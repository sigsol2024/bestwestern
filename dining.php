<?php
require_once __DIR__ . '/includes/content-loader.php';

$cmsDefaults = require __DIR__ . '/includes/cms-defaults.php';
$heroPlaceholder = cms_default_setting('placeholder_hero_image');
$detailPlaceholder = cms_default_setting('placeholder_detail_image');
$galleryPlaceholder = cms_default_setting('placeholder_gallery_image');
$roomPlaceholder = cms_default_setting('placeholder_room_image');
$pageTitle = getPageSection('dining', 'page_title', 'Dining Experience');

$hero_kicker = getPageSection('dining', 'hero_kicker', 'Dining');
$hero_title_html = getPageSection('dining', 'hero_title_html', 'Lorem Ipsum <br/><i class="font-light opacity-90">Dolor Sit</i>');
$hero_subtitle = getPageSection('dining', 'hero_subtitle', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
$hero_hours = getPageSection('dining', 'hero_hours', 'Open Daily: 00:00 - 00:00');
$hero_bg = getPageSection('dining', 'hero_bg', $heroPlaceholder);

$intro_vertical = getPageSection('dining', 'intro_vertical', 'Lorem Menus');
$chef_title_html = getPageSection('dining', 'chef_title_html', 'Lorem <br/> <span class="text-primary italic">Ipsum</span>');
$chef_body_html = getPageSection('dining', 'chef_body_html', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>');
$chef_signature = getPageSection('dining', 'chef_signature', 'Lorem Ipsum');
$chef_main_img = getPageSection('dining', 'chef_main_img', $detailPlaceholder);
$chef_circle_img = getPageSection('dining', 'chef_circle_img', $galleryPlaceholder);

$visual_title = getPageSection('dining', 'visual_title', 'Lorem Gallery');
$visual_link_href = getPageSection('dining', 'visual_link_href', '/gallery');

$masonry = json_decode(getPageSection('dining', 'masonry_json', ''), true);
if (!is_array($masonry) || count($masonry) < 5) {
    $masonry = $cmsDefaults['dining_masonry'];
}

$menu_kicker = getPageSection('dining', 'menu_kicker', 'Lorem Menu');
$menu_heading = getPageSection('dining', 'menu_heading', 'Lorem Ipsum');
$menu_quote = getPageSection('dining', 'menu_quote', '"Lorem ipsum dolor sit amet."');
$menu_iframe_url = getPageSection('dining', 'menu_iframe_url', '');
$menuItems = json_decode(getPageSection('dining', 'menu_json', ''), true);
if (!is_array($menuItems) || count($menuItems) === 0) {
    $menuItems = $cmsDefaults['dining_menu'];
}

$cta_bg = getPageSection('dining', 'cta_bg', $roomPlaceholder);
$cta_title = getPageSection('dining', 'cta_title', 'Lorem Reservation');
$cta_body = getPageSection('dining', 'cta_body', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
$cta_btn1 = getPageSection('dining', 'cta_btn1', 'Contact Us');
$cta_btn1_href = getPageSection('dining', 'cta_btn1_href', '#');
$cta_btn1_href = trim((string)$cta_btn1_href) !== '' ? $cta_btn1_href : '#';
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($pageTitle) ?></title>
  <?php require_once __DIR__ . '/includes/head-header.php'; ?>
  <style>
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #efe8d6; }
    ::-webkit-scrollbar-thumb { background: #c9bfab; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #411d13; }
    .text-vertical {
      writing-mode: vertical-rl;
      text-orientation: mixed;
    }
    @keyframes dining-fade-in {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .animate-fade-in {
      animation: dining-fade-in 1s ease-out both;
    }
  </style>
</head>
<body class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark font-display text-text-main overflow-x-hidden selection:bg-primary/20 selection:text-primary">
<!-- Fabric Texture Overlay -->
<div class="fixed inset-0 pointer-events-none bg-fabric-pattern opacity-60 z-0"></div>
<div class="relative z-10 flex flex-col min-h-screen">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- Hero Section -->
<section class="relative w-full h-[85vh] overflow-hidden flex items-end pb-20 md:pb-32 px-6 md:px-16">
  <div class="absolute inset-0 z-0 w-full h-full bg-cover bg-center" data-alt="Dining hero"
       style="background-image: url('<?= e($hero_bg) ?>');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-transparent"></div>
  </div>
  <div class="relative z-10 w-full max-w-[1440px] mx-auto flex flex-col md:flex-row items-end justify-between gap-8">
    <div class="max-w-3xl">
      <span class="inline-block text-white/90 text-sm font-bold tracking-[0.2em] uppercase mb-4 animate-fade-in"><?= e($hero_kicker) ?></span>
      <h1 class="font-serif text-5xl md:text-6xl lg:text-8xl text-white font-medium leading-[1.1] mb-6">
        <?= $hero_title_html ?>
      </h1>
      <p class="text-white/80 text-lg md:text-xl font-light max-w-lg leading-relaxed">
        <?= e($hero_subtitle) ?>
      </p>
      <div class="mt-6 md:hidden">
        <div class="inline-flex items-center gap-2 text-sm font-medium tracking-wide bg-white/10 backdrop-blur-sm border border-white/30 rounded px-3 py-1 shadow-sm text-white/90">
          <span class="material-symbols-outlined text-white/90">schedule</span>
          <span><?= e($hero_hours) ?></span>
        </div>
      </div>
    </div>
    <div class="hidden md:flex flex-col items-end gap-4 text-white/90">
      <div class="flex items-center gap-2 text-sm font-medium tracking-wide bg-white/10 backdrop-blur-sm border border-white/30 rounded px-3 py-1 shadow-sm">
        <span class="material-symbols-outlined text-white/90">schedule</span>
        <span><?= e($hero_hours) ?></span>
      </div>
      <div class="w-px h-12 bg-white/20 my-2"></div>
      <button class="size-16 rounded-full border border-white/30 flex items-center justify-center hover:bg-primary hover:border-primary hover:text-white transition-all duration-500 group backdrop-blur-sm" type="button">
        <span class="material-symbols-outlined text-3xl group-hover:rotate-45 transition-transform duration-500">arrow_downward</span>
      </button>
    </div>
  </div>
</section>

<!-- Editorial Intro Section -->
<section class="relative py-[25px] px-6 md:px-16 max-w-[1440px] mx-auto">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-stretch lg:items-center">
    <div class="hidden lg:flex col-span-1 justify-center h-full">
      <span class="text-vertical text-xs tracking-[0.3em] uppercase text-text-main/40 font-bold border-l border-text-main/10 py-12 pl-4 h-full">
        <?= e($intro_vertical) ?>
      </span>
    </div>
    <div class="col-span-1 lg:col-span-5 flex flex-col gap-8">
      <h2 class="font-serif text-4xl md:text-5xl lg:text-6xl text-text-main dark:text-white leading-tight">
        <?= $chef_title_html ?>
      </h2>
      <div class="space-y-6 text-text-muted dark:text-white/80 text-lg leading-relaxed font-light">
        <?= $chef_body_html ?>
      </div>
      <div class="pt-6">
        <div class="flex items-center gap-4">
          <div class="h-px w-12 bg-primary"></div>
          <span class="font-serif italic text-xl text-text-main dark:text-white"><?= e($chef_signature) ?></span>
        </div>
      </div>
    </div>
    <div class="col-span-1 lg:col-span-6 relative mt-12 lg:mt-0 min-w-0 flex flex-col items-center gap-8 lg:block">
      <div class="aspect-[4/5] w-full max-w-lg mx-auto lg:max-w-none rounded-lg overflow-hidden relative z-10 shadow-2xl">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transform scale-105 hover:scale-100 transition-transform duration-[1.5s]" data-alt="Chef" style="background-image: url('<?= e($chef_main_img) ?>');"></div>
      </div>
      <div class="hidden lg:block absolute -bottom-10 -left-10 w-2/3 aspect-square bg-sand-darker dark:bg-gray-800 -z-10 rounded-lg"></div>
      <div class="relative w-44 max-w-[min(100%,220px)] aspect-square rounded-full border-8 border-background-light dark:border-background-dark overflow-hidden shadow-xl z-20 -mt-24 sm:-mt-28 md:mt-0 md:absolute md:ml-0 md:max-w-none md:w-48 md:-bottom-16 md:-right-6 lg:-right-12 lg:w-64">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hover:scale-110 transition-transform duration-700" data-alt="Detail" style="background-image: url('<?= e($chef_circle_img) ?>');"></div>
      </div>
    </div>
  </div>
</section>

<!-- Cinematic Masonry Gallery -->
<section class="py-20 px-2 sm:px-6 md:px-10 lg:px-16 2xl:px-24 max-w-none w-full">
  <div class="max-w-[2200px] mx-auto w-full">
  <div class="flex flex-col md:flex-row items-end justify-between mb-12">
    <h3 class="font-serif text-3xl md:text-4xl text-text-main dark:text-white"><?= e($visual_title) ?></h3>
    <a class="group flex items-center gap-2 text-sm font-bold tracking-wider uppercase mt-4 md:mt-0 text-text-main" href="<?= e(site_href((string)$visual_link_href)) ?>">
      See Full Gallery
      <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_right_alt</span>
    </a>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 md:gap-6 auto-rows-[240px] sm:auto-rows-[260px] md:auto-rows-[320px] lg:auto-rows-[380px] xl:auto-rows-[420px]">
    <?php
    $m0 = $masonry[0] ?? ['src' => '', 'tag' => '', 'caption' => ''];
    $m1 = $masonry[1] ?? $m0;
    $m2 = $masonry[2] ?? $m0;
    $m3 = $masonry[3] ?? $m0;
    $m4 = $masonry[4] ?? $m0;
    ?>
    <div class="md:col-span-8 row-span-1 md:row-span-2 relative group overflow-hidden rounded-lg min-h-[240px] md:min-h-[320px] h-full">
      <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] group-hover:scale-105" style="background-image: url('<?= e((string)($m0['src'] ?? '')) ?>');"></div>
      <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors duration-500"></div>
      <?php if (!empty($m0['tag']) || !empty($m0['caption'])): ?>
      <div class="absolute bottom-6 left-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-500">
        <?php if (!empty($m0['tag'])): ?><span class="text-xs font-bold uppercase tracking-widest bg-primary text-white px-2 py-1 mb-2 inline-block"><?= e((string)$m0['tag']) ?></span><?php endif; ?>
        <?php if (!empty($m0['caption'])): ?><p class="font-serif text-xl italic"><?= e((string)$m0['caption']) ?></p><?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="md:col-span-4 row-span-1 md:row-span-2 relative group overflow-hidden rounded-lg min-h-[240px] md:min-h-[320px] h-full">
      <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] group-hover:scale-105" style="background-image: url('<?= e((string)($m1['src'] ?? '')) ?>');"></div>
      <?php if (!empty($m1['tag']) || !empty($m1['caption'])): ?>
      <div class="absolute bottom-6 left-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-500">
        <?php if (!empty($m1['tag'])): ?><span class="text-xs font-bold uppercase tracking-widest bg-primary text-white px-2 py-1 mb-2 inline-block"><?= e((string)$m1['tag']) ?></span><?php endif; ?>
        <?php if (!empty($m1['caption'])): ?><p class="font-serif text-xl italic"><?= e((string)$m1['caption']) ?></p><?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="md:col-span-4 relative group overflow-hidden rounded-lg min-h-[240px] md:min-h-[320px] h-full">
      <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] group-hover:scale-105" style="background-image: url('<?= e((string)($m2['src'] ?? '')) ?>');"></div>
    </div>
    <div class="md:col-span-4 relative group overflow-hidden rounded-lg min-h-[240px] md:min-h-[320px] h-full">
      <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] group-hover:scale-105" style="background-image: url('<?= e((string)($m3['src'] ?? '')) ?>');"></div>
    </div>
    <div class="md:col-span-4 relative group overflow-hidden rounded-lg min-h-[240px] md:min-h-[320px] h-full">
      <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] group-hover:scale-105" style="background-image: url('<?= e((string)($m4['src'] ?? '')) ?>');"></div>
    </div>
  </div>
  </div>
