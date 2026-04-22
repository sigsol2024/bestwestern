<?php
/**
 * Authentication Functions
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

function adminAuthCookieName() {
    return 'cms_admin_auth';
}

function adminAuthCookieOptions($expires) {
    return [
        'expires' => (int) $expires,
        'path' => '/',
        'domain' => '',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ];
}

function clearAdminAuthCookie() {
    setcookie(adminAuthCookieName(), '', adminAuthCookieOptions(time() - 3600));
    unset($_COOKIE[adminAuthCookieName()]);
}

function buildAdminAuthCookieValue($adminId, $username, $expires) {
    $adminId = (int) $adminId;
    $username = trim((string) $username);
    $expires = (int) $expires;
    $payload = $adminId . '|' . $username . '|' . $expires;
    $sig = hash_hmac('sha256', $payload, cms_admin_auth_cookie_secret());
    return $payload . '|' . $sig;
}

function setAdminAuthCookie($adminId, $username) {
    $expires = time() + SESSION_TIMEOUT;
    $value = buildAdminAuthCookieValue($adminId, $username, $expires);
    setcookie(adminAuthCookieName(), $value, adminAuthCookieOptions($expires));
    $_COOKIE[adminAuthCookieName()] = $value;
}

function restoreAdminSessionFromCookie() {
    global $pdo;

    $raw = $_COOKIE[adminAuthCookieName()] ?? '';
    if (!is_string($raw) || $raw === '') {
        return false;
    }

    $parts = explode('|', $raw);
    if (count($parts) !== 4) {
        clearAdminAuthCookie();
        return false;
    }

    [$adminIdRaw, $usernameRaw, $expiresRaw, $sig] = $parts;
    $payload = $adminIdRaw . '|' . $usernameRaw . '|' . $expiresRaw;
    $expectedSig = hash_hmac('sha256', $payload, cms_admin_auth_cookie_secret());
    if (!hash_equals($expectedSig, (string) $sig)) {
        clearAdminAuthCookie();
        return false;
    }

    $adminId = (int) $adminIdRaw;
    $username = trim((string) $usernameRaw);
    $expires = (int) $expiresRaw;
    if ($adminId < 1 || $username === '' || $expires < time()) {
        clearAdminAuthCookie();
        return false;
    }

    if (!$pdo instanceof PDO) {
        return false;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, email FROM admin_users WHERE id = ? AND username = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$adminId, $username]);
        $user = $stmt->fetch();
        if (!$user) {
            clearAdminAuthCookie();
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_email'] = $user['email'];
        $_SESSION['last_activity'] = time();
        setAdminAuthCookie($user['id'], $user['username']);
        generateCSRFToken();
        return true;
    } catch (PDOException $e) {
        error_log("Cookie auth restore error: " . $e->getMessage());
        return false;
    }
}

function isLoggedIn() {
    if ((!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) && !restoreAdminSessionFromCookie()) {
        return false;
    }
    if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
        return false;
    }
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
            session_unset();
            session_destroy();
            clearAdminAuthCookie();
            return false;
        }
    }
    $_SESSION['last_activity'] = time();
    setAdminAuthCookie($_SESSION['admin_id'], $_SESSION['admin_username']);
    return true;
}

function requireLogin() {
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
    $callingFile = $backtrace[1]['file'] ?? '';
    $isApiFile = strpos($callingFile, '/api/') !== false || strpos($callingFile, '\\api\\') !== false;

    if (!isLoggedIn()) {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $httpAccept = $_SERVER['HTTP_ACCEPT'] ?? '';

        $isApiRequest = $isApiFile
            || strpos($requestUri, '/api/') !== false
            || strpos($scriptName, '/api/') !== false
            || (isset($httpAccept) && strpos($httpAccept, 'application/json') !== false);

        if ($isApiRequest) {
            while (ob_get_level()) {
                ob_end_clean();
            }
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Authentication required. Please log in.']);
            exit;
        }

        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
        redirect(ADMIN_URL . 'index.php');
    }

    generateCSRFToken();
}

function login($username, $password) {
    global $pdo;
    if (!checkLoginRateLimit($username)) {
        return ['success' => false, 'message' => 'Too many login attempts. Please try again later.'];
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND is_active = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['last_activity'] = time();
            setAdminAuthCookie($user['id'], $user['username']);

            $stmt = $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);

            clearFailedLoginAttempts($username);

            generateCSRFToken();

            return ['success' => true, 'user' => $user];
        }

        recordFailedLoginAttempt($username);
        // Small delay to slow down brute-force attempts (kept short for UX)
        usleep(random_int(120000, 280000)); // 120–280ms
        return ['success' => false, 'message' => 'Invalid username or password.'];
    } catch(PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return ['success' => false, 'message' => 'An error occurred. Please try again.'];
    }
}

function logout() {
    clearAdminAuthCookie();
    session_unset();
    session_destroy();
    redirect(ADMIN_URL . 'index.php');
}

function getClientIpForRateLimit() {
    // Trust only direct REMOTE_ADDR (avoids spoofing via X-Forwarded-For unless you terminate TLS at a trusted proxy)
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!is_string($ip)) {
        $ip = '';
    }
    $ip = trim($ip);
    // Normalize to a safe token
    if ($ip === '' || strlen($ip) > 64) {
        $ip = 'unknown';
    }
    return $ip;
}

function getRateLimitStoreDir() {
    $dir = rtrim(ADMIN_PATH, '/\\') . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'ratelimit';
    if (!file_exists($dir)) {
        @mkdir($dir, 0755, true);
    }
    return $dir;
}

function readRateLimitTimestamps($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    $raw = @file_get_contents($filePath);
    if ($raw === false || $raw === '') {
        return [];
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return [];
    }
    $out = [];
    foreach ($data as $t) {
        if (is_int($t) || ctype_digit((string)$t)) {
            $out[] = (int)$t;
        }
    }
    return $out;
}

function writeRateLimitTimestamps($filePath, $timestamps) {
    $tmp = $filePath . '.tmp';
    @file_put_contents($tmp, json_encode(array_values($timestamps)), LOCK_EX);
    @rename($tmp, $filePath);
}

function checkAndBumpRateLimit($bucketKey, $maxAttempts, $windowSeconds) {
    $dir = getRateLimitStoreDir();
    $file = $dir . DIRECTORY_SEPARATOR . hash('sha256', $bucketKey) . '.json';
    $now = time();
    $windowStart = $now - (int)$windowSeconds;

    // Best-effort lock to reduce race conditions
    $fp = @fopen($file, 'c+');
    if ($fp) {
        @flock($fp, LOCK_EX);
    }

    $timestamps = readRateLimitTimestamps($file);
    $timestamps = array_values(array_filter($timestamps, function ($t) use ($windowStart) {
        return $t > $windowStart;
    }));

    $allowed = count($timestamps) < (int)$maxAttempts;
    if ($allowed) {
        $timestamps[] = $now;
        writeRateLimitTimestamps($file, $timestamps);
    }

    if ($fp) {
        @flock($fp, LOCK_UN);
        @fclose($fp);
    }

    return $allowed;
}

function checkLoginRateLimit($username) {
    // Two buckets:
    // - per-username+ip (stops targeted guessing)
    // - per-ip global (stops broad guessing)
    $ip = getClientIpForRateLimit();
    $u = strtolower(trim((string)$username));
    $u = preg_replace('/[^a-z0-9_\-\.@]/', '', $u);
    if ($u === '') {
        $u = 'unknown-user';
    }

    $okUser = checkAndBumpRateLimit('login:user:' . $u . ':ip:' . $ip, MAX_LOGIN_ATTEMPTS, LOGIN_ATTEMPT_WINDOW);
    if (!$okUser) {
        return false;
    }
    // Slightly higher global threshold per IP
    $okIp = checkAndBumpRateLimit('login:ip:' . $ip, MAX_LOGIN_ATTEMPTS * 3, LOGIN_ATTEMPT_WINDOW);
    return $okIp;
}

function recordFailedLoginAttempt($username) {
    // Kept for backward compatibility; rate limit bucket bumps happen in checkLoginRateLimit().
    // This function can be used later for analytics/audit logging.
    return;
}

function clearFailedLoginAttempts($username) {
    // Best-effort: clear only the per-user+ip bucket for this session IP
    $ip = getClientIpForRateLimit();
    $u = strtolower(trim((string)$username));
    $u = preg_replace('/[^a-z0-9_\-\.@]/', '', $u);
    if ($u === '') {
        return;
    }
    $dir = getRateLimitStoreDir();
    $file = $dir . DIRECTORY_SEPARATOR . hash('sha256', 'login:user:' . $u . ':ip:' . $ip) . '.json';
    if (file_exists($file)) {
        @unlink($file);
    }
}

function getCurrentUserId() {
    return $_SESSION['admin_id'] ?? null;
}

function getCurrentUsername() {
    return $_SESSION['admin_username'] ?? null;
}

