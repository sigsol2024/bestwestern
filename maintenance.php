<?php
require_once __DIR__ . '/includes/content-loader.php';

$siteName = getSiteSetting('site_name', cms_default_setting('site_name'));
$title = getSiteSetting('maintenance_title', cms_default_setting('maintenance_title'));
$message = getSiteSetting('maintenance_message', cms_default_setting('maintenance_message'));
$backgroundPath = getSiteSetting('maintenance_background', cms_default_setting('maintenance_background'));
$backgroundUrl = $backgroundPath !== '' ? site_media_url((string) $backgroundPath) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex,nofollow">
  <title><?= e($title) ?> - <?= e($siteName) ?></title>
  <?php require_once __DIR__ . '/includes/head-header.php'; ?>
  <style>
    .maintenance-shell {
      min-height: 100vh;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 32px 20px;
      background: #1a1210;
      color: #fff;
      overflow: hidden;
    }
    .maintenance-shell::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image: <?= $backgroundUrl !== '' ? ("url('" . e($backgroundUrl) . "')") : 'none' ?>;
      background-size: cover;
      background-position: center;
      opacity: 0.34;
      transform: scale(1.03);
    }
    .maintenance-shell::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(18,16,14,0.72) 0%, rgba(18,16,14,0.84) 100%);
    }
    .maintenance-card {
      position: relative;
      z-index: 1;
      width: min(680px, 100%);
      padding: 40px 32px;
      border-radius: 18px;
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.14);
      backdrop-filter: blur(10px);
      text-align: center;
    }
    .maintenance-kicker {
      display: inline-block;
      margin-bottom: 14px;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: rgba(255,255,255,0.78);
    }
    .maintenance-card h1 {
      margin: 0 0 14px;
      font-size: clamp(2rem, 5vw, 3.5rem);
      line-height: 1.05;
      color: #fff;
    }
    .maintenance-card p {
      margin: 0;
      font-size: 1rem;
      line-height: 1.75;
      color: rgba(255,255,255,0.86);
      white-space: pre-line;
    }
  </style>
</head>
<body>
  <main class="maintenance-shell">
    <section class="maintenance-card">
      <span class="maintenance-kicker"><?= e($siteName) ?></span>
      <h1><?= e($title) ?></h1>
      <p><?= e($message) ?></p>
    </section>
  </main>
</body>
</html>
