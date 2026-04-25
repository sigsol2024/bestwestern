<?php
require_once __DIR__ . '/includes/content-loader.php';

$siteName = getSiteSetting('site_name', cms_default_setting('site_name'));
$footerEmail = getSiteSetting('footer_email', cms_default_setting('footer_email'));

$pageKey = 'terms-and-conditions';
$pageTitle = getPageSection($pageKey, 'page_title', 'Terms & Conditions');
$hero_kicker = getPageSection($pageKey, 'hero_kicker', 'Legal');
$hero_title = getPageSection($pageKey, 'hero_title', 'Terms & Conditions');
$hero_subtitle = getPageSection($pageKey, 'hero_subtitle', 'The terms that govern use of our website and services.');
$last_updated = getPageSection($pageKey, 'last_updated', 'Last updated: April 8, 2026');
$body_html = getPageSection($pageKey, 'body_html', '');

if (trim((string)$body_html) === '') {
  $body_html = <<<HTML
<p>These Terms &amp; Conditions ("Terms") apply to your use of the <strong>{$siteName}</strong> website and related concierge services. By accessing or using our website, you agree to these Terms.</p>

<h2>Use of the website</h2>
<ul>
  <li>You may use this website for lawful purposes and in accordance with these Terms.</li>
  <li>You must not attempt to disrupt, damage, or gain unauthorized access to the website or its systems.</li>
  <li>We may update, suspend, or discontinue any part of the site without notice.</li>
</ul>

<h2>Reservations and services</h2>
<p>Reservation availability, rates, inclusions, and policies may change. Specific booking terms (including cancellation, deposits, and no-show policies) may be provided at the time of booking and will apply to your reservation.</p>

<h2>Third-party links and embeds</h2>
<p>Our website may contain links to third-party websites and services (including maps and booking providers). We do not control third-party services and are not responsible for their content or policies.</p>

<h2>Intellectual property</h2>
<p>All content on this site—including text, photos, video, logos, and design—is owned by or licensed to {$siteName} and is protected by applicable intellectual property laws. You may not reproduce or distribute any content without prior written permission.</p>

<h2>Disclaimer</h2>
<p>This website is provided on an "as is" and "as available" basis. While we strive for accuracy, we do not warrant that the site will be uninterrupted, error-free, or free of harmful components.</p>

<h2>Limitation of liability</h2>
<p>To the maximum extent permitted by law, {$siteName} shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or relating to your use of the website or services.</p>

<h2>Changes to these Terms</h2>
<p>We may revise these Terms from time to time. Updated Terms will be posted on this page with a revised "Last updated" date.</p>

<h2>Contact</h2>
<p>If you have questions about these Terms, please contact us at <a href="mailto:{$footerEmail}">{$footerEmail}</a>.</p>
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
