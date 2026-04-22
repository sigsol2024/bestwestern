<?php
$GLOBALS['site_header_overlaps_hero'] = true;
require_once __DIR__ . '/includes/content-loader.php';

$heroPlaceholder = cms_default_setting('placeholder_hero_image');
$detailPlaceholder = cms_default_setting('placeholder_detail_image');
$galleryPlaceholder = cms_default_setting('placeholder_gallery_image');

$pageTitle = getSiteSetting('site_name', cms_default_setting('site_name'));

$hero_trust_badge = getPageSection('index', 'hero_trust_badge', 'Travelers Choice 2026');
$hero_show_stars = trim((string)getPageSection('index', 'hero_show_stars', '1')) === '1';
$hero_title = normalize_home_hero_title_html(
    (string) getPageSection('index', 'hero_title', 'Luxury on the Shores<br/><span class="italic text-surface">of Oxbow Lake.</span>')
);
$hero_subtitle = getPageSection('index', 'hero_subtitle', 'An international standard of hospitality in the heart of Bayelsa.');
$hero_bg = getPageSection('index', 'hero_bg', $heroPlaceholder);
$hero_bg_url = site_media_url($hero_bg);

$booking_widget_html = getPageSection('index', 'booking_widget_html', '');
$hasBookingEmbed = trim((string) $booking_widget_html) !== '';
$bookingWrapperId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) getSiteSetting('booking_wrapper_id', cms_default_setting('booking_wrapper_id')));
if ($bookingWrapperId === '') {
    $bookingWrapperId = cms_default_setting('booking_wrapper_id');
}
$bookingWrapperSelector = '#' . $bookingWrapperId;

$hp_kicker = getPageSection('index', 'home_philosophy_kicker', 'Our Heritage');
$hp_title_html = getPageSection('index', 'home_philosophy_title_html', 'Where Heritage Meets Hospitality');
$hp_body = getPageSection(
    'index',
    'home_philosophy_body',
    '<p>Nestled in the heart of Bayelsa State, our hotel blends rich heritage with modern hospitality. As part of the Best Western <span class="text-brand-red font-semibold">Plus</span> collection, we uphold a legacy of excellence while delivering a distinctively Nigerian warmth.</p>'
);
$hp_link_text = getPageSection('index', 'home_philosophy_link_text', 'Explore the Story');
$hp_link_href = getPageSection('index', 'home_philosophy_link_href', '/about');
$hp_main_img = site_media_url(getPageSection('index', 'home_philosophy_main_img', $detailPlaceholder));

$rooms_kicker = getPageSection('index', 'home_rooms_kicker', 'Exquisite suites designed for the refined traveler');
$rooms_title = getPageSection('index', 'home_rooms_title', 'Sanctuaries of Calm');
$rooms_view_all = getPageSection('index', 'home_rooms_view_all_href', '/rooms');

$dining_kicker = getPageSection('index', 'home_dining_kicker', 'Gastronomy');
$dining_heading_html = trim((string) getPageSection('index', 'home_dining_heading_html', ''));
if ($dining_heading_html === '') {
    $legacyTitle = trim((string) getPageSection('index', 'home_dining_title', ''));
    $dining_heading_html = $legacyTitle !== ''
        ? '<span class="italic">' . htmlspecialchars($legacyTitle, ENT_QUOTES, 'UTF-8') . '</span>'
        : '<span class="italic">Culinary Excellence</span>';
}
$dining_v1_title = getPageSection('index', 'home_dining_venue1_title', 'Mama Oxbow');
$dining_v1_body = getPageSection('index', 'home_dining_venue1_body', 'Authentic local delicacies crafted with a modern touch, overlooking the gentle ripples of the lake.');
$dining_v2_title = getPageSection('index', 'home_dining_venue2_title', 'Red Lotus');
$dining_v2_body = getPageSection('index', 'home_dining_venue2_body', 'An Asian-fusion journey where tradition meets contemporary culinary innovation.');
$dining_top_path = trim((string) getPageSection('index', 'home_dining_image_top', ''));
if ($dining_top_path === '') {
    $dining_top_path = (string) getPageSection('index', 'home_dining_image', $galleryPlaceholder);
}
$dining_bottom_path = trim((string) getPageSection('index', 'home_dining_image_bottom', ''));
if ($dining_bottom_path === '') {
    $dining_bottom_path = $detailPlaceholder;
}
$dining_img_top = site_media_url($dining_top_path !== '' ? $dining_top_path : $galleryPlaceholder);
$dining_img_bottom = site_media_url($dining_bottom_path !== '' ? $dining_bottom_path : $detailPlaceholder);

