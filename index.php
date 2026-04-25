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

$heroGalleryRaw = trim((string) getPageSection('index', 'hero_gallery_slides_json', ''));
$heroSlideUrls = [];
$galleryDecoded = json_decode($heroGalleryRaw, true);
if (is_array($galleryDecoded)) {
    foreach ($galleryDecoded as $path) {
        $p = is_string($path) ? trim($path) : '';
        if ($p !== '') {
            $heroSlideUrls[] = site_media_url($p);
        }
    }
}
if ($heroSlideUrls === []) {
    $heroSlideUrls = [$hero_bg_url];
}
$heroSliderCount = count($heroSlideUrls);
$heroSliderMultiple = $heroSliderCount > 1;

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
$loc_map_embed_custom = trim((string) getPageSection('index', 'home_location_map_embed_url', ''));
$googleMapsApiKey = trim((string) getSiteSetting('google_maps_api_key', ''));
$loc_address_trim = trim((string) $loc_address);
$loc_map_iframe_src = '';
if ($loc_map_embed_custom !== '') {
    $loc_map_iframe_src = $loc_map_embed_custom;
} elseif ($googleMapsApiKey !== '' && $loc_address_trim !== '') {
    $loc_map_iframe_src = 'https://www.google.com/maps/embed/v1/place?key=' . rawurlencode($googleMapsApiKey) . '&q=' . rawurlencode($loc_address_trim);
} elseif ($loc_address_trim !== '') {
    $loc_map_iframe_src = 'https://www.google.com/maps?q=' . rawurlencode($loc_address_trim) . '&output=embed';
}
$loc_show_map_embed = ($loc_map_iframe_src !== '');

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
<body class="bg-surface text-on-surface font-body font-light selection:bg-secondary-container selection:text-on-secondary-container antialiased max-w-[100vw] overflow-x-clip">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- Hero (full-bleed gallery slider) -->
<header id="home-hero" class="relative h-screen min-h-[600px] w-full overflow-hidden" data-hero-autoplay-ms="6000">
  <div class="absolute inset-0 z-0" aria-hidden="true">
    <?php foreach ($heroSlideUrls as $i => $slideUrl): ?>
    <div class="home-hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out <?= $i === 0 ? 'opacity-100 z-[1]' : 'opacity-0 z-0' ?>" data-hero-slide="<?= (int) $i ?>">
      <img class="w-full h-full object-cover" src="<?= e($slideUrl) ?>" alt="" width="1920" height="1080"<?= $i === 0 ? ' fetchpriority="high"' : ' loading="lazy"' ?>/>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="pointer-events-none absolute inset-0 z-[2] bg-gradient-to-r from-black/85 via-black/50 to-black/0"></div>
  <div class="pointer-events-none absolute inset-0 z-[2] bg-gradient-to-b from-brand-ink/30 via-transparent to-brand-ink/70"></div>

  <div class="relative z-10 flex h-full flex-col justify-center px-6 pt-24 pb-28 md:px-14 lg:pl-24 lg:pr-12 text-left max-w-[42rem]">
    <?php if ($hero_show_stars || trim($hero_trust_badge) !== ''): ?>
    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mb-4">
      <?php if ($hero_show_stars): ?>
      <div class="flex text-brand-gold">
        <?php for ($si = 0; $si < 5; $si++): ?>
        <span class="material-symbols-outlined !text-sm sm:!text-base" style="font-variation-settings:'FILL'1,'wght'400;">star</span>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
      <?php if (trim($hero_trust_badge) !== ''): ?>
      <span class="font-body text-[10px] sm:text-xs text-surface/85 uppercase tracking-[0.22em] sm:tracking-[0.28em]"><?= e($hero_trust_badge) ?></span>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <h1 class="site-hero-title font-headline text-4xl sm:text-5xl md:text-6xl lg:text-7xl text-surface leading-[1.15] mb-5 drop-shadow-sm">
      <?= $hero_title ?>
    </h1>
    <p class="font-body text-base md:text-lg text-surface/90 font-light max-w-xl leading-relaxed">
      <?= e($hero_subtitle) ?>
    </p>
  </div>

  <?php if ($heroSliderMultiple): ?>
  <button type="button" class="home-hero-nav absolute left-3 md:left-6 top-1/2 z-20 -translate-y-1/2 rounded-md border border-white/25 bg-white/15 px-3 py-2.5 text-surface backdrop-blur-sm transition hover:bg-white/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold/60" data-hero-prev aria-label="Previous slide">
    <span class="material-symbols-outlined text-2xl leading-none">chevron_left</span>
  </button>
  <button type="button" class="home-hero-nav absolute right-3 md:right-6 top-1/2 z-20 -translate-y-1/2 rounded-md border border-white/25 bg-white/15 px-3 py-2.5 text-surface backdrop-blur-sm transition hover:bg-white/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold/60" data-hero-next aria-label="Next slide">
    <span class="material-symbols-outlined text-2xl leading-none">chevron_right</span>
  </button>
  <div class="absolute bottom-8 left-6 z-20 flex gap-2 md:left-14 lg:left-24" data-hero-dots role="tablist" aria-label="Hero slides"></div>
  <?php endif; ?>

  <?php if ($hasBookingEmbed): ?>
  <div class="absolute bottom-8 md:bottom-12 left-1/2 -translate-x-1/2 w-full max-w-5xl px-4 md:px-6 z-20">
    <div class="bg-surface/95 backdrop-blur-xl p-2 shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)]">
      <div class="min-w-0 p-3 md:p-4" id="home-booking-embed-slot">
        <?= $booking_widget_html ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <?php if ($heroSliderMultiple): ?>
  <script>
  (function () {
    var root = document.getElementById('home-hero');
    if (!root) return;
    var slides = root.querySelectorAll('[data-hero-slide]');
    var dotsWrap = root.querySelector('[data-hero-dots]');
    var prevBtn = root.querySelector('[data-hero-prev]');
    var nextBtn = root.querySelector('[data-hero-next]');
    if (!slides.length || !dotsWrap) return;
    var n = slides.length;
    var current = 0;
    var autoplayMs = parseInt(root.getAttribute('data-hero-autoplay-ms') || '6000', 10);
    if (autoplayMs < 2000) autoplayMs = 6000;
    var timer = null;

    function setActive(i) {
      current = (i + n) % n;
      slides.forEach(function (el, idx) {
        if (idx === current) {
          el.classList.remove('opacity-0', 'z-0');
          el.classList.add('opacity-100', 'z-[1]');
        } else {
          el.classList.remove('opacity-100', 'z-[1]');
          el.classList.add('opacity-0', 'z-0');
        }
      });
      var dots = dotsWrap.querySelectorAll('[data-hero-dot]');
      dots.forEach(function (d, idx) {
        if (idx === current) {
          d.classList.add('bg-white', 'w-6');
          d.classList.remove('bg-white/40');
          d.setAttribute('aria-selected', 'true');
        } else {
          d.classList.remove('bg-white', 'w-6');
          d.classList.add('bg-white/40');
          d.setAttribute('aria-selected', 'false');
        }
      });
    }

    for (var j = 0; j < n; j++) {
      (function (index) {
        var dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'h-2 rounded-full bg-white/40 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold/70 ' + (index === 0 ? 'w-6 bg-white' : 'w-2');
        dot.setAttribute('data-hero-dot', '');
        dot.setAttribute('role', 'tab');
        dot.setAttribute('aria-label', 'Slide ' + (index + 1));
        dot.setAttribute('aria-selected', index === 0 ? 'true' : 'false');
        dot.addEventListener('click', function () {
          setActive(index);
          restart();
        });
        dotsWrap.appendChild(dot);
      })(j);
    }

    function next() { setActive(current + 1); }
    function prev() { setActive(current - 1); }

    function restart() {
      if (timer) clearInterval(timer);
      timer = setInterval(next, autoplayMs);
    }

    if (prevBtn) prevBtn.addEventListener('click', function () { prev(); restart(); });
    if (nextBtn) nextBtn.addEventListener('click', function () { next(); restart(); });
    restart();
  })();
  </script>
  <?php endif; ?>
