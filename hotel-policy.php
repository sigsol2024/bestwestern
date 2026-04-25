<?php
require_once __DIR__ . '/includes/content-loader.php';

$siteName = getSiteSetting('site_name', cms_default_setting('site_name'));
$footerEmail = getSiteSetting('footer_email', cms_default_setting('footer_email'));
$footerPhone = getSiteSetting('footer_phone', cms_default_setting('footer_phone'));

$pageKey = 'hotel-policy';
$pageTitle = getPageSection($pageKey, 'page_title', 'Hotel Policy');
$hero_kicker = getPageSection($pageKey, 'hero_kicker', 'Guest Information');
$hero_title = getPageSection($pageKey, 'hero_title', 'Hotel Policy');
$hero_subtitle = getPageSection($pageKey, 'hero_subtitle', 'A simple guide to ensure a calm, seamless stay for every guest.');
$last_updated = getPageSection($pageKey, 'last_updated', 'Last updated: April 8, 2026');
$body_html = getPageSection($pageKey, 'body_html', '');

if (trim((string)$body_html) === '') {
  $body_html = <<<HTML
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

<h2>Lorem Ipsum</h2>
<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

<h2>Dolor Sit</h2>
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

<h2>Amet Elit</h2>
<p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<h2>Contact</h2>
<p>Lorem ipsum dolor sit amet. <a href="mailto:{$footerEmail}">{$footerEmail}</a> | <a href="tel:{$footerPhone}">{$footerPhone}</a></p>
HTML;
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($pageTitle) ?></title>
  <?php require_once __DIR__ . '/includes/head-header.php'; ?>
  <style>
    .notoSerif { font-family: 'Noto Serif', serif; }
    .inter { font-family: 'Inter', sans-serif; }
    body[data-page="legal"] { background-color: #fdf9f3; color: #1c1c18; }
    body[data-page="legal"] .site-header-nav--over-hero:not(.site-header-nav--scrolled) .site-header-desktop-link--inactive,
    body[data-page="legal"] .site-header-nav--over-hero:not(.site-header-nav--scrolled) .site-header-mobile-trigger { color: #1c1c18; }
    .legal-content h2 { margin-top: 28px; margin-bottom: 10px; font-family: 'Noto Serif', serif; font-size: 1.7rem; line-height: 1.3; color: #0b1f3a; }
    .legal-content p { margin-top: 10px; line-height: 1.8; color: #44474d; font-family: 'Inter', sans-serif; }
    .legal-content ul { margin-top: 10px; padding-left: 1.25rem; list-style: disc; color: #44474d; }
    .legal-content li { margin-top: 8px; line-height: 1.7; }
    .legal-content a { text-decoration: underline; color: #C8A96A; text-underline-offset: 3px; }
  </style>
</head>
<body data-page="legal" class="bg-surface text-on-surface overflow-x-hidden">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<main class="pt-32 pb-24 px-6 md:px-12">
  <section class="max-w-5xl mx-auto">
    <div class="mb-12">
      <span class="inter block text-secondary text-xs font-bold uppercase tracking-[0.25em] mb-3"><?= e($hero_kicker) ?></span>
      <h1 class="notoSerif text-4xl md:text-6xl leading-tight text-primary-container mb-4"><?= e($hero_title) ?></h1>
      <p class="inter text-on-surface-variant text-base md:text-lg font-light leading-relaxed max-w-2xl"><?= e($hero_subtitle) ?></p>
      <p class="inter text-xs text-on-surface-variant mt-4 uppercase tracking-[0.2em]"><?= e($last_updated) ?></p>
    </div>

    <div class="bg-surface-container-low rounded-xl border border-outline-variant/30 p-7 md:p-10">
      <div class="legal-content">
        <?= $body_html ?>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