$fac_title = getPageSection('index', 'home_facilities_title', 'Leisure & Wellness');
$fac_blurb = getPageSection('index', 'home_facilities_blurb', 'Designed to rejuvenate your senses and enhance your productivity.');
$bentoRaw = (string) getPageSection('index', 'home_facilities_bento_json', '');
$bentoTiles = json_decode($bentoRaw, true);
if (!is_array($bentoTiles) || count($bentoTiles) < 4) {
    $bentoTiles = [
        ['image' => $heroPlaceholder, 'title' => 'The Infinity Pool', 'subtitle' => 'Open Daily • 6AM - 10PM'],
        ['image' => $detailPlaceholder, 'title' => 'Wellness Spa', 'subtitle' => ''],
        ['image' => $galleryPlaceholder, 'title' => 'Elite Gym', 'subtitle' => ''],
        ['image' => $galleryPlaceholder, 'title' => 'Akassa Hall', 'subtitle' => 'Business & Events'],
    ];
}
foreach ($bentoTiles as $i => $tile) {
    if (!is_array($tile)) {
        $bentoTiles[$i] = ['image' => '', 'title' => '', 'subtitle' => ''];
        continue;
    }
    $bentoTiles[$i]['image'] = isset($tile['image']) ? (string) $tile['image'] : '';
    $bentoTiles[$i]['title'] = isset($tile['title']) ? (string) $tile['title'] : '';
    $bentoTiles[$i]['subtitle'] = isset($tile['subtitle']) ? (string) $tile['subtitle'] : '';
}

$loc_title = getPageSection('index', 'home_location_title', 'The Serenity of Oxbow Lake');
$loc_body = getPageSection('index', 'home_location_body', 'A peaceful retreat away from the city\'s pulse, offering breathtaking views and tranquil mornings.');
$bulletsRaw = (string) getPageSection('index', 'home_location_bullets_json', '');
$loc_bullets = json_decode($bulletsRaw, true);
if (!is_array($loc_bullets)) {
    $loc_bullets = ['5 min to Government House', '15 min to Airport', 'Oxbow Lake waterfront'];
}
$loc_bullets = array_values(array_filter(array_map('strval', $loc_bullets), static function ($s) { return trim($s) !== ''; }));
$loc_address = getPageSection('index', 'home_location_address', 'Oxbow Lake Rd, Yenagoa, Bayelsa');
$loc_map = site_media_url(getPageSection('index', 'home_location_map_image', $galleryPlaceholder));

$currency = getSiteSetting('currency_symbol', '$');
$featuredRooms = getFeaturedRoomsForHome(8);

$home_room_subtitle = static function (array $room): string {
    $sd = trim((string) ($room['short_description'] ?? ''));
    if ($sd !== '') {
        return preg_replace('/\s+/', ' ', strip_tags($sd));
    }
    $feats = $room['features'] ?? [];
    if (is_array($feats) && $feats !== []) {
        $parts = array_slice(array_map('strval', $feats), 0, 3);

        return implode(' • ', $parts);
    }
    $rt = trim((string) ($room['room_type'] ?? ''));
    $loc = trim((string) ($room['location'] ?? ''));
    if ($rt !== '' && $loc !== '') {
        return $rt . ' • ' . $loc;
    }

    return $rt !== '' ? $rt : $loc;
};
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($pageTitle) ?></title>
  <?php require_once __DIR__ . '/includes/head-header.php'; ?>
  <?php if ($hasBookingEmbed): ?>
  <style>
    #home-booking-embed-slot <?= $bookingWrapperSelector ?>,
    #home-booking-embed-slot #booking-widget,
    #home-booking-embed-slot #booking-form {
      position: static !important;
      transform: none !important;
      margin: 0 !important;
      max-width: none !important;
      width: 100% !important;
      box-shadow: none !important;
      border: 0 !important;
      background: transparent !important;
      padding: 0 !important;
    }
  </style>
  <?php endif; ?>
