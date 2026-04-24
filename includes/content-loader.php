<?php
/**
 * Content loader helper.
 * Functions to load dynamic content from the database for the frontend.
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

require_once BASE_PATH . '/includes/url.php';

// Include database config if not already included
if (!isset($pdo)) {
    try {
        require_once BASE_PATH . '/admin/includes/config.php';
    } catch (Exception $e) {
        // If config fails to load, set $pdo to null so functions can handle gracefully
        $pdo = null;
        error_log("Failed to load config: " . $e->getMessage());
    }
}

/**
 * Escape site name and wrap "Plus" / "PLUS" (whole word) in brand-red for header/footer lockups.
 */
function site_brand_name_html(string $name): string {
    $e = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $out = preg_replace('/(?<![A-Za-z])(Plus|PLUS)(?![A-Za-z])/u', '<span class="text-brand-red">$1</span>', $e);
    return is_string($out) ? $out : $e;
}

/**
 * Get page section content
 */
function getPageSection($page, $sectionKey, $default = '') {
    global $pdo;
    
    if (!isset($pdo) || $pdo === null) {
        return $default;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT content FROM page_sections WHERE page = ? AND section_key = ?");
        $stmt->execute([$page, $sectionKey]);
        $result = $stmt->fetch();
        
        return $result ? $result['content'] : $default;
    } catch(PDOException $e) {
        error_log("Content loader error: " . $e->getMessage());
        return $default;
    }
}

/**
 * Strip characters that are unsafe or invalid inside an HTML class attribute (Tailwind utilities).
 *
 * @return string trimmed utility string (may be empty if input contained only invalid characters)
 */
function sanitize_tailwind_utilities(string $classes): string {
    $classes = trim($classes);
    if ($classes === '') {
        return '';
    }
    $clean = preg_replace('/[^a-zA-Z0-9_\-\[\]\/\s:.%()+,=]/', '', $classes);
    $clean = is_string($clean) ? $clean : '';
    return trim(preg_replace('/\s+/', ' ', $clean));
}

/**
 * Drop good_to_know keys removed from the room detail template.
 *
 * @param array<string, mixed> $g
 * @return array<string, mixed>
 */
function strip_legacy_room_good_to_know(array $g): array {
    unset(
        $g['booking_checkin_default'],
        $g['booking_checkout_default'],
        $g['booking_guests_default'],
        $g['floor_plan_url'],
        $g['testimonial_quote'],
        $g['testimonial_by'],
        $g['trending_message'],
        $g['booking_trust_line'],
        $g['booking_trust_subline'],
        $g['panel_footnote']
    );
    return $g;
}

/**
 * Homepage hero title: replace legacy boxed span (border/rounded) with the current accent class.
 * Stored DB content may still contain the older markup.
 */
function normalize_home_hero_title_html(string $html): string {
    $legacyClass = 'class="italic text-primary border border-white/90 rounded-lg px-3 py-1 inline-block shadow-sm"';
    $strokeClass = 'class="italic text-primary site-hero-accent-text"';
    $html = str_replace($legacyClass, $strokeClass, $html);
    // Any other variant that still uses border-white/90 on the accent span
    $replaced = preg_replace(
        '/class="([^"]*\bitalic\b[^"]*\btext-primary\b)[^"]*\bborder-white\/90[^"]*"/',
        'class="italic text-primary site-hero-accent-text"',
        $html
    );
    return is_string($replaced) ? $replaced : $html;
}

/**
 * Get site setting
 */
function getSiteSetting($key, $default = '') {
    global $pdo;
    
    if (!isset($pdo) || $pdo === null) {
        return $default;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        
        return $result ? $result['setting_value'] : $default;
    } catch(PDOException $e) {
        return $default;
    }
}

/**
 * Normalized #rrggbb from site_settings for Tailwind / CSS (invalid values fall back).
 */
function site_theme_color(string $settingKey, string $fallbackHex): string {
    $normalize = static function (string $v): ?string {
        $v = trim($v);
        if ($v === '') {
            return null;
        }
        if (preg_match('/^#([0-9a-fA-F]{3})$/', $v, $m)) {
            $h = $m[1];

            return '#' . $h[0] . $h[0] . $h[1] . $h[1] . $h[2] . $h[2];
        }
        if (preg_match('/^#[0-9a-fA-F]{6}$/i', $v)) {
            return strtolower($v);
        }

        return null;
    };
    $fb = $normalize(trim($fallbackHex)) ?? '#411d13';
    $raw = getSiteSetting($settingKey, $fb);
    $out = $normalize((string) $raw);

    return $out ?? $fb;
}

function site_maintenance_mode_enabled(): bool {
    $raw = trim((string) getSiteSetting('maintenance_mode', cms_default_setting('maintenance_mode')));
    return in_array(strtolower($raw), ['1', 'true', 'yes', 'on'], true);
}

function site_current_public_page_slug(): ?string {
    $script = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''), '.php');
    if ($script === '') {
        return null;
    }
    if ($script === 'index') {
        return 'index';
    }

    $allowed = [
        'about',
        'rooms',
        'room-details',
        'contact',
        'gallery',
        'dining',
        'amenities',
        'hotel-policy',
        'privacy-policy',
        'terms-and-conditions',
    ];

    return in_array($script, $allowed, true) ? $script : null;
}

