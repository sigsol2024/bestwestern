<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $page = validatePageSlug((string)($_GET['page'] ?? ''));
            $section = validateSectionKey((string)($_GET['section'] ?? ''));
            if ($page && $section) {
                $stmt = $pdo->prepare("SELECT * FROM page_sections WHERE page = ? AND section_key = ?");
                $stmt->execute([$page, $section]);
                $sectionData = $stmt->fetch();
                if ($sectionData) jsonResponse(['success' => true, 'section' => $sectionData]);
                jsonResponse(['success' => false, 'message' => 'Section not found'], 404);
            }
            if ($page) {
                $stmt = $pdo->prepare("SELECT * FROM page_sections WHERE page = ? ORDER BY section_key");
                $stmt->execute([$page]);
                jsonResponse(['success' => true, 'sections' => $stmt->fetchAll()]);
            }
            jsonResponse(['success' => false, 'message' => 'Page parameter required'], 400);
            break;

        case 'POST':
            $csrfToken = getRequestCSRFToken();
            if (!verifyCSRFToken($csrfToken)) {
                jsonResponse(['success' => false, 'message' => 'Invalid security token'], 403);
            }
            $contentType = (string) ($_SERVER['CONTENT_TYPE'] ?? '');
            if (stripos($contentType, 'application/json') !== false) {
                $data = getJsonRequestBody();
                if (getJsonRequestError() !== JSON_ERROR_NONE || !is_array($data)) {
                    jsonResponse(['success' => false, 'message' => 'Invalid JSON data'], 400);
                }
            } elseif (!empty($_POST)) {
                $data = $_POST;
            } else {
                $data = getJsonRequestBody();
                if (getJsonRequestError() !== JSON_ERROR_NONE || !is_array($data)) {
                    jsonResponse(['success' => false, 'message' => 'Expected JSON or form POST (multipart) body'], 400);
                }
            }
            if (!isset($data['page']) || !isset($data['section_key'])) {
                jsonResponse(['success' => false, 'message' => 'Page and section_key required'], 400);
            }

            $pageName = validatePageSlug((string)$data['page']);
            $sectionKey = validateSectionKey((string)$data['section_key']);
            if ($pageName === null || $sectionKey === null) {
                jsonResponse(['success' => false, 'message' => 'Invalid page or section_key'], 400);
            }

            $contentType = validateContentType((string)($data['content_type'] ?? 'text'));
            $content = $data['content'] ?? '';
            if (!is_string($content)) {
                $content = is_array($content) ? (string)json_encode($content) : (string)$content;
            }
            $encRaw = trim((string)($data['content_encoding'] ?? ''));
            if ($encRaw !== '' && strcasecmp($encRaw, 'base64') === 0) {
                $b = preg_replace('/\s+/', '', $content);
                $decoded = base64_decode($b, true);
                if ($decoded === false) {
                    jsonResponse(['success' => false, 'message' => 'Invalid base64 content'], 400);
                }
                $content = $decoded;
            }

            $stmt = $pdo->prepare("INSERT INTO page_sections (page, section_key, content_type, content)
                                   VALUES (?, ?, ?, ?)
                                   ON DUPLICATE KEY UPDATE content = ?, content_type = ?, updated_at = NOW()");
            $stmt->execute([$pageName, $sectionKey, $contentType, $content, $content, $contentType]);
            jsonResponse(['success' => true, 'message' => 'Section updated successfully']);
            break;

        default:
            jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
    }
} catch(PDOException $e) {
    error_log("Pages API error: " . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'Database error occurred'], 500);
}