</section>

<!-- Menu Highlight Section -->
<section id="diningMenu" class="py-[84px] px-6 md:px-16 bg-white dark:bg-surface-dark transition-colors relative">
  <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2">
    <div class="size-24 rounded-full border border-primary/30 flex items-center justify-center bg-white dark:bg-surface-dark">
      <span class="material-symbols-outlined text-primary text-3xl">restaurant_menu</span>
    </div>
  </div>
  <div class="max-w-[1000px] mx-auto">
    <div class="text-center mb-16 md:mb-24">
      <span class="text-xs font-bold uppercase tracking-[0.3em] text-primary mb-4 block"><?= e($menu_kicker) ?></span>
      <h2 class="font-serif text-4xl md:text-6xl text-text-main dark:text-white mb-6"><?= e($menu_heading) ?></h2>
      <p class="text-text-muted dark:text-white/60 max-w-lg mx-auto italic font-serif text-lg"><?= e($menu_quote) ?></p>
    </div>
    <?php
      $menu_iframe_url = trim((string)$menu_iframe_url);
      $menu_is_http = ($menu_iframe_url !== '' && (str_starts_with($menu_iframe_url, 'http://') || str_starts_with($menu_iframe_url, 'https://')));
    ?>
    <?php if ($menu_is_http): ?>
      <div class="rounded-xl overflow-hidden border border-gray-100 dark:border-white/10 shadow-xl bg-white dark:bg-surface-dark">
        <iframe
          src="<?= e($menu_iframe_url) ?>"
          title="Menu"
          loading="lazy"
          referrerpolicy="strict-origin-when-cross-origin"
          style="width:100%; height: 900px; border: 0;"
        ></iframe>
      </div>
      <div class="mt-10 text-center">
        <a class="inline-flex items-center justify-center gap-3 px-8 py-3 bg-primary text-white font-bold tracking-wide rounded-lg hover:bg-primary-light transition-colors shadow-md shadow-primary/20"
           href="<?= e($menu_iframe_url) ?>" target="_blank" rel="noopener noreferrer">
          <span>Open menu</span>
          <span class="material-symbols-outlined text-sm">open_in_new</span>
        </a>
      </div>
    <?php else: ?>
      <p class="text-text-muted dark:text-white/70 text-center">Menu URL is not configured yet.</p>
    <?php endif; ?>
  </div>
