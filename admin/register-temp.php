<?php
/**
 * TEMPORARY — first-time admin bootstrap only.
 *
 * DELETE THIS FILE after you have created your admin account.
 */
declare(strict_types=1);

// Set to false or delete this file when finished.
const CMS_TEMP_REGISTER_ENABLED = true;
const CMS_TEMP_REGISTER_SESSION_KEY = 'bootstrap_gate_register_temp';

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if (!CMS_TEMP_REGISTER_ENABLED) {
    http_response_code(410);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "This registration form is disabled. Remove CMS_TEMP_REGISTER_ENABLED or restore it only if you still need this file (not recommended).\n";
    exit;
}

function registerTempBootstrapCode(): string {
    return trim((string) cms_bootstrap_gate_code());
}

function registerTempHasAdmin(PDO $pdo): bool {
    $stmt = $pdo->query('SELECT COUNT(*) FROM admin_users');
    return ((int) $stmt->fetchColumn()) > 0;
}

$bootstrapCode = registerTempBootstrapCode();
if ($bootstrapCode === '') {
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "Bootstrap gate is not configured. Set a value in admin/includes/config.php before using this page.\n";
    exit;
}

$error = '';
$success = '';
$gateError = '';
$gateUnlocked = !empty($_SESSION[CMS_TEMP_REGISTER_SESSION_KEY]);
$hasExistingAdmin = false;
$adminStateKnown = false;

if ($pdo instanceof PDO) {
    try {
        $hasExistingAdmin = registerTempHasAdmin($pdo);
        $adminStateKnown = true;
    } catch (PDOException $e) {
        error_log('register-temp admin count: ' . $e->getMessage());
        $adminStateKnown = false;
    }
}

if (!$gateUnlocked && $_SERVER['REQUEST_METHOD'] === 'POST' && (($_POST['action'] ?? '') === 'unlock_bootstrap')) {
    $csrf = $_POST['csrf_token'] ?? '';
    $providedCode = trim((string) ($_POST['bootstrap_code'] ?? ''));

    if (!verifyCSRFToken($csrf)) {
        $gateError = 'Invalid security token. Refresh the page and try again.';
    } elseif ($providedCode === '' || !hash_equals($bootstrapCode, $providedCode)) {
        $gateError = 'Access code is incorrect.';
    } else {
        session_regenerate_id(true);
        $_SESSION[CMS_TEMP_REGISTER_SESSION_KEY] = 1;
        $gateUnlocked = true;
    }
}

if ($gateUnlocked && $_SERVER['REQUEST_METHOD'] === 'POST' && (($_POST['action'] ?? '') === 'create_admin')) {
    $csrf = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrf)) {
        $error = 'Invalid security token. Refresh the page and try again!';
    } elseif (!$adminStateKnown) {
        $error = 'Database error. Check that the `admin_users` table exists and database credentials are correct.';
    } elseif ($hasExistingAdmin) {
        $error = 'An admin account already exists. This bootstrap form is only available for first-time setup.';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $password2 = (string)($_POST['password_confirm'] ?? '');

        if ($username === '' || strlen($username) < 3) {
            $error = 'Username must be at least 3 characters.';
        } elseif (!preg_match('/^[a-zA-Z0-9_@.-]+$/', $username)) {
            $error = 'Username may only contain letters, numbers, and _ @ . -';
        } elseif ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $password2) {
            $error = 'Passwords do not match.';
        } else {
            try {
                $check = $pdo->prepare('SELECT id FROM admin_users WHERE username = ? OR email = ? LIMIT 1');
                $check->execute([$username, $email]);
                if ($check->fetch()) {
                    $error = 'That username or email is already registered.';
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $ins = $pdo->prepare(
                        'INSERT INTO admin_users (username, email, password_hash, is_active) VALUES (?, ?, ?, 1)'
                    );
                    $ins->execute([$username, $email, $hash]);
                    $hasExistingAdmin = true;
                    $success = 'Admin account created. You can log in now. Delete <code>admin/register-temp.php</code> from the server.';
                }
            } catch (PDOException $e) {
                error_log('register-temp: ' . $e->getMessage());
                $error = 'Database error. Check that the `admin_users` table exists and credentials in config are correct.';
            }
        }
    }
}