</head>
<body class="bg-surface text-on-surface font-body font-light selection:bg-secondary-container selection:text-on-secondary-container antialiased overflow-x-hidden">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- Hero -->
<header class="relative h-screen min-h-[600px] w-full overflow-hidden">
  <div class="absolute inset-0 z-0">
    <img class="w-full h-full object-cover" src="<?= e($hero_bg_url) ?>" alt="" width="1920" height="1080" fetchpriority="high"/>
    <div class="absolute inset-0 bg-gradient-to-b from-brand-ink/40 via-transparent to-brand-ink/80"></div>
  </div>
  <div class="relative z-10 flex flex-col justify-center items-center h-full text-center px-6 pt-20">
    <?php if ($hero_show_stars || trim($hero_trust_badge) !== ''): ?>
    <div class="flex items-center gap-2 mb-3">
      <?php if ($hero_show_stars): ?>
      <div class="flex text-brand-gold">
        <?php for ($si = 0; $si < 5; $si++): ?>
        <span class="material-symbols-outlined !text-xs" style="font-variation-settings:'FILL'1,'wght'400;">star</span>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
      <?php if (trim($hero_trust_badge) !== ''): ?>
      <span class="font-body text-[9px] sm:text-[10px] text-surface/80 uppercase tracking-[0.22em] sm:tracking-[0.28em]"><?= e($hero_trust_badge) ?></span>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <h1 class="site-hero-title font-headline text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-surface leading-tight mb-5 max-w-5xl">
      <?= $hero_title ?>
    </h1>
    <p class="font-body text-sm md:text-base lg:text-lg text-surface/90 tracking-widest uppercase font-light max-w-3xl">
      <?= e($hero_subtitle) ?>
    </p>
  </div>

  <?php if ($hasBookingEmbed): ?>
  <div class="absolute bottom-8 md:bottom-12 left-1/2 -translate-x-1/2 w-full max-w-5xl px-4 md:px-6 z-20">
    <div class="bg-surface/95 backdrop-blur-xl p-2 shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)]">
      <div class="min-w-0 p-3 md:p-4" id="home-booking-embed-slot">
        <?= $booking_widget_html ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</header>

<!-- Brand story -->
<section class="py-24 md:py-32 px-6 md:px-12 max-w-screen-2xl mx-auto bg-surface">
  <div class="grid grid-cols-1 md:grid-cols-12 gap-12 md:gap-16 items-center">
    <div class="md:col-span-7 relative">
      <img class="w-full aspect-[5/6] max-h-[480px] sm:max-h-[500px] md:max-h-[540px] object-cover shadow-2xl shadow-brand-ink/5" src="<?= e($hp_main_img) ?>" alt="" width="800" height="960"/>
      <div class="absolute -bottom-6 -right-6 md:-bottom-8 md:-right-8 w-48 h-48 md:w-64 md:h-64 bg-surface-container-low -z-10" aria-hidden="true"></div>
    </div>
    <div class="md:col-span-5 flex flex-col">
      <?php if (trim($hp_kicker) !== ''): ?>
      <span class="font-body uppercase tracking-[0.3em] text-brand-gold mb-6 md:mb-8 text-sm"><?= e($hp_kicker) ?></span>
      <?php endif; ?>
      <div class="font-headline text-3xl md:text-4xl lg:text-5xl text-on-surface leading-tight mb-8 md:mb-10"><?= $hp_title_html ?></div>
      <div class="font-body text-on-surface-variant leading-relaxed text-lg mb-8 [&_a]:text-brand-gold [&_a]:underline"><?= $hp_body ?></div>
      <?php if (trim($hp_link_text) !== ''): ?>
      <a class="font-body text-brand-gold uppercase tracking-widest text-sm font-bold border-b border-brand-gold/20 pb-2 self-start hover:border-brand-gold transition-all" href="<?= e(site_href((string) $hp_link_href)) ?>"><?= e($hp_link_text) ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Rooms -->