</header>

<!-- Brand story -->
<section id="home-about" class="py-24 md:py-32 px-6 md:px-12 max-w-screen-2xl mx-auto bg-surface">
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

<!-- Rooms: horizontal slider — 1 / 2 / 3 cards visible (mobile / tablet / desktop); arrows scroll one viewport; no wheel preventDefault -->
<section class="py-24 md:py-32 bg-surface-container-low">
  <div class="max-w-screen-2xl mx-auto px-6 md:px-12">
    <?php
    $roomsForSlider = is_array($featuredRooms) ? array_slice($featuredRooms, 0, 8) : [];
    $roomsCount = count($roomsForSlider);
    $roomsShowArrows = $roomsCount > 1;
    ?>
    <div class="mb-10 flex flex-col gap-6 md:mb-14 md:flex-row md:items-end md:justify-between">
      <div class="mx-auto max-w-3xl text-center md:mx-0 md:max-w-none md:text-left">
        <?php if (trim($rooms_kicker) !== ''): ?>
        <p class="mb-3 font-body text-xs uppercase tracking-widest text-on-surface-variant"><?= e($rooms_kicker) ?></p>
        <?php endif; ?>
        <h2 class="font-headline text-3xl leading-tight text-on-surface sm:text-4xl md:text-5xl"><?= e($rooms_title) ?></h2>
      </div>
      <?php if ($roomsShowArrows): ?>
      <div class="flex shrink-0 justify-center gap-2 md:justify-end" id="homeRoomsNavWrap" aria-label="Room slider navigation">
        <button type="button" id="homeRoomsPrev" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-outline-variant bg-surface text-on-surface transition-colors hover:border-brand-gold hover:text-brand-gold focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold" aria-label="Previous rooms">
          <span class="material-symbols-outlined text-xl" aria-hidden="true">chevron_left</span>
        </button>
        <button type="button" id="homeRoomsNext" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-outline-variant bg-surface text-on-surface transition-colors hover:border-brand-gold hover:text-brand-gold focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold" aria-label="Next rooms">
          <span class="material-symbols-outlined text-xl" aria-hidden="true">chevron_right</span>
        </button>
      </div>
      <?php endif; ?>
    </div>

    <?php if ($roomsForSlider === []): ?>
    <p class="py-6 text-center font-body text-on-surface-variant md:text-left">No rooms to show yet. Mark rooms as <strong>Featured</strong> in Admin → Rooms.</p>
    <?php else: ?>
    <div class="min-w-0">
      <div
        id="homeRoomsScroller"
        class="home-rooms-slider-track flex min-h-0 w-full snap-x snap-mandatory items-stretch gap-5 overflow-x-auto scroll-smooth pb-4 [-webkit-overflow-scrolling:touch] md:snap-none no-scrollbar"
      >
        <?php foreach ($roomsForSlider as $room):
            $rtitle = (string) ($room['title'] ?? '');
            $rslug = (string) ($room['slug'] ?? '');
            $rprice = is_numeric($room['price'] ?? null) ? number_format((float) $room['price'], 0) : '';
            $rsub = $home_room_subtitle($room);
            $rimgPath = (string) ($room['main_image'] ?? '');
            $rimg = $rimgPath !== '' ? site_media_url($rimgPath) : site_media_url($detailPlaceholder);
            ?>
      <a class="home-rooms-slider-card snap-start flex min-h-[26rem] max-w-full shrink-0 flex-col rounded-xl bg-white text-left shadow-[0_8px_30px_-12px_rgba(0,0,0,0.15)] ring-1 ring-black/[0.06] transition-shadow duration-300 hover:shadow-[0_16px_40px_-12px_rgba(0,0,0,0.2)] focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold focus-visible:ring-offset-2 focus-visible:ring-offset-surface-container-low group" href="<?= e(site_url('room-details', ['slug' => $rslug])) ?>">
        <div class="relative aspect-[4/3] w-full shrink-0 overflow-hidden bg-surface-container-high">
          <img class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= e($rimg) ?>" alt="<?= e($rtitle) ?>" width="480" height="360"/>
          <div class="pointer-events-none absolute inset-0 bg-brand-ink/0 transition-colors duration-300 group-hover:bg-brand-ink/10"></div>
        </div>
        <div class="flex min-h-[9rem] flex-1 flex-col p-5">
          <h3 class="font-headline text-lg leading-snug text-on-surface line-clamp-2 md:text-xl"><?= e($rtitle) ?></h3>
          <?php if ($rsub !== ''): ?>
          <p class="mt-2 line-clamp-2 font-body text-xs uppercase tracking-widest text-on-surface-variant"><?= e($rsub) ?></p>
          <?php endif; ?>
          <div class="mt-auto flex items-end justify-between gap-3 border-t border-outline-variant/15 pt-4">
            <?php if ($rprice !== ''): ?>
            <span class="font-body text-lg font-semibold tabular-nums text-brand-gold"><?= e($currency) ?><?= e($rprice) ?><span class="text-xs font-normal text-on-surface-variant"> / night</span></span>
            <?php else: ?>
            <span></span>
            <?php endif; ?>
            <span class="material-symbols-outlined shrink-0 text-brand-gold opacity-80 transition-transform group-hover:translate-x-0.5" aria-hidden="true">arrow_forward</span>
          </div>
        </div>
      </a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="mt-10 flex justify-center md:justify-end">
      <a class="inline-flex items-center gap-2 font-body text-xs font-bold uppercase tracking-[0.2em] text-brand-gold transition-colors hover:text-on-surface" href="<?= e(site_href((string) $rooms_view_all)) ?>">
        <span>View all suites</span>
        <span class="material-symbols-outlined !text-base">arrow_forward</span>
      </a>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- Dining (background hardcoded for brand consistency) -->