</section>

<!-- CTA / Reservation Section -->
<section id="diningReservation" class="py-[25px] px-6 md:px-16 flex justify-center items-center bg-sand-darker/30 dark:bg-black/20">
  <div class="relative w-full max-w-[1200px] overflow-hidden rounded-2xl bg-surface-ink text-white">
    <div class="absolute inset-0 opacity-40 mix-blend-overlay bg-cover bg-center" style="background-image: url('<?= e($cta_bg) ?>');"></div>
    <div class="relative z-10 px-6 py-20 md:py-24 md:px-20 text-center flex flex-col items-center gap-8">
      <div class="size-12 mb-2 text-primary">
        <span class="material-symbols-outlined text-5xl">concierge</span>
      </div>
      <h2 class="font-serif text-4xl md:text-6xl max-w-3xl leading-tight"><?= e($cta_title) ?></h2>
      <p class="text-white/70 max-w-lg text-lg"><?= e($cta_body) ?></p>
      <div class="flex flex-row flex-nowrap gap-2 sm:gap-4 w-full justify-center mt-4 min-w-0 max-w-2xl mx-auto px-1">
        <a href="<?= e(site_href((string)$cta_btn1_href)) ?>" class="w-full sm:w-auto px-6 sm:px-10 py-3 sm:py-4 text-xs sm:text-base bg-transparent border border-champagne/60 text-champagne hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 font-bold tracking-wide rounded-lg backdrop-blur-sm shadow-[0_0_24px_rgba(65,29,19,0.25)] hover:shadow-[0_0_36px_rgba(65,29,19,0.45)] text-center leading-tight inline-flex items-center justify-center">
          <?= e($cta_btn1) ?>
        </a>
      </div>
    </div>
  </div>