<section class="py-24 md:py-32 bg-surface-container-low relative">
  <div class="px-6 md:px-12 mb-12 md:mb-20 text-center max-w-4xl mx-auto">
    <h2 class="font-headline text-4xl md:text-5xl text-on-surface mb-4 md:mb-6"><?= e($rooms_title) ?></h2>
    <?php if (trim($rooms_kicker) !== ''): ?>
    <p class="font-body text-on-surface-variant uppercase tracking-widest text-xs"><?= e($rooms_kicker) ?></p>
    <?php endif; ?>
  </div>
  <div class="relative">
    <?php
    $roomsForSlider = is_array($featuredRooms) ? array_slice($featuredRooms, 0, 8) : [];
    ?>
    <div id="homeRoomsScroller" class="overflow-x-auto no-scrollbar flex space-x-6 md:space-x-8 px-6 md:px-12 pb-10 scroll-smooth">
      <?php if ($roomsForSlider === []): ?>
      <p class="font-body text-on-surface-variant py-8 px-2">No rooms to show yet. Mark rooms as <strong>Featured</strong> in Admin → Rooms.</p>
      <?php else: ?>
        <?php foreach ($roomsForSlider as $room):
            $rtitle = (string) ($room['title'] ?? '');
            $rslug = (string) ($room['slug'] ?? '');
            $rprice = is_numeric($room['price'] ?? null) ? number_format((float) $room['price'], 0) : '';
            $rsub = $home_room_subtitle($room);
            $rimgPath = (string) ($room['main_image'] ?? '');
            $rimg = $rimgPath !== '' ? site_media_url($rimgPath) : site_media_url($detailPlaceholder);
            ?>
      <a class="flex-shrink-0 w-[min(100vw-3rem,450px)] group text-left" href="<?= e(site_url('room-details', ['slug' => $rslug])) ?>">
        <div class="relative overflow-hidden mb-6 aspect-[3/4]">
          <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="<?= e($rimg) ?>" alt="<?= e($rtitle) ?>" width="450" height="600"/>
          <div class="absolute inset-0 bg-brand-ink/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
        </div>
        <div class="flex justify-between items-end gap-4">
          <div class="min-w-0">
            <h3 class="font-headline text-2xl text-on-surface mb-1"><?= e($rtitle) ?></h3>
            <?php if ($rsub !== ''): ?>
            <span class="font-body text-sm text-on-surface-variant uppercase tracking-widest line-clamp-2"><?= e($rsub) ?></span>
            <?php endif; ?>
          </div>
          <?php if ($rprice !== ''): ?>
          <span class="font-body text-lg text-brand-gold font-semibold shrink-0"><?= e($currency) ?><?= e($rprice) ?></span>
          <?php endif; ?>
        </div>
      </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <?php if ($roomsForSlider !== []): ?>
    <div class="flex justify-center gap-2 mt-2 md:mt-4" aria-hidden="true">
      <span class="h-1.5 w-6 rounded-full bg-brand-gold"></span>
      <span class="h-1.5 w-1.5 rounded-full bg-outline-variant"></span>
      <span class="h-1.5 w-1.5 rounded-full bg-outline-variant"></span>
    </div>
    <?php endif; ?>
    <div class="absolute top-0 right-0 h-full w-24 md:w-32 rooms-fade-overlay pointer-events-none hidden md:block"></div>
    <div class="px-6 md:px-12 mt-8 text-right">
      <a class="font-body text-xs uppercase tracking-[0.2em] font-bold text-brand-gold hover:text-on-surface transition-colors inline-flex items-center gap-2 group" href="<?= e(site_href((string) $rooms_view_all)) ?>">
        View All Suites <span class="w-1 h-1 bg-brand-red rounded-full opacity-60"></span> <span class="material-symbols-outlined !text-sm">arrow_forward</span>
      </a>
    </div>
  </div>
