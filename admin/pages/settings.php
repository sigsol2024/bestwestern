<?php
/**
 * Site Settings Page
 */

$pageTitle = 'Settings';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/header.php';

// Get all settings
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings ORDER BY setting_key");
    $settingsRows = $stmt->fetchAll();
    $settings = [];
    foreach ($settingsRows as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch(PDOException $e) {
    error_log("Settings page error: " . $e->getMessage());
    $settings = [];
}

$csrfToken = generateCSRFToken();
$defaultSettings = cms_system_defaults();
$maintenanceBgValue = $settings['maintenance_background'] ?? $defaultSettings['maintenance_background'];
?>

<div class="page-intro">
    <h1>Site settings</h1>
    <p class="text-muted">Branding, footer, navigation, email, and integrations. Save to apply changes across the public site.</p>
</div>

<form id="settingsForm" class="settings-form">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    
    <!-- General Settings -->
    <div class="card">
        <div class="card-header">
            <h2>General Settings</h2>
        </div>
        <div class="card-body card-body--stack">
            <div class="form-group">
                <label for="site_name">Site Name</label>
                <input type="text" id="site_name" name="site_name" value="<?= sanitize($settings['site_name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="site_tagline">Site Tagline</label>
                <input type="text" id="site_tagline" name="site_tagline" value="<?= sanitize($settings['site_tagline'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="room_detail_hero_badge">Room detail hero badge</label>
                <input type="text" id="room_detail_hero_badge" name="room_detail_hero_badge" value="<?= sanitize($settings['room_detail_hero_badge'] ?? $defaultSettings['room_detail_hero_badge']) ?>" placeholder="<?= sanitize($defaultSettings['room_detail_hero_badge']) ?>">
                <p class="form-help">Small uppercase label above the room title on single-room pages.</p>
            </div>
            
            <div class="form-group">
                <label for="currency_symbol">Currency Symbol</label>
                <input type="text" id="currency_symbol" name="currency_symbol" value="<?= sanitize($settings['currency_symbol'] ?? $defaultSettings['currency_symbol']) ?>" placeholder="<?= sanitize($defaultSettings['currency_symbol']) ?>" maxlength="5">
                <p class="form-help">Currency symbol used throughout the site (e.g., $, NGN, EUR, GBP).</p>
            </div>
            
            <div class="form-group">
                <label>Logo — dark variant (header, light backgrounds)</label>
                <p class="form-help">Coffee brown / dark artwork for use on off-white (#efe8d6) header. Recommended file: <code>assets/images/logo/logo-dark.png</code> (optional fallback if file exists and this field is empty).</p>
                <div style="margin-bottom: 10px;">
                    <button type="button" class="btn btn-outline" onclick="openMediaModal('site_logo', 'logo_preview')">
                        <i class="fas fa-image"></i> Select dark logo
                    </button>
                </div>
                <input type="hidden" id="site_logo" name="site_logo" value="<?= sanitize($settings['site_logo'] ?? '') ?>">
                <div id="logo_preview" class="image-preview" style="margin-top: 10px; <?= !empty($settings['site_logo']) ? 'display: block;' : 'display: none;' ?>">
                    <?php if (!empty($settings['site_logo'])): ?>
                        <img id="logo_img" src="<?= SITE_URL . ltrim($settings['site_logo'], '/') ?>" alt="" style="max-width: 200px; max-height: 200px; object-fit: contain;">
                    <?php else: ?>
                        <img id="logo_img" src="" alt="" style="max-width: 200px; max-height: 200px; object-fit: contain;">
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Logo — light variant (footer, dark / primary background)</label>
                <p class="form-help">White or off-white (#efe8d6) artwork for brown (#411d13) footer. Do not use the dark header logo here. Optional file fallback: <code>assets/images/logo/logo-light.png</code>.</p>
                <div style="margin-bottom: 10px;">
                    <button type="button" class="btn btn-outline" onclick="openMediaModal('site_logo_light', 'logo_light_preview')">
                        <i class="fas fa-image"></i> Select light logo
                    </button>
                </div>
                <input type="hidden" id="site_logo_light" name="site_logo_light" value="<?= sanitize($settings['site_logo_light'] ?? '') ?>">
                <div id="logo_light_preview" class="image-preview" style="margin-top: 10px; <?= !empty($settings['site_logo_light']) ? 'display: block;' : 'display: none;' ?>">
                    <?php if (!empty($settings['site_logo_light'])): ?>
                        <img id="logo_light_img" src="<?= SITE_URL . ltrim($settings['site_logo_light'], '/') ?>" alt="" style="max-width: 200px; max-height: 200px; object-fit: contain; background: #411d13; padding: 8px;">
                    <?php else: ?>
                        <img id="logo_light_img" src="" alt="" style="max-width: 200px; max-height: 200px; object-fit: contain; background: #411d13; padding: 8px;">
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label>Favicon</label>
                <p class="form-help">Simplified mark, ideally 32×32 or 64×64 PNG. If empty, <code>assets/images/logo/favicon.png</code> is used when present.</p>
                <div style="margin-bottom: 10px;">
                    <button type="button" class="btn btn-outline" onclick="openMediaModal('site_favicon', 'favicon_preview')">
                        <i class="fas fa-image"></i> Select Favicon
                    </button>
                </div>
                <input type="hidden" id="site_favicon" name="site_favicon" value="<?= sanitize($settings['site_favicon'] ?? '') ?>">
                <div id="favicon_preview" class="image-preview" style="margin-top: 10px; <?= !empty($settings['site_favicon']) ? 'display: block;' : 'display: none;' ?>">
                    <?php if (!empty($settings['site_favicon'])): ?>
                        <img id="favicon_img" src="<?= SITE_URL . ltrim($settings['site_favicon'], '/') ?>" style="max-width: 64px; max-height: 64px;">
                    <?php else: ?>
                        <img id="favicon_img" src="" style="max-width: 64px; max-height: 64px;">
                    <?php endif; ?>
                </div>
                <p class="form-help">Select an image from the media library or upload a new one</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Maintenance Mode</h2>
        </div>
        <div class="card-body card-body--stack">
            <div class="form-group">
                <input type="hidden" name="maintenance_mode" value="0">
                <label style="display:flex;align-items:center;gap:10px;margin-bottom:0;">
                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" <?= ($settings['maintenance_mode'] ?? $defaultSettings['maintenance_mode']) === '1' ? 'checked' : '' ?>>
                    <span>Enable maintenance mode</span>
                </label>
                <p class="form-help">When enabled, all public pages redirect to the maintenance screen. The admin area remains accessible.</p>
            </div>

            <div class="form-group">
                <label for="maintenance_title">Maintenance title</label>
                <input type="text" id="maintenance_title" name="maintenance_title" value="<?= sanitize($settings['maintenance_title'] ?? $defaultSettings['maintenance_title']) ?>" placeholder="<?= sanitize($defaultSettings['maintenance_title']) ?>">
            </div>

            <div class="form-group">
                <label for="maintenance_message">Maintenance message</label>
                <textarea id="maintenance_message" name="maintenance_message" rows="4"><?= htmlspecialchars($settings['maintenance_message'] ?? $defaultSettings['maintenance_message'], ENT_QUOTES, 'UTF-8') ?></textarea>
                <p class="form-help">This text is shown on the public maintenance page while the site is unavailable.</p>
            </div>

            <div class="form-group">
                <label>Maintenance background image</label>
                <div style="margin-bottom: 10px;">
                    <button type="button" class="btn btn-outline" onclick="openMediaModal('maintenance_background', 'maintenance_background_preview')">
                        <i class="fas fa-image"></i> Select background image
                    </button>
                </div>
                <input type="hidden" id="maintenance_background" name="maintenance_background" value="<?= sanitize($maintenanceBgValue) ?>">
                <div id="maintenance_background_preview" class="image-preview" style="margin-top: 10px; <?= !empty($maintenanceBgValue) ? 'display: block;' : 'display: none;' ?>">
                    <img id="maintenance_background_img" src="<?= SITE_URL . ltrim($maintenanceBgValue, '/') ?>" alt="" style="max-width: 260px; max-height: 160px; object-fit: cover;">
                </div>
                <p class="form-help">Optional full-screen image shown behind the maintenance message.</p>
            </div>
        </div>
    </div>

    <!-- Header CTA (desktop nav button) -->
    <div class="card">
        <div class="card-header">
            <h2>Header navigation</h2>
        </div>
        <div class="card-body card-body--stack">
            <div class="form-row">
                <div class="form-group">
                    <label for="nav_suites_label">Rooms link label</label>
                    <input type="text" id="nav_suites_label" name="nav_suites_label" value="<?= sanitize($settings['nav_suites_label'] ?? $defaultSettings['nav_suites_label']) ?>" placeholder="<?= sanitize($defaultSettings['nav_suites_label']) ?>">
                </div>
                <div class="form-group">
                    <label for="nav_suites_href">Rooms link URL</label>
                    <input type="text" id="nav_suites_href" name="nav_suites_href" value="<?= sanitize($settings['nav_suites_href'] ?? $defaultSettings['nav_suites_href']) ?>" placeholder="<?= sanitize($defaultSettings['nav_suites_href']) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="nav_dining_label">Dining link label</label>
                    <input type="text" id="nav_dining_label" name="nav_dining_label" value="<?= sanitize($settings['nav_dining_label'] ?? $defaultSettings['nav_dining_label']) ?>" placeholder="<?= sanitize($defaultSettings['nav_dining_label']) ?>">
                </div>
                <div class="form-group">
                    <label for="nav_dining_href">Dining link URL</label>
                    <input type="text" id="nav_dining_href" name="nav_dining_href" value="<?= sanitize($settings['nav_dining_href'] ?? $defaultSettings['nav_dining_href']) ?>" placeholder="<?= sanitize($defaultSettings['nav_dining_href']) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="nav_experience_label">Amenities link label</label>
                    <input type="text" id="nav_experience_label" name="nav_experience_label" value="<?= sanitize($settings['nav_experience_label'] ?? $defaultSettings['nav_experience_label']) ?>" placeholder="<?= sanitize($defaultSettings['nav_experience_label']) ?>">
                </div>
                <div class="form-group">
                    <label for="nav_experience_href">Amenities link URL</label>
                    <input type="text" id="nav_experience_href" name="nav_experience_href" value="<?= sanitize($settings['nav_experience_href'] ?? $defaultSettings['nav_experience_href']) ?>" placeholder="<?= sanitize($defaultSettings['nav_experience_href']) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="nav_events_label">Gallery link label</label>
                    <input type="text" id="nav_events_label" name="nav_events_label" value="<?= sanitize($settings['nav_events_label'] ?? $defaultSettings['nav_events_label']) ?>" placeholder="<?= sanitize($defaultSettings['nav_events_label']) ?>">
                </div>
                <div class="form-group">
                    <label for="nav_events_href">Gallery link URL</label>
                    <input type="text" id="nav_events_href" name="nav_events_href" value="<?= sanitize($settings['nav_events_href'] ?? $defaultSettings['nav_events_href']) ?>" placeholder="<?= sanitize($defaultSettings['nav_events_href']) ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Header — primary button</h2>
        </div>
        <div class="card-body card-body--stack">
            <div class="form-group">
                <label for="nav_cta_label">Button label</label>
                <input type="text" id="nav_cta_label" name="nav_cta_label" value="<?= sanitize($settings['nav_cta_label'] ?? $defaultSettings['nav_cta_label']) ?>" placeholder="<?= sanitize($defaultSettings['nav_cta_label']) ?>">
                <p class="form-help">Shown on the right side of the desktop header (e.g. Check Availability).</p>
            </div>
            <div class="form-group">
                <label for="nav_cta_href">Button URL</label>
                <input type="text" id="nav_cta_href" name="nav_cta_href" value="<?= sanitize($settings['nav_cta_href'] ?? $defaultSettings['nav_cta_href']) ?>" placeholder="<?= sanitize($defaultSettings['nav_cta_href']) ?>">
                <p class="form-help">Internal path (e.g. <code>/rooms</code>) or full booking engine URL. This replaces the old fixed “contact” link for that button.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Theme</h2>
        </div>
        <div class="card-body card-body--stack">
            <div class="form-row">
                <div class="form-group">
                    <label for="theme_primary_color">Primary color</label>
                    <input type="text" id="theme_primary_color" name="theme_primary_color" value="<?= sanitize($settings['theme_primary_color'] ?? $defaultSettings['theme_primary_color']) ?>" placeholder="#411d13">
                </div>
                <div class="form-group">
                    <label for="theme_primary_light_color">Primary light color</label>
                    <input type="text" id="theme_primary_light_color" name="theme_primary_light_color" value="<?= sanitize($settings['theme_primary_light_color'] ?? $defaultSettings['theme_primary_light_color']) ?>" placeholder="#5a2a1f">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="theme_background_light_color">Light background</label>
                    <input type="text" id="theme_background_light_color" name="theme_background_light_color" value="<?= sanitize($settings['theme_background_light_color'] ?? $defaultSettings['theme_background_light_color']) ?>" placeholder="#efe8d6">
                </div>
                <div class="form-group">
                    <label for="theme_background_dark_color">Dark background</label>
                    <input type="text" id="theme_background_dark_color" name="theme_background_dark_color" value="<?= sanitize($settings['theme_background_dark_color'] ?? $defaultSettings['theme_background_dark_color']) ?>" placeholder="#1a1210">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="theme_champagne_color">Accent light color</label>
                    <input type="text" id="theme_champagne_color" name="theme_champagne_color" value="<?= sanitize($settings['theme_champagne_color'] ?? $defaultSettings['theme_champagne_color']) ?>" placeholder="#f5ede0">
                </div>
                <div class="form-group">
                    <label for="theme_sand_darker_color">Muted surface accent</label>
                    <input type="text" id="theme_sand_darker_color" name="theme_sand_darker_color" value="<?= sanitize($settings['theme_sand_darker_color'] ?? $defaultSettings['theme_sand_darker_color']) ?>" placeholder="#e3dcc8">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="theme_text_main_color">Main text color</label>
                    <input type="text" id="theme_text_main_color" name="theme_text_main_color" value="<?= sanitize($settings['theme_text_main_color'] ?? $defaultSettings['theme_text_main_color']) ?>" placeholder="#363636">
                </div>
                <div class="form-group">
                    <label for="theme_text_muted_color">Muted text color</label>
                    <input type="text" id="theme_text_muted_color" name="theme_text_muted_color" value="<?= sanitize($settings['theme_text_muted_color'] ?? $defaultSettings['theme_text_muted_color']) ?>" placeholder="#5c5c5c">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="theme_surface_light_color">Light surface</label>
                    <input type="text" id="theme_surface_light_color" name="theme_surface_light_color" value="<?= sanitize($settings['theme_surface_light_color'] ?? $defaultSettings['theme_surface_light_color']) ?>" placeholder="#ffffff">
                </div>
                <div class="form-group">
                    <label for="theme_surface_dark_color">Dark surface</label>
                    <input type="text" id="theme_surface_dark_color" name="theme_surface_dark_color" value="<?= sanitize($settings['theme_surface_dark_color'] ?? $defaultSettings['theme_surface_dark_color']) ?>" placeholder="#2a1f1c">
                </div>
            </div>
            <div class="form-group">
                <label for="theme_surface_ink_color">Ink surface</label>
                <input type="text" id="theme_surface_ink_color" name="theme_surface_ink_color" value="<?= sanitize($settings['theme_surface_ink_color'] ?? $defaultSettings['theme_surface_ink_color']) ?>" placeholder="#2a1814">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="theme_display_font">Display font</label>
                    <input type="text" id="theme_display_font" name="theme_display_font" value="<?= sanitize($settings['theme_display_font'] ?? $defaultSettings['theme_display_font']) ?>" placeholder="<?= sanitize($defaultSettings['theme_display_font']) ?>">
                </div>
                <div class="form-group">
                    <label for="theme_serif_font">Serif font</label>
                    <input type="text" id="theme_serif_font" name="theme_serif_font" value="<?= sanitize($settings['theme_serif_font'] ?? $defaultSettings['theme_serif_font']) ?>" placeholder="<?= sanitize($defaultSettings['theme_serif_font']) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="theme_body_font">Body font</label>
                    <input type="text" id="theme_body_font" name="theme_body_font" value="<?= sanitize($settings['theme_body_font'] ?? $defaultSettings['theme_body_font']) ?>" placeholder="<?= sanitize($defaultSettings['theme_body_font']) ?>">
                </div>
                <div class="form-group">
                    <label for="booking_wrapper_id">Booking wrapper id</label>
                    <input type="text" id="booking_wrapper_id" name="booking_wrapper_id" value="<?= sanitize($settings['booking_wrapper_id'] ?? $defaultSettings['booking_wrapper_id']) ?>" placeholder="<?= sanitize($defaultSettings['booking_wrapper_id']) ?>">
                    <p class="form-help">Used by homepage booking bridge styling when your booking provider embeds its own wrapper element.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Settings -->
    <div class="card">
        <div class="card-header">
            <h2>Footer Settings</h2>
        </div>
        <div class="card-body card-body--stack">
            <div class="form-group">
                <label for="footer_copyright">Copyright Text</label>
                <input type="text" id="footer_copyright" name="footer_copyright" value="<?= sanitize($settings['footer_copyright'] ?? '') ?>">
                <p class="form-help">This will be shown as: Copyright © [Year] [Your Text]. All rights reserved.</p>
            </div>
            
            <div class="form-group">
                <label for="footer_address">Address</label>
                <textarea id="footer_address" name="footer_address" rows="2"><?= sanitize($settings['footer_address'] ?? $defaultSettings['footer_address']) ?></textarea>
                <p class="form-help">Physical address displayed in the footer contact section</p>
            </div>
            
            <div class="form-group">
                <label for="footer_phone">Phone Number(s)</label>
                <input type="text" id="footer_phone" name="footer_phone" value="<?= sanitize($settings['footer_phone'] ?? $defaultSettings['footer_phone']) ?>">
                <p class="form-help">Enter phone number(s) to display in the footer (e.g., +234 813 480 7718 | +234 907 676 0923)</p>
            </div>
            
            <div class="form-group">
                <label for="footer_email">Email Address</label>
                <input type="email" id="footer_email" name="footer_email" value="<?= sanitize($settings['footer_email'] ?? $defaultSettings['footer_email']) ?>">
                <p class="form-help">Email address displayed in the footer contact section</p>
            </div>
            
            <div class="form-group">
                <label for="contact_email">General inquiries email</label>
                <input type="email" id="contact_email" name="contact_email" value="<?= sanitize($settings['contact_email'] ?? $settings['footer_email'] ?? $defaultSettings['contact_email']) ?>">
                <p class="form-help">Default email used as a fallback in contact-related content and outgoing mail branding.</p>
            </div>
            
            <div class="form-group">
                <label for="whatsapp_number">WhatsApp Number</label>
                <input type="text" id="whatsapp_number" name="whatsapp_number" value="<?= sanitize($settings['whatsapp_number'] ?? $defaultSettings['whatsapp_number']) ?>" placeholder="+2341234567890">
                <p class="form-help">Raw WhatsApp number with country code. If no direct link is set below, room booking falls back to this number automatically.</p>
            </div>

            <div class="form-group">
                <label for="whatsapp_link">WhatsApp Link</label>
                <input type="text" id="whatsapp_link" name="whatsapp_link" value="<?= sanitize($settings['whatsapp_link'] ?? $defaultSettings['whatsapp_link']) ?>" placeholder="https://wa.me/15550000000?text=Hello">
                <p class="form-help">Optional full WhatsApp URL for booking/contact buttons. Leave blank to build one from the number.</p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="footer_privacy_href">Privacy link URL</label>
                    <input type="text" id="footer_privacy_href" name="footer_privacy_href" value="<?= sanitize($settings['footer_privacy_href'] ?? $defaultSettings['footer_privacy_href']) ?>" placeholder="<?= sanitize($defaultSettings['footer_privacy_href']) ?>">
                </div>
                <div class="form-group">
                    <label for="footer_terms_href">Terms link URL</label>
                    <input type="text" id="footer_terms_href" name="footer_terms_href" value="<?= sanitize($settings['footer_terms_href'] ?? $defaultSettings['footer_terms_href']) ?>" placeholder="<?= sanitize($defaultSettings['footer_terms_href']) ?>">
                </div>
            </div>
        </div>
    </div>
    
    <!-- SMTP Settings -->
    <div class="card">
        <div class="card-header">
            <h2>SMTP Email Settings</h2>
        </div>
        <div class="card-body card-body--stack">
            <div class="alert-smtp-note">
                <strong>Note:</strong> These values are stored for outgoing email/integration use, but the current public contact page does not submit a form yet.
            </div>
            
            <div class="form-group">
                <label for="smtp_host">SMTP Host</label>
                <input type="text" id="smtp_host" name="smtp_host" value="<?= sanitize($settings['smtp_host'] ?? '') ?>" placeholder="smtp.gmail.com">
                <p class="form-help">SMTP server hostname (e.g., smtp.gmail.com, smtp.mailtrap.io)</p>
            </div>
            
            <div class="form-group">
                <label for="smtp_port">SMTP Port</label>
                <input type="number" id="smtp_port" name="smtp_port" value="<?= sanitize($settings['smtp_port'] ?? '587') ?>" placeholder="587">
                <p class="form-help">SMTP port (usually 587 for TLS, 465 for SSL, 25 for unencrypted)</p>
            </div>
            
            <div class="form-group">
                <label for="smtp_username">SMTP Username</label>
                <input type="text" id="smtp_username" name="smtp_username" value="<?= sanitize($settings['smtp_username'] ?? '') ?>" placeholder="your-email@gmail.com">
                <p class="form-help">SMTP authentication username (usually your email address)</p>
            </div>
            
            <div class="form-group">
                <label for="smtp_password">SMTP Password</label>
                <input type="password" id="smtp_password" name="smtp_secret" value="<?= sanitize($settings['smtp_password'] ?? '') ?>" placeholder="Your SMTP password" autocomplete="current-password">
                <p class="form-help">SMTP authentication password (for Gmail, use an App Password)</p>
            </div>
            
            <div class="form-group">
                <label for="smtp_encryption">SMTP Encryption</label>
                <select id="smtp_encryption" name="smtp_encryption" class="form-control">
                    <option value="tls" <?= ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                    <option value="ssl" <?= ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                    <option value="" <?= empty($settings['smtp_encryption']) ? 'selected' : '' ?>>None</option>
                </select>
                <p class="form-help">Encryption method (TLS for port 587, SSL for port 465)</p>
            </div>
            
            <div class="form-group">
                <label for="smtp_from_email">From Email</label>
                <input type="email" id="smtp_from_email" name="smtp_from_email" value="<?= sanitize($settings['smtp_from_email'] ?? $settings['contact_email'] ?? $defaultSettings['contact_email']) ?>" placeholder="noreply@yourdomain.com">
                <p class="form-help">Email address that will appear as the sender</p>
            </div>
            
            <div class="form-group">
                <label for="smtp_from_name">From Name</label>
                <input type="text" id="smtp_from_name" name="smtp_from_name" value="<?= sanitize($settings['smtp_from_name'] ?? ($settings['site_name'] ?? cms_default_setting('smtp_from_name'))) ?>" placeholder="Site / hotel name">
                <p class="form-help">Name that will appear as the sender</p>
            </div>
        </div>
    </div>
    
    <!-- Social Media Links -->
    <div class="card">
        <div class="card-header">
            <h2>Social Media Links</h2>
        </div>
        <div class="card-body card-body--stack">
            <div id="socialMediaList">
                <!-- Social media items will be rendered here -->
            </div>
            <button type="button" class="btn btn-outline" onclick="addSocialMedia()" style="margin-top: 12px;">
                <i class="fas fa-plus"></i> Add Social Media
            </button>
            <input type="hidden" id="social_media_json" name="social_media_json" value="<?= htmlspecialchars($settings['social_media_json'] ?? '[]', ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <!-- Live Chat -->
    <div class="card">
        <div class="card-header">
            <h2>Live chat</h2>
        </div>
        <div class="card-body card-body--stack">
            <div class="form-group">
                <label for="smartsupp_key">Smartsupp key</label>
                <input type="text" id="smartsupp_key" name="smartsupp_key" value="<?= sanitize($settings['smartsupp_key'] ?? '') ?>" placeholder="Your Smartsupp key">
                <p class="form-help">If set, Smartsupp will load on every public page. You can change this later when your client account is ready.</p>
            </div>
        </div>
    </div>
    
    <!-- Google Maps Settings -->
    <div class="card">
        <div class="card-header">
            <h2>Google Maps</h2>
        </div>
        <div class="card-body card-body--stack">
            <div class="form-group">
                <label for="google_maps_api_key">Google Maps API Key</label>
                <input type="text" id="google_maps_api_key" name="google_maps_api_key" value="<?= sanitize($settings['google_maps_api_key'] ?? '') ?>" placeholder="AIzaSy...">
                <p class="form-help">Enter your Google Maps API key to enable interactive maps on the contact page. Get your API key from <a href="https://console.cloud.google.com/" target="_blank" rel="noopener">Google Cloud Console</a>. The contact page will show a static placeholder image until an API key is configured.</p>
            </div>
        </div>
    </div>
    
    <!-- Custom Scripts -->
    <div class="card">
        <div class="card-header">
            <h2>Custom Scripts</h2>
        </div>
        <div class="card-body card-body--stack">
            <div class="form-group">
                <label for="header_scripts">Header Scripts</label>
                <textarea id="header_scripts" name="header_scripts" rows="6" class="mono"><?= htmlspecialchars($settings['header_scripts'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                <p class="form-help">Scripts will be added in the &lt;head&gt; section (e.g., Google Analytics, Meta Pixel, etc.)</p>
            </div>
            
            <div class="form-group">
                <label for="body_scripts">Body Scripts</label>
                <textarea id="body_scripts" name="body_scripts" rows="6" class="mono"><?= htmlspecialchars($settings['body_scripts'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                <p class="form-help">Scripts will be added right after the opening &lt;body&gt; tag (e.g., chat widgets, tracking scripts)</p>
            </div>
            
            <div class="form-group">
                <label for="footer_scripts">Footer Scripts</label>
                <textarea id="footer_scripts" name="footer_scripts" rows="6" class="mono"><?= htmlspecialchars($settings['footer_scripts'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                <p class="form-help">Scripts will be added right before the closing &lt;/body&gt; tag (e.g., analytics, custom JavaScript)</p>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save settings</button>
        <span class="text-muted">All sections above are saved together.</span>
    </div>
</form>

<script>
// Media modal integration - the openMediaModal function is provided by media-library.js

// Social Media Management
let socialMediaList = [];

const SOCIAL_PLATFORMS = [
    { value: 'facebook', label: 'Facebook' },
    { value: 'instagram', label: 'Instagram' },
    { value: 'tiktok', label: 'TikTok' },
    { value: 'x', label: 'X (formerly Twitter)' }
];

function inferPlatformFromUrl(url) {
    const u = String(url || '').toLowerCase();
    if (u.includes('instagram.com')) return 'instagram';
    if (u.includes('tiktok.com')) return 'tiktok';
    if (u.includes('twitter.com') || u.includes('x.com')) return 'x';
    if (u.includes('facebook.com') || u.includes('fb.com')) return 'facebook';
    return 'facebook';
}

function normalizePlatform(p) {
    const v = String(p || '').toLowerCase().trim();
    if (v === 'twitter' || v === 'x-twitter') return 'x';
    if (v === 'ig') return 'instagram';
    return v || 'facebook';
}

// Load social media from hidden input
function loadSocialMedia() {
    const jsonInput = document.getElementById('social_media_json');
    try {
        socialMediaList = JSON.parse(jsonInput.value || '[]');
    } catch (e) {
        console.error('Error parsing social media JSON:', e);
        socialMediaList = [];
    }

    // Backwards compatibility: older entries may be { icon, url }.
    // Convert to { platform, url } by inferring from URL.
    if (!Array.isArray(socialMediaList)) socialMediaList = [];
    socialMediaList = socialMediaList.map((item) => {
        const url = (item && typeof item === 'object') ? (item.url || '') : '';
        const platform = (item && typeof item === 'object') ? (item.platform || '') : '';
        return {
            platform: normalizePlatform(platform || inferPlatformFromUrl(url)),
            url: String(url || '')
        };
    });
    renderSocialMedia();
}

// Render social media list
function renderSocialMedia() {
    const container = document.getElementById('socialMediaList');
    container.innerHTML = '';
    
    if (socialMediaList.length === 0) {
        container.innerHTML = '<p class="form-help" style="margin-bottom: 15px;">No social media links added yet. Click "Add Social Media" to add one.</p>';
    } else {
        socialMediaList.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'social-item';

            const optionsHtml = SOCIAL_PLATFORMS.map((p) => {
                const selected = normalizePlatform(item.platform) === p.value ? 'selected' : '';
                return `<option value="${p.value}" ${selected}>${p.label}</option>`;
            }).join('');

            div.innerHTML = `
                <div class="social-item__head">
                    <strong>Social link #${index + 1}</strong>
                    <button type="button" class="btn btn-sm btn-outline" onclick="removeSocialMedia(${index})">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                <div class="form-group" style="margin-bottom: 12px;">
                    <label>Platform</label>
                    <select onchange="updateSocialMedia(${index}, 'platform', this.value)">
                        ${optionsHtml}
                    </select>
                    <p class="form-help">Select the platform — the correct icon will be shown in the footer automatically.</p>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label>URL</label>
                    <input type="url" value="${(item.url || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;')}" 
                           onchange="updateSocialMedia(${index}, 'url', this.value)" 
                           placeholder="https://...">
                    <p class="form-help">Full profile or page URL</p>
                </div>
            `;
            container.appendChild(div);
        });
    }
    
    // Update hidden input
    document.getElementById('social_media_json').value = JSON.stringify(socialMediaList);
}

// Add new social media
function addSocialMedia() {
    socialMediaList.push({
        platform: 'facebook',
        url: ''
    });
    renderSocialMedia();
}

// Remove social media
function removeSocialMedia(index) {
    if (confirm('Are you sure you want to remove this social media link?')) {
        socialMediaList.splice(index, 1);
        renderSocialMedia();
    }
}

// Update social media item
function updateSocialMedia(index, field, value) {
    if (socialMediaList[index]) {
        if (field === 'platform') {
            socialMediaList[index][field] = normalizePlatform(value);
        } else {
            socialMediaList[index][field] = value;
        }
        document.getElementById('social_media_json').value = JSON.stringify(socialMediaList);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadSocialMedia();
});

// Form submission
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Ensure social media JSON is up to date & normalized
    const dedup = new Map();
    (socialMediaList || []).forEach((item) => {
        const platform = normalizePlatform(item?.platform);
        const url = String(item?.url || '').trim();
        if (!url) return;
        dedup.set(platform, { platform, url });
    });
    socialMediaList = Array.from(dedup.values());
    document.getElementById('social_media_json').value = JSON.stringify(socialMediaList);
    
    const formData = new FormData(this);
    const metaCsrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    if (metaCsrf) {
        formData.set('csrf_token', metaCsrf);
    }
    const debugSiteName = String(formData.get('site_name') || '').slice(0, 80);
    const debugHasCsrf = !!formData.get('csrf_token');
    const debugHasSmtpSecret = formData.has('smtp_secret');
    
    const submitBtn = this.querySelector('button[type="submit"]');
    if (typeof setSaveButtonSavingState === 'function') setSaveButtonSavingState(submitBtn, true);

    // #region agent log
    fetch('http://127.0.0.1:7262/ingest/0395f273-d6cc-4a5a-a50c-e33b0e3ee23d',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'2bd4ec'},body:JSON.stringify({sessionId:'2bd4ec',runId:'initial',hypothesisId:'H5',location:'admin/pages/settings.php:666',message:'settings submit started',data:{hasCsrf:debugHasCsrf,hasSmtpSecret:debugHasSmtpSecret,siteNamePreview:debugSiteName,formKeyCount:Array.from(formData.keys()).length},timestamp:Date.now()})}).catch(()=>{});
    // #endregion

    fetch('<?= ADMIN_URL ?>api/settings.php', {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
    })
    .then(response => {
        // #region agent log
        fetch('http://127.0.0.1:7262/ingest/0395f273-d6cc-4a5a-a50c-e33b0e3ee23d',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'2bd4ec'},body:JSON.stringify({sessionId:'2bd4ec',runId:'initial',hypothesisId:'H5',location:'admin/pages/settings.php:675',message:'settings response received',data:{status:response.status,ok:response.ok,statusText:response.statusText},timestamp:Date.now()})}).catch(()=>{});
        // #endregion
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response:', text);
                throw new Error('Server returned invalid response. Please check server logs.');
            }
        });
    })
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Operation failed', 'error');
        }
    })
    .catch(error => {
        console.error('Settings save error:', error);
        showToast('Error: ' + error.message, 'error');
    })
    .finally(() => {
        if (typeof setSaveButtonSavingState === 'function') setSaveButtonSavingState(submitBtn, false);
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