function site_page_is_publicly_active(string $pageSlug): bool {
    if ($pageSlug === 'index') {
        return true;
    }
    if ($pageSlug === 'room-details') {
        $pageSlug = 'rooms';
    }
    $settingKey = 'page_active_' . $pageSlug;
    $raw = trim((string) getSiteSetting($settingKey, cms_default_setting($settingKey, '1')));
    return in_array(strtolower($raw), ['1', 'true', 'yes', 'on'], true);
}

function site_enforce_maintenance_mode(): void {
    if (PHP_SAPI === 'cli' || !site_maintenance_mode_enabled()) {
        return;
    }

    // Admin area never loads this file today, but keep a hard bypass so maintenance
    // cannot redirect /admin/* if content-loader is ever included there.
    $scriptPath = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    if (strpos($scriptPath, '/admin/') !== false) {
        return;
    }

    $script = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($script === 'maintenance.php') {
        return;
    }

    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Location: ' . site_url('maintenance'));
    exit;
}

/**
 * If maintenance mode is off, do not serve the maintenance page — send visitors to the homepage.
 * (Otherwise bookmarks / open tabs to /maintenance.php would show the maintenance screen after the site is live again.)
 */
function site_redirect_if_maintenance_disabled(): void {
    if (PHP_SAPI === 'cli' || site_maintenance_mode_enabled()) {
        return;
    }

    $script = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($script !== 'maintenance.php') {
        return;
    }

    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Location: ' . site_url('index'));
    exit;
}

function site_enforce_page_availability(): void {
    if (PHP_SAPI === 'cli') {
        return;
    }

    $pageSlug = site_current_public_page_slug();
    if ($pageSlug === null || $pageSlug === 'index') {
        return;
    }

    if (!site_page_is_publicly_active($pageSlug)) {
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Location: ' . site_url('index'));
        exit;
    }
}

site_enforce_maintenance_mode();
site_redirect_if_maintenance_disabled();
site_enforce_page_availability();

/**
 * Get all rooms (with optional filters)
 */
function getRooms($filters = []) {
    global $pdo;
    
    if (!isset($pdo) || $pdo === null) {
        return [];
    }
    
    $where = [];
    $params = [];
    
    if (isset($filters['is_active'])) {
        $where[] = "is_active = ?";
        $params[] = intval($filters['is_active']);
    }
    
    if (isset($filters['is_featured'])) {
        $where[] = "is_featured = ?";
        $params[] = intval($filters['is_featured']);
    }
    
    if (isset($filters['limit'])) {
        $limit = intval($filters['limit']);
    } else {
        $limit = 1000;
    }
    
    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    
    try {
        // Sort by display_order (non-zero first), then stable by id.
        // This avoids "most recently created/edited" looking order when many rooms share display_order=0.
        $stmt = $pdo->prepare("SELECT * FROM rooms {$whereClause} ORDER BY (display_order = 0) ASC, display_order ASC, id ASC LIMIT ?");
        $params[] = $limit;
        $stmt->execute($params);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Decode JSON fields
        foreach ($rooms as &$roomItem) {
            $roomItem['gallery_images'] = json_decode($roomItem['gallery_images'] ?? '[]', true);
            $roomItem['features'] = json_decode($roomItem['features'] ?? '[]', true);
            $roomItem['amenities'] = json_decode($roomItem['amenities'] ?? '[]', true);
            $roomItem['tags'] = json_decode($roomItem['tags'] ?? '[]', true);
            $roomItem['included_items'] = json_decode($roomItem['included_items'] ?? '[]', true);
            $gtk = json_decode($roomItem['good_to_know'] ?? '{}', true);
            $roomItem['good_to_know'] = strip_legacy_room_good_to_know(is_array($gtk) ? $gtk : []);
        }
        unset($roomItem);
        
        return $rooms;
    } catch(PDOException $e) {
        error_log("Get rooms error: " . $e->getMessage());
        return [];
    }
}

/**
 * Featured rooms for homepage carousel: prefer is_featured, else fill from active rooms.
 */
function getFeaturedRoomsForHome($limit = 12) {
    $limit = max(1, (int)$limit);
    $rooms = getRooms(['is_active' => 1, 'is_featured' => 1, 'limit' => $limit]);
    if (empty($rooms)) {
        $rooms = getRooms(['is_active' => 1, 'limit' => min(8, $limit)]);
    }
    return $rooms;
}

/**
 * Get single room by slug
 */
function getRoomBySlug($slug) {
    global $pdo;
    
    if (!isset($pdo) || $pdo === null) {
        return null;
    }
    
    $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($slug)));
    if (empty($slug)) {
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE slug = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$slug]);
        
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $room = $stmt->fetch();
        
        if ($room) {
            $room['gallery_images'] = json_decode($room['gallery_images'] ?? '[]', true);
            $room['features'] = json_decode($room['features'] ?? '[]', true);
            $room['amenities'] = json_decode($room['amenities'] ?? '[]', true);
            $room['tags'] = json_decode($room['tags'] ?? '[]', true);
            $room['included_items'] = json_decode($room['included_items'] ?? '[]', true);
            $gtk = json_decode($room['good_to_know'] ?? '{}', true);
            $room['good_to_know'] = strip_legacy_room_good_to_know(is_array($gtk) ? $gtk : []);
        }
        
        return $room ? $room : null;
    } catch(PDOException $e) {
        error_log("Get room by slug error: " . $e->getMessage());
        return null;
    }
}

/**
 * Escape output for HTML
 */
function e($string) {
    if (is_array($string) || is_object($string)) {
        return '';
    }
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

