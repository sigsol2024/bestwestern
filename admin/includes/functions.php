<?php
/**
 * Helper Functions
 * Utility functions for the admin panel
 */

function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    if ($data === null || $data === false) {
        return '';
    }
    if (!is_string($data)) {
        $data = (string) $data;
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function generateSlug($string) {
    if ($string === null || !is_string($string)) {
        $string = (string) $string;
    }
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    if (!headers_sent()) {
        setcookie('cms_csrf_token', $_SESSION['csrf_token'], [
            'expires' => time() + 86400,
            'path' => '/',
            'domain' => '',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    if (empty($token)) {
        return false;
    }
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    $cookieToken = $_COOKIE['cms_csrf_token'] ?? '';
    return is_string($cookieToken) && $cookieToken !== '' && hash_equals($cookieToken, $token);
}

function getRawRequestBody() {
    if (!array_key_exists('__cms_raw_request_body', $GLOBALS)) {
        $GLOBALS['__cms_raw_request_body'] = (string) file_get_contents('php://input');
    }
    return $GLOBALS['__cms_raw_request_body'];
}

function getJsonRequestBody() {
    if (!array_key_exists('__cms_json_request_body', $GLOBALS)) {
        $rawBody = trim(getRawRequestBody());
        if ($rawBody === '') {
            $GLOBALS['__cms_json_request_body'] = [];
            $GLOBALS['__cms_json_request_error'] = JSON_ERROR_NONE;
        } else {
            $GLOBALS['__cms_json_request_body'] = json_decode($rawBody, true);
            $GLOBALS['__cms_json_request_error'] = json_last_error();
        }
    }
    return $GLOBALS['__cms_json_request_body'];
}

function getJsonRequestError() {
    getJsonRequestBody();
    return (int) ($GLOBALS['__cms_json_request_error'] ?? JSON_ERROR_NONE);
}

function cmsDebugLog($hypothesisId, $location, $message, array $data = [], $runId = 'initial') {
    $logPath = BASE_PATH . DIRECTORY_SEPARATOR . 'debug-2bd4ec.log';
    $payload = [
        'sessionId' => '2bd4ec',
        'runId' => (string) $runId,
        'hypothesisId' => (string) $hypothesisId,
        'location' => (string) $location,
        'message' => (string) $message,
        'data' => $data,
        'timestamp' => round(microtime(true) * 1000),
    ];
    @file_put_contents($logPath, json_encode($payload, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function getRequestCSRFToken() {
    $headers = getAllHeaders();
    foreach ($headers as $name => $value) {
        if (strcasecmp((string) $name, 'X-CSRF-Token') === 0) {
            return is_string($value) ? $value : '';
        }
    }
    if (!empty($_POST['csrf_token'])) {
        return (string) $_POST['csrf_token'];
    }
    $jsonBody = getJsonRequestBody();
    if (is_array($jsonBody)) {
        if (!empty($jsonBody['csrf_token'])) {
            return (string) $jsonBody['csrf_token'];
        }
        if (!empty($jsonBody['csrfToken'])) {
            return (string) $jsonBody['csrfToken'];
        }
    }
    return '';
}

if (!function_exists('getAllHeaders')) {
    function getAllHeaders() {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (strpos($name, 'HTTP_') === 0) {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

function validateImageFile($file) {
    $errors = [];
    if (!isset($file['error']) || is_array($file['error'])) {
        $errors[] = 'Invalid file upload parameters.';
        return $errors;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'File upload error: ' . $file['error'];
        return $errors;
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = 'File size exceeds maximum allowed size of ' . formatFileSize(MAX_FILE_SIZE);
        return $errors;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_IMAGE_EXTENSIONS)) {
        $errors[] = 'Invalid file type. Allowed types: ' . implode(', ', ALLOWED_IMAGE_EXTENSIONS);
        return $errors;
    }
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
    } elseif (function_exists('mime_content_type')) {
        $mimeType = mime_content_type($file['tmp_name']);
    } else {
        $mimeType = 'image/' . $ext;
    }
    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        $errors[] = 'Invalid MIME type.';
        return $errors;
    }

    // Extra validation: ensure the file is actually a readable image
    // (protects against polyglots / spoofed MIME types)
    if (function_exists('getimagesize')) {
        $imgInfo = @getimagesize($file['tmp_name']);
        if ($imgInfo === false || empty($imgInfo[0]) || empty($imgInfo[1])) {
            $errors[] = 'Uploaded file is not a valid image.';
            return $errors;
        }
    }
    return $errors;
}

function uploadImage($file, $subdirectory = '') {
    $errors = validateImageFile($file);
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    if (!file_exists(UPLOAD_DIR)) {
        if (!mkdir(UPLOAD_DIR, 0755, true)) {
            error_log("Failed to create base upload directory: " . UPLOAD_DIR);
            return ['success' => false, 'errors' => ['Failed to create upload directory. Please check server permissions.']];
        }
    }

    if ($subdirectory) {
        $subdirectory = sanitize($subdirectory);
        $subdirectory = str_replace(['..', '/', '\\'], '', $subdirectory);
        $subdirectory = preg_replace('/[^a-zA-Z0-9_-]/', '', $subdirectory);
        if (empty($subdirectory)) {
            $subdirectory = '';
        }
    }

    $uploadPath = UPLOAD_DIR . ($subdirectory ? $subdirectory . '/' : '');
    $uploadPath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $uploadPath);
    $uploadPath = rtrim($uploadPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    $realUploadDir = realpath(UPLOAD_DIR);
    if (!$realUploadDir) {
        error_log("Base upload directory does not exist: " . UPLOAD_DIR);
        return ['success' => false, 'errors' => ['Upload directory not found. Please contact administrator.']];
    }

    if (!file_exists($uploadPath)) {
        if (!mkdir($uploadPath, 0755, true)) {
            error_log("Failed to create upload subdirectory: " . $uploadPath);
            return ['success' => false, 'errors' => ['Failed to create upload directory. Please check server permissions.']];
        }
    }

    $realUploadPath = realpath($uploadPath);
    if (!$realUploadPath || strpos($realUploadPath, $realUploadDir) !== 0) {
        error_log("Upload path validation failed. Base: {$realUploadDir}, Target: {$realUploadPath}");
        return ['success' => false, 'errors' => ['Invalid upload path. Security check failed.']];
    }

    if (!is_writable($uploadPath)) {
        error_log("Upload directory is not writable: " . $uploadPath);
        return ['success' => false, 'errors' => ['Upload directory is not writable. Please check server permissions.']];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid('img_', true) . '.' . $ext;
    $filePath = $uploadPath . $filename;

    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        $uploadError = error_get_last();
        error_log("Failed to move uploaded file. Error: " . ($uploadError ? $uploadError['message'] : 'Unknown error'));
        return ['success' => false, 'errors' => ['Failed to save uploaded file. Please try again.']];
    }

    $subdirPath = $subdirectory ? $subdirectory . '/' : '';
    $relativePath = 'assets/uploads/' . $subdirPath . $filename;

    return [
        'success' => true,
        'filename' => $filename,
        'path' => $relativePath,
        'full_path' => $filePath
    ];
}

/**
 * Get site setting (admin / API helpers)
 */
function getSetting($key, $default = null) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

/**
 * Upsert site setting
 */
function updateSetting($key, $value) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)
                               ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = CURRENT_TIMESTAMP");
        return $stmt->execute([$key, $value, $value]);
    } catch (PDOException $e) {
        error_log("Setting update error: " . $e->getMessage());
        return false;
    }
}

function deleteFile($filePath) {
    $filePath = sanitize($filePath);
    $filePath = str_replace(['..', '\\'], '', $filePath);
    $filePath = ltrim($filePath, '/');
    if (strpos($filePath, 'assets/uploads/') !== 0) {
        error_log("Attempted to delete file outside uploads directory: " . $filePath);
        return false;
    }
    $fullPath = SITE_PATH . '/' . $filePath;
    if (file_exists($fullPath) && is_file($fullPath)) {
        $deleted = @unlink($fullPath);
        if (!$deleted) {
            error_log("Failed to delete file: " . $fullPath);
            return false;
        }
        return true;
    }
    return true;
}

/**
 * Validate CMS page slug for page_sections.page (alphanumeric, underscore, hyphen).
 *
 * @return string|null
 */
function validatePageSlug($page) {
    $page = trim((string)$page);
    if ($page === '' || strlen($page) > 50) {
        return null;
    }
    return preg_match('/^[a-z0-9_-]+$/i', $page) ? $page : null;
}

/**
 * Validate section_key for page_sections (alphanumeric, underscore, hyphen).
 *
 * @return string|null
 */
function validateSectionKey($key) {
    $key = trim((string)$key);
    if ($key === '' || strlen($key) > 100) {
        return null;
    }
    return preg_match('/^[a-z0-9_-]+$/i', $key) ? $key : null;
}

/**
 * Normalize content_type to allowed ENUM values.
 */
function validateContentType($type) {
    $type = strtolower(trim((string)$type));
    $allowed = ['text', 'html', 'image', 'json'];
    return in_array($type, $allowed, true) ? $type : 'text';
}

function jsonResponse($data, $statusCode = 200) {
    while (ob_get_level()) {
        ob_end_clean();
    }
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