$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bootstrap Access - <?= htmlspecialchars((string) getSetting('cms_product_name', cms_default_setting('cms_product_name')), ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="<?= htmlspecialchars(ADMIN_URL . 'assets/css/admin.css', ENT_QUOTES, 'UTF-8') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="login-page">
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="login-logo" style="background:#d63638;"><i class="fas fa-user-plus" style="font-size:28px;"></i></div>
        <h1>Bootstrap Access</h1>
        <p class="login-subtitle" style="color:#b45309;">
          This page is only for first-time setup. Remove <strong>register-temp.php</strong> after use.
        </p>
      </div>

      <?php if (!$gateUnlocked): ?>
        <?php if ($gateError): ?>
        <div class="alert alert-error" style="margin-bottom:20px;padding:12px;border-left:4px solid var(--error-color);background:#fef2f2;">
          <?= htmlspecialchars($gateError, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="register-temp.php" class="login-form" autocomplete="off">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
          <input type="hidden" name="action" value="unlock_bootstrap">

          <div class="form-group">
            <label for="bootstrap_code">Access code</label>
            <div class="input-wrapper">
              <input type="password" id="bootstrap_code" name="bootstrap_code" required autocomplete="current-password">
              <button type="button" class="password-toggle" data-password-toggle="bootstrap_code" aria-label="Show password" aria-pressed="false">
                <i class="fas fa-eye" aria-hidden="true"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn-login"><span>Unlock</span></button>
        </form>
        <p style="text-align:center;margin-top:20px;color:#6b7280;font-size:13px;">
          The access code is defined in <code>admin/includes/config.php</code>.
        </p>
      <?php elseif ($success): ?>
        <div class="alert alert-error" style="margin-bottom:20px;padding:12px;border-left:4px solid #00a32a;background:#ecfdf5;color:#166534;">
          <?= $success ?>
        </div>
        <p style="text-align:center;"><a class="btn-login" style="display:inline-block;width:auto;text-decoration:none;" href="<?= htmlspecialchars(ADMIN_URL . 'index.php', ENT_QUOTES, 'UTF-8') ?>">Go to login</a></p>
      <?php elseif (!$adminStateKnown): ?>
        <div class="alert alert-error" style="margin-bottom:20px;padding:12px;border-left:4px solid var(--error-color);background:#fef2f2;">
          Unable to inspect the admin table. Check the database connection and confirm <code>admin_users</code> exists.
        </div>
        <p style="text-align:center;"><a class="btn-login" style="display:inline-block;width:auto;text-decoration:none;" href="<?= htmlspecialchars(ADMIN_URL . 'index.php', ENT_QUOTES, 'UTF-8') ?>">Go to login</a></p>
      <?php elseif ($hasExistingAdmin): ?>
        <div class="alert alert-error" style="margin-bottom:20px;padding:12px;border-left:4px solid #2563eb;background:#eff6ff;color:#1d4ed8;">
          An admin account already exists in the database. This page will not show the bootstrap form again.
        </div>
        <p style="text-align:center;"><a class="btn-login" style="display:inline-block;width:auto;text-decoration:none;" href="<?= htmlspecialchars(ADMIN_URL . 'index.php', ENT_QUOTES, 'UTF-8') ?>">Go to login</a></p>
      <?php else: ?>
        <?php if ($error): ?>
        <div class="alert alert-error" style="margin-bottom:20px;padding:12px;border-left:4px solid var(--error-color);background:#fef2f2;">
          <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>

        <div class="alert alert-error" style="margin-bottom:20px;padding:12px;border-left:4px solid #d97706;background:#fffbeb;color:#92400e;">
          No admin account was found. You can create the first admin below.
        </div>

        <form method="POST" action="register-temp.php" class="login-form" autocomplete="off">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
          <input type="hidden" name="action" value="create_admin">

          <div class="form-group">
            <label for="username">Username</label>
            <div class="input-wrapper">
              <input type="text" id="username" name="username" required minlength="3" maxlength="100"
                     autocomplete="off" value="<?= isset($_POST['username']) ? htmlspecialchars((string)$_POST['username'], ENT_QUOTES, 'UTF-8') : '' ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrapper">
              <input type="email" id="email" name="email" required maxlength="255"
                     autocomplete="off" value="<?= isset($_POST['email']) ? htmlspecialchars((string)$_POST['email'], ENT_QUOTES, 'UTF-8') : '' ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
              <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
              <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password" aria-pressed="false">
                <i class="fas fa-eye" aria-hidden="true"></i>
              </button>
            </div>
          </div>
          <div class="form-group">
            <label for="password_confirm">Confirm password</label>
            <div class="input-wrapper">
              <input type="password" id="password_confirm" name="password_confirm" required minlength="8" autocomplete="new-password">
              <button type="button" class="password-toggle" data-password-toggle="password_confirm" aria-label="Show password" aria-pressed="false">
                <i class="fas fa-eye" aria-hidden="true"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn-login"><span>Create admin</span></button>
        </form>
        <p style="text-align:center;margin-top:20px;"><a href="<?= htmlspecialchars(ADMIN_URL . 'index.php', ENT_QUOTES, 'UTF-8') ?>" style="color:#6b7280;">Back to login</a></p>
      <?php endif; ?>
    </div>
  </div>
  <script>
  (function () {
    document.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var id = btn.getAttribute('data-password-toggle');
        var input = id ? document.getElementById(id) : null;
        if (!input) return;
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        btn.setAttribute('aria-pressed', show ? 'true' : 'false');
        var icon = btn.querySelector('i');
        if (icon) icon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
      });
    });
  })();
  </script>
</body>
</html>
