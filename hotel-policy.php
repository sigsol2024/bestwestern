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
    .legal-content h2 { margin-top: 28px; margin-bottom: 10px; font-weight: 600; letter-spacing: -0.01em; }
    .legal-content p { margin-top: 10px; line-height: 1.8; }
    .legal-content ul { margin-top: 10px; padding-left: 1.25rem; list-style: disc; }
    .legal-content li { margin-top: 8px; line-height: 1.7; }
    .legal-content a { text-decoration: underline; }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display antialiased text-text-main dark:text-white transition-colors duration-300 overflow-x-hidden">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="relative flex min-h-screen w-full flex-col">
  <main class="flex-grow w-full max-w-[980px] mx-auto px-6 lg:px-12 py-12 lg:py-20">
    <div class="mb-10">
      <span class="block text-primary text-xs font-bold uppercase tracking-[0.25em] mb-3"><?= e($hero_kicker) ?></span>
      <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-medium tracking-tight mb-4"><?= e($hero_title) ?></h1>
      <p class="text-text-muted text-base md:text-lg font-body font-light leading-relaxed max-w-2xl"><?= e($hero_subtitle) ?></p>
      <p class="text-xs text-text-muted mt-4"><?= e($last_updated) ?></p>
    </div>

    <div class="bg-white rounded-2xl border border-black/[0.06] shadow-elevation p-7 md:p-10">
      <div class="legal-content text-text-main">
        <?= $body_html ?>
      </div>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