</section>

<!-- Dining -->
<section class="py-24 md:py-32 bg-background-dark text-surface">
  <div class="px-6 md:px-12 max-w-screen-2xl mx-auto flex flex-col lg:flex-row gap-16 lg:gap-24 items-center">
    <div class="lg:w-1/2 w-full">
      <?php if (trim($dining_kicker) !== ''): ?>
      <span class="font-body uppercase tracking-[0.3em] text-brand-gold text-xs mb-6 md:mb-8 block"><?= e($dining_kicker) ?></span>
      <?php endif; ?>
      <div class="font-headline text-4xl md:text-6xl lg:text-7xl mb-10 md:mb-12"><?= $dining_heading_html ?></div>
      <div class="space-y-12 md:space-y-16">
        <div class="border-l-2 border-brand-gold/30 pl-6 md:pl-8 py-2">
          <h4 class="font-body text-xl md:text-2xl font-semibold mb-3 md:mb-4 tracking-tight"><?= e($dining_v1_title) ?></h4>
          <p class="font-body text-surface/60 leading-relaxed font-light"><?= e($dining_v1_body) ?></p>
        </div>
        <div class="border-l-2 border-brand-gold/30 pl-6 md:pl-8 py-2">
          <h4 class="font-body text-xl md:text-2xl font-semibold mb-3 md:mb-4 tracking-tight"><?= e($dining_v2_title) ?></h4>
          <p class="font-body text-surface/60 leading-relaxed font-light"><?= e($dining_v2_body) ?></p>
        </div>
      </div>
    </div>
    <div class="lg:w-1/2 w-full grid grid-cols-2 gap-4 md:gap-6 min-h-[400px] md:h-[700px]">
      <div class="pt-8 md:pt-12 min-h-0">
        <img class="w-full h-full min-h-[200px] object-cover rounded-sm" src="<?= e($dining_img_top) ?>" alt="" width="400" height="500"/>
      </div>
      <div class="pb-8 md:pb-12 min-h-0">
        <img class="w-full h-full min-h-[200px] object-cover rounded-sm" src="<?= e($dining_img_bottom) ?>" alt="" width="400" height="500"/>
      </div>
    </div>
  </div>
</section>

