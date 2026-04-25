<?php
$GLOBALS['site_header_overlaps_hero'] = true;
require_once __DIR__ . '/includes/content-loader.php';

$cmsDefaults = require __DIR__ . '/includes/cms-defaults.php';
$heroPlaceholder = cms_default_setting('placeholder_hero_image');
$detailPlaceholder = cms_default_setting('placeholder_detail_image');
$galleryPlaceholder = cms_default_setting('placeholder_gallery_image');
$pageTitle = getPageSection('about', 'page_title', 'Our Story');

// Hero (intentional asymmetry)
$hero_title_html = (string) getPageSection('about', 'hero_title_html', 'Our Story');
$hero_property_line = (string) getPageSection('about', 'hero_property_line', 'A Best Western Plus Property');
$hero_intro = (string) getPageSection(
    'about',
    'hero_intro',
    "In the heart of the Niger Delta, where the river meets the soul of Nigeria, stands an exhibition of luxury—a sanctuary crafted for the stately traveler."
);
$hero_bg = (string) getPageSection('about', 'hero_bg', $heroPlaceholder);
$hero_bg_url = site_media_url($hero_bg);

// Philosophy (tonal layering)
$philosophy_kicker = (string) getPageSection('about', 'philosophy_kicker', 'Philosophy');
$philosophy_title = (string) getPageSection('about', 'philosophy_title', 'The Art of Living Intentionally');
$philosophy_p1 = (string) getPageSection(
    'about',
    'philosophy_p1',
    'We believe that true luxury is not defined by excess, but by the quiet confidence of quality. Every curve of our architecture and every texture in our suites has been curated to provide a sense of calm authority.'
);
$philosophy_p2 = (string) getPageSection(
    'about',
    'philosophy_p2',
    'BW Plus Yenagoa is more than a destination; it is a stately home for those who appreciate the finer nuances of hospitality.'
);
$philosophy_image_1 = trim((string) getPageSection('about', 'philosophy_image_1', ''));
if ($philosophy_image_1 === '') {
    $philosophy_image_1 = (string) getPageSection('about', 'story_image', $detailPlaceholder);
}
$philosophy_image_2 = trim((string) getPageSection('about', 'philosophy_image_2', ''));
if ($philosophy_image_2 === '') {
    $philosophy_image_2 = (string) getPageSection('about', 'values_image', $galleryPlaceholder);
}
$philosophy_image_1_url = site_media_url($philosophy_image_1);
$philosophy_image_2_url = site_media_url($philosophy_image_2);

// Rooted in the Delta (culture integration)
$culture_title = (string) getPageSection('about', 'culture_title', 'Rooted in the Delta');
$culture_feature_1_title = (string) getPageSection('about', 'culture_feature_1_title', 'Indigenous Soul');
$culture_feature_1_body = (string) getPageSection('about', 'culture_feature_1_body', 'Our interior palettes are inspired by the rich silt and golden sunsets of the Nun River, honoring the land we stand upon.');
$culture_feature_2_title = (string) getPageSection('about', 'culture_feature_2_title', 'Local Artistry');
$culture_feature_2_body = (string) getPageSection('about', 'culture_feature_2_body', 'Collaborating with Bayelsan artisans, we showcase traditional motifs reimagined for a modern global audience.');
$culture_image = (string) getPageSection('about', 'culture_image', $galleryPlaceholder);
$culture_image_url = site_media_url($culture_image);

// Heritage (editorial layout)
$heritage_title = (string) getPageSection('about', 'heritage_title', 'Our Heritage');
$heritage_body = (string) getPageSection('about', 'heritage_body', "Established as a beacon of Yenagoa's rising prominence, Best Western Plus Yenagoa has evolved into the city's premier destination for diplomatic and corporate excellence.");
$heritage_link_label = (string) getPageSection('about', 'heritage_link_label', 'EXPLORE OUR TIMELINE');
$heritage_link_href = (string) getPageSection('about', 'heritage_link_href', '#');
$heritage_image_1 = (string) getPageSection('about', 'heritage_image_1', $detailPlaceholder);
$heritage_image_2 = (string) getPageSection('about', 'heritage_image_2', $galleryPlaceholder);
$heritage_image_1_url = site_media_url($heritage_image_1);
$heritage_image_2_url = site_media_url($heritage_image_2);