</section>

</div>

<!-- Floating skip widget: visible only while menu section is in view -->
<div id="diningMenuSkipWidget" class="fixed left-3 md:left-8 top-1/2 -translate-y-1/2 z-[9999] transition-opacity duration-300 opacity-0 pointer-events-none">
  <div class="flex flex-col items-center gap-2">
    <button id="diningMenuSkipBtn"
            type="button"
            class="size-12 rounded-full bg-black/20 hover:bg-black/30 text-white border border-white/25 backdrop-blur-sm shadow-lg transition-colors flex items-center justify-center"
            aria-label="Skip menu section">
      <span class="material-symbols-outlined text-2xl">arrow_downward</span>
    </button>
    <span class="text-[11px] font-bold uppercase tracking-widest text-white/80 bg-black/20 border border-white/15 backdrop-blur-sm rounded-full px-3 py-1 select-none">Skip</span>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var widget = document.getElementById('diningMenuSkipWidget');
  var btn = document.getElementById('diningMenuSkipBtn');
  var menu = document.getElementById('diningMenu');
  var next = document.getElementById('diningReservation');
  if (!widget || !btn || !menu || !next) return;

  btn.addEventListener('click', function () {
    next.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

  if (!('IntersectionObserver' in window)) {
    widget.classList.remove('opacity-0', 'pointer-events-none');
    widget.classList.add('opacity-100');
    return;
  }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.target !== menu) return;
      if (entry.isIntersecting) {
        widget.classList.remove('opacity-0', 'pointer-events-none');
        widget.classList.add('opacity-100');
      } else {
        widget.classList.add('opacity-0', 'pointer-events-none');
        widget.classList.remove('opacity-100');
      }
    });
  }, { root: null, threshold: 0.15, rootMargin: '-10% 0px -10% 0px' });
  io.observe(menu);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
