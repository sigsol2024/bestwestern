<?php
/**
 * Settings API — save / load site settings
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

header('Content-Type: application/json');

// #region agent log
if (function_exists('cmsDebugLog')) {
    cmsDebugLog('H1', 'admin/api/settings.php:14', 'settings api entered', [
        'method' => $_SERVER['REQUEST_METHOD'] ?? '',
        'contentType' => $_SERVER['CONTENT_TYPE'] ?? '',
        'hasSessionAdminId' => isset($_SESSION['admin_id']),
        'hasAuthCookie' => isset($_COOKIE['cms_admin_auth']),
        'hasCsrfCookie' => isset($_COOKIE['cms_csrf_token']),
        'postKeys' => array_values(array_keys($_POST ?? [])),
    ]);
}
// #endregion

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $key = $_GET['key'] ?? null;

            if ($key) {
                $value = getSetting($key);
                jsonResponse(['success' => true, 'key' => $key, 'value' => $value]);
            }

            $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings ORDER BY setting_key");
            $settings = $stmt->fetchAll();

            $settingsArray = [];
            foreach ($settings as $setting) {
                $settingsArray[$setting['setting_key']] = $setting['setting_value'];
            }

            jsonResponse(['success' => true, 'settings' => $settingsArray]);
            break;

        case 'POST':
            $csrfToken = getRequestCSRFToken();
            // #region agent log
            if (function_exists('cmsDebugLog')) {
                $sessionToken = isset($_SESSION['csrf_token']) ? (string) $_SESSION['csrf_token'] : '';
                $cookieToken = isset($_COOKIE['cms_csrf_token']) ? (string) $_COOKIE['cms_csrf_token'] : '';
                cmsDebugLog('H3', 'admin/api/settings.php:49', 'settings csrf evaluation', [
                    'csrfTokenLength' => strlen((string) $csrfToken),
                    'sessionTokenLength' => strlen($sessionToken),
                    'cookieTokenLength' => strlen($cookieToken),
                    'matchesSession' => ($sessionToken !== '' && hash_equals($sessionToken, (string) $csrfToken)),
                    'matchesCookie' => ($cookieToken !== '' && hash_equals($cookieToken, (string) $csrfToken)),
                    'postHasCsrf' => array_key_exists('csrf_token', $_POST),
                ]);
            }
            // #endregion
            if (!verifyCSRFToken($csrfToken)) {
                // #region agent log
                if (function_exists('cmsDebugLog')) {
                    cmsDebugLog('H3', 'admin/api/settings.php:61', 'settings csrf rejected', [
                        'reason' => 'verifyCSRFToken returned false',
                    ]);
                }
                // #endregion
                jsonResponse(['success' => false, 'message' => 'Invalid security token'], 403);
            }

            $data = $_POST;
            if (!is_array($data) || empty($data)) {
                $data = getJsonRequestBody();
            }

            // #region agent log
            if (function_exists('cmsDebugLog')) {
                cmsDebugLog('H4', 'admin/api/settings.php:73', 'settings payload received', [
                    'dataType' => gettype($data),
                    'keyCount' => is_array($data) ? count($data) : -1,
                    'hasSmtpSecret' => is_array($data) && array_key_exists('smtp_secret', $data),
                    'hasSiteName' => is_array($data) && array_key_exists('site_name', $data),
                    'jsonError' => function_exists('getJsonRequestError') ? getJsonRequestError() : null,
                ]);
            }
            // #endregion

            if (!is_array($data)) {
                jsonResponse(['success' => false, 'message' => 'Invalid data format'], 400);
            }

            if (array_key_exists('smtp_secret', $data)) {
                $data['smtp_password'] = $data['smtp_secret'];
                unset($data['smtp_secret']);
            }
            unset($data['csrf_token'], $data['csrfToken']);

            $updated = [];
            foreach ($data as $key => $value) {
                if (updateSetting($key, $value)) {
                    $updated[] = $key;
                }
            }

            // #region agent log
            if (function_exists('cmsDebugLog')) {
                cmsDebugLog('H5', 'admin/api/settings.php:98', 'settings save completed', [
                    'updatedCount' => count($updated),
                    'updatedKeysSample' => array_slice($updated, 0, 8),
                ]);
            }
            // #endregion

            jsonResponse([
                'success' => true,
                'message' => count($updated) . ' setting(s) updated successfully',
                'updated' => $updated
            ]);
            break;

        default:
            jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
    }
} catch (PDOException $e) {
    error_log("Settings API error: " . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'Database error occurred'], 500);
}