// Call to experience
$experience_bg = trim((string) getPageSection('about', 'experience_bg', ''));
if ($experience_bg === '') {
    $experience_bg = (string) getPageSection('about', 'parallax_bg', $heroPlaceholder);
}
$experience_bg_url = site_media_url($experience_bg);
$experience_title = (string) getPageSection('about', 'experience_title', 'Write Your Own Story');
$experience_button_label = (string) getPageSection('about', 'experience_button_label', 'Begin Your Stay');
$experience_button_href = (string) getPageSection('about', 'experience_button_href', '/rooms');
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        .scrim-bottom {
            background: linear-gradient(to top, rgba(11, 31, 58, 0.9) 0%, rgba(11, 31, 58, 0) 100%);
        }

        /* About page palette override to match the provided sample exactly */
        body[data-page="about"] { background-color: #fdf9f3; color: #1c1c18; }
        body[data-page="about"] ::selection { background: #fedb98; color: #785f28; }
        body[data-page="about"] .bg-surface { background-color: #fdf9f3 !important; }
        body[data-page="about"] .text-on-surface { color: #1c1c18 !important; }
        body[data-page="about"] .text-on-surface-variant { color: #44474d !important; }
        body[data-page="about"] .bg-surface-container-low { background-color: #f7f3ed !important; }
        body[data-page="about"] .bg-surface-container { background-color: #f1ede7 !important; }
        body[data-page="about"] .bg-surface-container-high { background-color: #ebe8e2 !important; }
        body[data-page="about"] .bg-surface-container-highest { background-color: #e6e2dc !important; }
        body[data-page="about"] .text-primary { color: #000615 !important; }
        body[data-page="about"] .bg-primary-container { background-color: #0b1f3a !important; }
        body[data-page="about"] .text-primary-container { color: #0b1f3a !important; }
        body[data-page="about"] .text-on-primary-container { color: #7587a7 !important; }
        body[data-page="about"] .bg-secondary { background-color: #C8A96A !important; }
        body[data-page="about"] .text-secondary { color: #C8A96A !important; }
        body[data-page="about"] .text-on-secondary { color: #ffffff !important; }
        body[data-page="about"] .bg-gold-standard { background-color: #C8A96A !important; }
        body[data-page="about"] .text-gold-standard { color: #C8A96A !important; }
  </style>
</head>
<body data-page="about" class="bg-surface text-on-surface selection:bg-secondary-container selection:text-on-secondary-container overflow-x-hidden">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<main class="pt-32">
  <!-- Hero Section: Intentional Asymmetry -->
  <section class="px-12 mb-32 grid grid-cols-12 gap-8 items-end flex flex-col md:grid">
    <div class="col-span-12 md:col-span-7 h-[500px] md:h-[760px] relative overflow-hidden group order-2 md:order-1">
      <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="Luxury hotel exterior with modernist architecture" src="<?= e($hero_bg_url) ?>"/>
    </div>
    <div class="col-span-12 md:col-span-4 md:col-start-9 pb-12 order-1 md:order-2">
      <h1 class="notoSerif text-6xl md:text-8xl leading-none mb-2 text-primary italic"><?= $hero_title_html ?></h1>
      <p class="inter text-[10px] uppercase tracking-[0.3em] text-gold-standard font-semibold mb-8"><?= e($hero_property_line) ?></p>
      <p class="inter text-lg text-on-surface-variant font-light leading-relaxed"><?= e($hero_intro) ?></p>
      <div class="mt-12 h-px w-24 bg-secondary"></div>
    </div>
  </section>

  <!-- Section: Hotel Philosophy (Tonal Layering) -->
  <section class="bg-surface-container-low py-32 px-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-24 items-center">
      <div class="order-2 md:order-1">
        <span class="inter text-xs uppercase tracking-[0.3em] text-secondary font-bold mb-6 block"><?= e($philosophy_kicker) ?></span>
        <h2 class="notoSerif text-5xl mb-8 leading-tight text-primary-container"><?= e($philosophy_title) ?></h2>
        <p class="inter text-lg text-on-surface-variant leading-relaxed mb-8"><?= e($philosophy_p1) ?></p>
        <p class="inter text-lg text-on-surface-variant leading-relaxed"><?= e($philosophy_p2) ?></p>
      </div>
      <div class="order-1 md:order-2 grid grid-cols-2 gap-4">
        <div class="aspect-[3/4] bg-surface-container-highest overflow-hidden">
          <img class="w-full h-full object-cover" data-alt="Hotel lobby details" src="<?= e($philosophy_image_1_url) ?>"/>
        </div>
        <div class="aspect-[3/4] bg-surface-container-highest overflow-hidden mt-12">
          <img class="w-full h-full object-cover" data-alt="Luxury spa environment" src="<?= e($philosophy_image_2_url) ?>"/>
        </div>
      </div>
    </div>
  </section>

  <!-- Section: Bayelsa Culture Integration (Full Bleed/Editorial) -->
  <section class="py-32 bg-primary-container text-white overflow-hidden">
    <div class="px-12 grid grid-cols-12 gap-8 items-center">
      <div class="col-span-12 md:col-span-5 z-10">
        <h2 class="notoSerif text-6xl italic mb-12"><?= e($culture_title) ?></h2>
        <div class="space-y-12 max-w-md">
          <div class="group">
            <div class="w-12 h-[1px] bg-gold-standard mb-6 transition-all group-hover:w-24"></div>
            <h4 class="inter font-bold uppercase tracking-widest text-sm mb-3 text-gold-standard"><?= e($culture_feature_1_title) ?></h4>
            <p class="inter text-on-primary-container leading-relaxed"><?= e($culture_feature_1_body) ?></p>
          </div>
          <div class="group">
            <div class="w-12 h-[1px] bg-gold-standard mb-6 transition-all group-hover:w-24"></div>
            <h4 class="inter font-bold uppercase tracking-widest text-sm mb-3 text-gold-standard"><?= e($culture_feature_2_title) ?></h4>
            <p class="inter text-on-primary-container leading-relaxed"><?= e($culture_feature_2_body) ?></p>
          </div>
        </div>
      </div>
      <div class="col-span-12 md:col-span-7 relative">
        <div class="aspect-video bg-slate-800 rounded-lg overflow-hidden shadow-2xl">
          <img class="w-full h-full object-cover opacity-80" data-alt="Aerial cinematic view of a winding river" src="<?= e($culture_image_url) ?>"/>
        </div>
        <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-secondary/20 blur-3xl rounded-full"></div>
      </div>
    </div>
  </section>

  <!-- Section: Heritage & The Stately Curator (Editorial Layout) -->
  <section class="py-48 px-12 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row gap-24">
      <div class="md:w-1/3">
        <h3 class="notoSerif text-4xl mb-6"><?= e($heritage_title) ?></h3>
        <p class="inter text-on-surface-variant font-light mb-12"><?= e($heritage_body) ?></p>
        <div class="flex flex-col gap-2">
          <div class="w-full h-[1px] bg-gold-standard/30"></div>
          <a class="inline-flex items-center gap-4 text-secondary uppercase tracking-[0.2em] text-xs font-bold group py-2" href="<?= e(site_href($heritage_link_href)) ?>">
            <?= e($heritage_link_label) ?>
            <span class="material-symbols-outlined transition-transform group-hover:translate-x-2">trending_flat</span>
          </a>
        </div>
      </div>
      <div class="md:w-2/3">
        <div class="grid grid-cols-12 gap-4">
          <div class="col-span-7 h-96 bg-surface-container overflow-hidden">
            <img class="w-full h-full object-cover" data-alt="Professional hotel staff" src="<?= e($heritage_image_1_url) ?>"/>
          </div>
          <div class="col-span-5 h-96 bg-surface-container-high overflow-hidden">
            <img class="w-full h-full object-cover" data-alt="Sophisticated hotel bar area" src="<?= e($heritage_image_2_url) ?>"/>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section: Call to Experience -->
  <section class="relative h-[614px] flex items-center justify-center text-center overflow-hidden">
    <img class="absolute inset-0 w-full h-full object-cover" data-alt="Luxury hotel swimming pool at twilight" src="<?= e($experience_bg_url) ?>"/>
    <div class="absolute inset-0 bg-primary/40 backdrop-blur-[2px]"></div>
    <div class="relative z-10 px-6">
      <h2 class="notoSerif text-5xl md:text-7xl text-white mb-8"><?= e($experience_title) ?></h2>
      <button type="button" class="bg-secondary text-on-secondary px-12 py-5 rounded-lg inter uppercase tracking-[0.3em] text-sm font-bold hover:opacity-90 transition-all shadow-xl" onclick="window.location.href='<?= e(site_href($experience_button_href)) ?>'"><?= e($experience_button_label) ?></button>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
