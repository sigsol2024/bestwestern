<?php
/**
 * Shared frontend <head> assets.
 * Include inside <head> on every public page.
 */

static $siteHeadLoaded = false;
if ($siteHeadLoaded) {
    return;
}
$siteHeadLoaded = true;
$bookingWrapperId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) getSiteSetting('booking_wrapper_id', cms_default_setting('booking_wrapper_id')));
if ($bookingWrapperId === '') {
    $bookingWrapperId = cms_default_setting('booking_wrapper_id');
}
$bookingWrapperSelector = '#' . $bookingWrapperId;
?>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&amp;family=Inter:wght@300;400;600;700&amp;display=swap" rel="stylesheet"/>
<?php
if (function_exists('getSiteSetting') && function_exists('site_media_url') && function_exists('site_root')) {
    $fav = trim((string)getSiteSetting('site_favicon', cms_default_setting('site_favicon', '')));
    if ($fav === '') {
        $favPath = site_root() . '/assets/images/logo/favicon.png';
        if (is_file($favPath)) {
            $fav = 'assets/images/logo/favicon.png';
        }
    }
    if ($fav !== '') {
        $favUrl = site_media_url($fav);
        echo '<link rel="icon" href="' . htmlspecialchars($favUrl, ENT_QUOTES, 'UTF-8') . '" sizes="32x32">' . "\n";
        echo '<link rel="icon" href="' . htmlspecialchars($favUrl, ENT_QUOTES, 'UTF-8') . '" sizes="64x64" type="image/png">' . "\n";
        echo '<link rel="apple-touch-icon" href="' . htmlspecialchars($favUrl, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }
}
?>
<?php
// Layout tokens used across Tailwind classes map to Theme settings (see Admin → Settings → Theme).
$twPrimary = site_theme_color('theme_primary_color', cms_default_setting('theme_primary_color'));
$twPrimaryLight = site_theme_color('theme_primary_light_color', cms_default_setting('theme_primary_light_color'));
$twBgLight = site_theme_color('theme_background_light_color', cms_default_setting('theme_background_light_color'));
$twBgDark = site_theme_color('theme_background_dark_color', cms_default_setting('theme_background_dark_color'));
$twChampagne = site_theme_color('theme_champagne_color', cms_default_setting('theme_champagne_color'));
$twTextMain = site_theme_color('theme_text_main_color', cms_default_setting('theme_text_main_color'));
$twTextMuted = site_theme_color('theme_text_muted_color', cms_default_setting('theme_text_muted_color'));
$twSurfaceLight = site_theme_color('theme_surface_light_color', cms_default_setting('theme_surface_light_color'));
$twSurfaceDark = site_theme_color('theme_surface_dark_color', cms_default_setting('theme_surface_dark_color'));
$twSurfaceInk = site_theme_color('theme_surface_ink_color', cms_default_setting('theme_surface_ink_color'));
$twSandDarker = site_theme_color('theme_sand_darker_color', cms_default_setting('theme_sand_darker_color'));
$twEncPrimarySvg = rawurlencode(site_theme_color('theme_primary_color', '#411d13'));
$twEncTextMainSvg = rawurlencode(site_theme_color('theme_text_main_color', '#363636'));
?>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "primary": "<?= htmlspecialchars($twPrimary, ENT_QUOTES, 'UTF-8') ?>",
          "primary-light": "<?= htmlspecialchars($twPrimaryLight, ENT_QUOTES, 'UTF-8') ?>",
          "background-light": "<?= htmlspecialchars($twBgLight, ENT_QUOTES, 'UTF-8') ?>",
          "background-dark": "<?= htmlspecialchars($twBgDark, ENT_QUOTES, 'UTF-8') ?>",
          "champagne": "<?= htmlspecialchars($twChampagne, ENT_QUOTES, 'UTF-8') ?>",
          "text-main": "<?= htmlspecialchars($twTextMain, ENT_QUOTES, 'UTF-8') ?>",
          "text-muted": "<?= htmlspecialchars($twTextMuted, ENT_QUOTES, 'UTF-8') ?>",
          "surface-light": "<?= htmlspecialchars($twSurfaceLight, ENT_QUOTES, 'UTF-8') ?>",
          "surface-dark": "<?= htmlspecialchars($twSurfaceDark, ENT_QUOTES, 'UTF-8') ?>",
          "surface-ink": "<?= htmlspecialchars($twSurfaceInk, ENT_QUOTES, 'UTF-8') ?>",
          "sand-darker": "<?= htmlspecialchars($twSandDarker, ENT_QUOTES, 'UTF-8') ?>",
          "brand-gold": "<?= htmlspecialchars($twPrimary, ENT_QUOTES, 'UTF-8') ?>",
          "brand-red": "#E31837",
          "surface": "<?= htmlspecialchars($twBgLight, ENT_QUOTES, 'UTF-8') ?>",
          "on-surface": "<?= htmlspecialchars($twTextMain, ENT_QUOTES, 'UTF-8') ?>",
          "on-surface-variant": "<?= htmlspecialchars($twTextMuted, ENT_QUOTES, 'UTF-8') ?>",
          "surface-container-low": "<?= htmlspecialchars($twChampagne, ENT_QUOTES, 'UTF-8') ?>",
          "surface-container-highest": "<?= htmlspecialchars($twSandDarker, ENT_QUOTES, 'UTF-8') ?>",
          "outline-variant": "<?= htmlspecialchars($twSandDarker, ENT_QUOTES, 'UTF-8') ?>",
          "outline": "<?= htmlspecialchars($twTextMuted, ENT_QUOTES, 'UTF-8') ?>",
          "brand-ink": "<?= htmlspecialchars($twSurfaceInk, ENT_QUOTES, 'UTF-8') ?>",
          "secondary-container": "<?= htmlspecialchars($twChampagne, ENT_QUOTES, 'UTF-8') ?>",
          "on-secondary-container": "<?= htmlspecialchars($twPrimary, ENT_QUOTES, 'UTF-8') ?>",
          "on-secondary-fixed": "<?= htmlspecialchars($twSurfaceInk, ENT_QUOTES, 'UTF-8') ?>",
          "surface-container": "<?= htmlspecialchars($twSandDarker, ENT_QUOTES, 'UTF-8') ?>",
          "on-primary": "#ffffff",
          "inverse-primary": "<?= htmlspecialchars($twPrimaryLight, ENT_QUOTES, 'UTF-8') ?>",
          "inverse-on-surface": "<?= htmlspecialchars($twChampagne, ENT_QUOTES, 'UTF-8') ?>",
          "scrim": "#000000",
        },
        fontFamily: {
          "display": ["<?= htmlspecialchars((string) getSiteSetting('theme_display_font', cms_default_setting('theme_display_font')), ENT_QUOTES, 'UTF-8') ?>", "sans-serif"],
          "serif": ["<?= htmlspecialchars((string) getSiteSetting('theme_serif_font', cms_default_setting('theme_serif_font')), ENT_QUOTES, 'UTF-8') ?>", "serif"],
          "body": ["<?= htmlspecialchars((string) getSiteSetting('theme_body_font', cms_default_setting('theme_body_font')), ENT_QUOTES, 'UTF-8') ?>", "sans-serif"],
          "headline": ["<?= htmlspecialchars((string) getSiteSetting('theme_serif_font', cms_default_setting('theme_serif_font')), ENT_QUOTES, 'UTF-8') ?>", "serif"],
          "label": ["Inter", "ui-sans-serif", "system-ui", "sans-serif"],
        },
        borderRadius: {
          "xs": "0.25rem",
          "sm": "0.375rem",
          "DEFAULT": "0.25rem",
          "md": "0.5rem",
          "lg": "0.75rem",
          "xl": "1rem",
          "2xl": "1.25rem",
          "3xl": "1.5rem",
          "full": "9999px",
        },
        boxShadow: {
          "elevation": "0 20px 40px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01)",
        },
        backgroundImage: {
          "texture-pattern": "url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='<?= $twEncPrimarySvg ?>' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")",
          "fabric-pattern": "url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='<?= $twEncTextMainSvg ?>' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")",
          "architectural-pattern": "url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='<?= $twEncTextMainSvg ?>' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")",
          "subtle-pattern": "url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='<?= $twEncPrimarySvg ?>' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")",
        }
      },
    },
  }