<!-- Facilities bento -->
<section class="py-24 md:py-32 px-6 md:px-12 bg-surface">
  <div class="max-w-screen-2xl mx-auto">
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-6 mb-12 md:mb-20">
      <h2 class="font-headline text-4xl md:text-5xl text-on-surface"><?= e($fac_title) ?></h2>
      <?php if (trim($fac_blurb) !== ''): ?>
      <div class="font-body text-on-surface-variant md:text-right max-w-xs uppercase tracking-widest text-[10px] leading-relaxed"><?= e($fac_blurb) ?></div>
      <?php endif; ?>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 md:grid-rows-2 gap-6 md:gap-8 md:h-[800px]">
      <?php
      $t0 = $bentoTiles[0];
        $t1 = $bentoTiles[1];
        $t2 = $bentoTiles[2];
        $t3 = $bentoTiles[3];
        $tileImg = static function ($path) use ($heroPlaceholder) {
            $p = trim((string) $path);

            return $p !== '' ? site_media_url($p) : site_media_url($heroPlaceholder);
        };
        ?>
      <div class="md:col-span-2 md:row-span-2 relative group overflow-hidden min-h-[280px] md:min-h-0">
        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 min-h-[280px] md:min-h-0" src="<?= e($tileImg($t0['image'])) ?>" alt="<?= e($t0['title']) ?>" width="800" height="800"/>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent p-6 md:p-10 flex flex-col justify-end">
          <h4 class="font-body text-surface text-2xl md:text-3xl font-light"><?= e($t0['title']) ?></h4>
          <?php if (trim($t0['subtitle']) !== ''): ?>
          <span class="font-body text-brand-gold text-xs uppercase tracking-widest mt-2"><?= e($t0['subtitle']) ?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="md:col-span-1 relative group overflow-hidden min-h-[220px] md:min-h-0">
        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 min-h-[220px] md:min-h-0" src="<?= e($tileImg($t1['image'])) ?>" alt="<?= e($t1['title']) ?>" width="400" height="400"/>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent p-5 md:p-6 flex flex-col justify-end">
          <h4 class="font-body text-surface text-lg md:text-xl font-light"><?= e($t1['title']) ?></h4>
          <?php if (trim($t1['subtitle']) !== ''): ?>
          <span class="font-body text-brand-gold text-xs uppercase tracking-widest mt-2"><?= e($t1['subtitle']) ?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="md:col-span-1 relative group overflow-hidden min-h-[220px] md:min-h-0">
        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 min-h-[220px] md:min-h-0" src="<?= e($tileImg($t2['image'])) ?>" alt="<?= e($t2['title']) ?>" width="400" height="400"/>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent p-5 md:p-6 flex flex-col justify-end">
          <h4 class="font-body text-surface text-lg md:text-xl font-light"><?= e($t2['title']) ?></h4>
          <?php if (trim($t2['subtitle']) !== ''): ?>
          <span class="font-body text-brand-gold text-xs uppercase tracking-widest mt-2"><?= e($t2['subtitle']) ?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="md:col-span-2 relative group overflow-hidden min-h-[240px] md:min-h-0">
        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 min-h-[240px] md:min-h-0" src="<?= e($tileImg($t3['image'])) ?>" alt="<?= e($t3['title']) ?>" width="800" height="400"/>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent p-6 md:p-8 flex flex-col justify-end">
          <h4 class="font-body text-surface text-xl md:text-2xl font-light"><?= e($t3['title']) ?></h4>
          <?php if (trim($t3['subtitle']) !== ''): ?>
          <span class="font-body text-brand-gold text-xs uppercase tracking-widest mt-2"><?= e($t3['subtitle']) ?></span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Location -->
<section class="py-24 md:py-32 bg-surface-container-low border-t border-outline-variant/10">
  <div class="px-6 md:px-12 max-w-screen-2xl mx-auto flex flex-col lg:flex-row gap-12 lg:gap-20">
    <div class="lg:w-1/3 w-full">
      <h2 class="font-headline text-3xl md:text-4xl mb-6 md:mb-8 text-on-surface"><?= e($loc_title) ?></h2>
      <p class="font-body text-on-surface-variant leading-relaxed mb-8"><?= nl2br(e($loc_body)) ?></p>
      <?php if ($loc_bullets !== []): ?>
      <ul class="space-y-4 mb-10 font-body text-sm text-on-surface-variant/80 uppercase tracking-widest">
        <?php foreach ($loc_bullets as $li): ?>
        <li class="flex items-center gap-3">
          <span class="w-1.5 h-1.5 rounded-full bg-brand-gold shrink-0"></span>
          <?= e($li) ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
      <?php if (trim($loc_address) !== ''): ?>
      <div class="flex items-start space-x-4">
        <span class="material-symbols-outlined text-brand-gold shrink-0">location_on</span>
        <span class="font-body text-sm tracking-wide text-on-surface"><?= e($loc_address) ?></span>
      </div>
      <?php endif; ?>
    </div>
    <div class="lg:w-2/3 w-full min-h-[320px] md:h-[500px] shadow-xl overflow-hidden grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition-all duration-700">
      <div class="w-full h-full min-h-[320px] md:min-h-[500px] bg-surface-container-highest p-2 md:p-3 flex">
        <img class="w-full h-full object-cover min-h-[280px] md:min-h-[476px] flex-1" src="<?= e($loc_map) ?>" alt="" width="1200" height="800"/>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