<section id="home-dining" class="py-24 md:py-32 text-surface" style="background-color:#1A1A1A;">
  <div class="px-6 md:px-12 max-w-screen-2xl mx-auto flex flex-col lg:flex-row gap-16 lg:gap-24 items-center">
    <div class="lg:w-1/2 w-full">
      <?php if (trim($dining_kicker) !== ''): ?>
      <span class="font-body uppercase tracking-[0.3em] text-white text-xs mb-6 md:mb-8 block"><?= e($dining_kicker) ?></span>
      <?php endif; ?>
      <div class="font-headline text-4xl md:text-6xl lg:text-7xl mb-10 md:mb-12"><?= $dining_heading_html ?></div>
      <div class="space-y-12 md:space-y-16">
        <div class="border-l-2 border-white pl-6 md:pl-8 py-2">
          <h4 class="font-body text-xl md:text-2xl font-semibold mb-3 md:mb-4 tracking-tight text-white"><?= e($dining_v1_title) ?></h4>
          <p class="font-body text-surface/60 leading-relaxed font-light"><?= e($dining_v1_body) ?></p>
        </div>
        <div class="border-l-2 border-white pl-6 md:pl-8 py-2">
          <h4 class="font-body text-xl md:text-2xl font-semibold mb-3 md:mb-4 tracking-tight text-white"><?= e($dining_v2_title) ?></h4>
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
    <div class="lg:w-2/3 w-full min-h-[320px] md:h-[500px] shadow-xl overflow-hidden rounded-sm bg-surface-container-highest <?= $loc_show_map_embed ? '' : 'grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition-all duration-700' ?>">
      <div class="relative w-full h-full min-h-[320px] md:min-h-[500px] <?= $loc_show_map_embed ? '' : 'p-2 md:p-3 flex' ?>">
        <?php if ($loc_show_map_embed): ?>
        <iframe
          class="absolute inset-0 h-full w-full border-0"
          title="<?= e($loc_title) ?> — map"
          src="<?= e($loc_map_iframe_src) ?>"
          allowfullscreen
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
        ></iframe>
        <?php else: ?>
        <img class="w-full h-full object-cover min-h-[280px] md:min-h-[476px] flex-1" src="<?= e($loc_map) ?>" alt="" width="1200" height="800"/>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<script>
(function () {
  var sc = document.getElementById('homeRoomsScroller');
  var prev = document.getElementById('homeRoomsPrev');
  var next = document.getElementById('homeRoomsNext');
  if (!sc || !prev || !next) return;
  function step(dir) {
    sc.scrollBy({ left: dir * sc.clientWidth, behavior: 'smooth' });
  }
  prev.addEventListener('click', function () {
    step(-1);
  });
  next.addEventListener('click', function () {
    step(1);
  });
})();
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