</script>
<style>
  :root {
    --site-surface-container-low: <?= htmlspecialchars($twChampagne, ENT_QUOTES, 'UTF-8') ?>;
  }
  html { scroll-behavior: smooth; }
  /* Brand: do not distort logos or add effects. */
  .site-brand-logo img {
    box-shadow: none !important;
    filter: none !important;
  }
  .no-scrollbar::-webkit-scrollbar { display: none; }
  .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  .text-cinematic { text-shadow: 0 2px 10px rgba(0,0,0,0.3); }
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  }
  .rooms-fade-overlay {
    background: linear-gradient(to right, transparent, var(--site-surface-container-low));
  }
  .inter,
  .font-inter {
    font-family: Inter, ui-sans-serif, system-ui, "Segoe UI", Roboto, Arial, sans-serif;
  }
  .noto-serif,
  .font-noto-serif {
    font-family: "Noto Serif", ui-serif, Georgia, "Times New Roman", serif;
  }

  /* Hero: light stroke / outline on accent words (not a box border) */
  .site-hero-accent-text {
    /* Use the site's modern display font for the accent phrase */
    font-family: <?= json_encode((string) getSiteSetting('theme_display_font', cms_default_setting('theme_display_font'))) ?>, <?= json_encode((string) getSiteSetting('theme_body_font', cms_default_setting('theme_body_font'))) ?>, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
    font-weight: 700;
    letter-spacing: -0.015em;
    -webkit-text-stroke: 1.25px rgba(255, 255, 255, 0.9);
    paint-order: stroke fill;
    text-shadow:
      0 0 1px rgba(255, 255, 255, 0.95),
      0 2px 20px rgba(0, 0, 0, 0.4);
  }

  /* Mobile nav modal (centered card, not sidebar) */
  .site-mobile-menu-overlay {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.3s ease, visibility 0.3s ease;
  }
  .site-mobile-menu-overlay.open {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
  }
  .site-mobile-menu-modal {
    opacity: 0;
    transform: scale(0.94) translateY(-0.5rem);
    transition: opacity 0.3s cubic-bezier(0.22, 1, 0.36, 1), transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
  }
  .site-mobile-menu-overlay.open .site-mobile-menu-modal {
    opacity: 1;
    transform: scale(1) translateY(0);
  }

  /* Homepage booking bridge: tames common embeds (e.g. StayEazi-style #booking-widget / #booking-form) */
  <?= $bookingWrapperSelector ?> {
    width: 100% !important;
  }
  <?= $bookingWrapperSelector ?> * {
    box-sizing: border-box;
  }
  <?= $bookingWrapperSelector ?> #booking-widget {
    margin: 0 !important;
    padding: 0 !important;
    border: 0 !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    background: transparent !important;
    max-width: none !important;
    width: 100% !important;
  }
  <?= $bookingWrapperSelector ?> #booking-form {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 8px !important;
    align-items: flex-end !important;
    justify-content: space-between !important;
    padding: 0 !important;
    margin: 0 !important;
    width: 100% !important;
  }
  <?= $bookingWrapperSelector ?> #booking-form > div {
    width: auto !important;
    min-width: 160px !important;
    flex: 1 1 160px !important;
    margin: 0 !important;
  }
  <?= $bookingWrapperSelector ?> #booking-form label {
    font-size: 11px !important;
    font-weight: 700 !important;
    letter-spacing: 0.08em !important;
    text-transform: uppercase !important;
    margin-bottom: 6px !important;
    color: #363636 !important;
  }
  <?= $bookingWrapperSelector ?> #booking-form input,
  <?= $bookingWrapperSelector ?> #booking-form select {
    width: 100% !important;
    height: 44px !important;
    padding: 10px 12px !important;
    border: 1px solid #d8d0bc !important;
    border-radius: 10px !important;
    background: #fff !important;
    color: #363636 !important;
  }
  <?= $bookingWrapperSelector ?> #booking-form button {
    width: 100% !important;
    height: 44px !important;
    margin-top: 0 !important;
    border: 0 !important;
    border-radius: 10px !important;
    background: #C8A96A !important;
    color: #261a00 !important;
    font-weight: 700 !important;
    cursor: pointer !important;
  }
  <?= $bookingWrapperSelector ?> #booking-form button:hover {
    filter: brightness(1.08) !important;
  }
  @media (max-width: 1024px) {
    <?= $bookingWrapperSelector ?> #booking-form {
      flex-direction: column !important;
      align-items: stretch !important;
    }
    <?= $bookingWrapperSelector ?> #booking-form > div {
      min-width: 100% !important;
      flex: 1 1 100% !important;
    }
  }
</style>
<?php
// Optional: site-wide injected header scripts (analytics etc)
if (function_exists('getSiteSetting')) {
    $headerScripts = getSiteSetting('header_scripts', '');
    if (!empty($headerScripts)) {
        echo "\n<!-- Custom Header Scripts -->\n";
        echo $headerScripts . "\n";
    }
}
?>

