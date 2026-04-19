<?php
require_once __DIR__ . '/includes/content-loader.php';

$heroPlaceholder = cms_default_setting('placeholder_hero_image');
$detailPlaceholder = cms_default_setting('placeholder_detail_image');
$galleryPlaceholder = cms_default_setting('placeholder_gallery_image');

$pageTitle = getSiteSetting('site_name', cms_default_setting('site_name'));

$hero_kicker = getPageSection('index', 'hero_kicker', 'Lorem Ipsum');
$hero_title = normalize_home_hero_title_html(
    (string) getPageSection('index', 'hero_title', 'Lorem Ipsum <br/><span class="italic text-primary site-hero-accent-text">Dolor Sit</span>')
);
$hero_subtitle = getPageSection('index', 'hero_subtitle', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
$hero_cta_text = getPageSection('index', 'hero_cta_text', 'Explore Rooms');
$hero_cta_href = getPageSection('index', 'hero_cta_href', '/rooms');
$hero_bg = getPageSection('index', 'hero_bg', $heroPlaceholder);

$hero_bg_slides_raw = getPageSection('index', 'hero_bg_slides', '');
$hero_slide_paths = [];
if (trim((string)$hero_bg_slides_raw) !== '') {
    $decodedSlides = json_decode((string)$hero_bg_slides_raw, true);
    if (is_array($decodedSlides)) {
        foreach ($decodedSlides as $p) {
            if (is_string($p) && trim($p) !== '') {
                $hero_slide_paths[] = trim($p);
            }
        }
    }
}
if (count($hero_slide_paths) === 0) {
    $hero_slide_paths = [$hero_bg];
}

$hero_youtube_url = getPageSection('index', 'hero_youtube_url', '');
$youtubeVideoId = '';
if (trim((string)$hero_youtube_url) !== '') {
    // watch?v=, youtu.be/, embed/, shorts/
    if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', (string)$hero_youtube_url, $m)) {
        $youtubeVideoId = $m[1];
    }
}
$hasHeroYoutube = ($youtubeVideoId !== '');

// Booking / calendar embed (paste HTML from admin homepage editor — bridges hero → next section)
$booking_widget_html = getPageSection('index', 'booking_widget_html', '');
$hasBookingBridge = trim((string)$booking_widget_html) !== '';
$bookingWrapperId = preg_replace('/[^A-Za-z0-9_-]/', '', (string)getSiteSetting('booking_wrapper_id', cms_default_setting('booking_wrapper_id')));
if ($bookingWrapperId === '') {
    $bookingWrapperId = cms_default_setting('booking_wrapper_id');
}
$bookingWrapperSelector = '#' . $bookingWrapperId;

$hp_kicker = getPageSection('index', 'home_philosophy_kicker', 'Lorem Ipsum');
$hp_title_html = getPageSection('index', 'home_philosophy_title_html', "Lorem Ipsum <br/> Dolor Sit");
$hp_body = getPageSection('index', 'home_philosophy_body', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
$hp_link_text = getPageSection('index', 'home_philosophy_link_text', 'Learn More');
$hp_link_href = getPageSection('index', 'home_philosophy_link_href', '/about');
$hp_main_img = getPageSection('index', 'home_philosophy_main_img', $detailPlaceholder);
$hp_secondary_img = getPageSection('index', 'home_philosophy_secondary_img', $galleryPlaceholder);

$arch_img = getPageSection('index', 'home_arch_image', $heroPlaceholder);
$arch_badge_title = getPageSection('index', 'home_arch_badge_title', 'Lorem');
$arch_badge_sub = getPageSection('index', 'home_arch_badge_sub', 'Ipsum');
$arch_title = getPageSection('index', 'home_arch_title', 'Dolor Sit Amet');
$arch_body = getPageSection('index', 'home_arch_body', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
$arch_li1 = getPageSection('index', 'home_arch_list_1', 'Lorem ipsum dolor');
$arch_li2 = getPageSection('index', 'home_arch_list_2', 'Sit amet consectetur');

$rooms_kicker = getPageSection('index', 'home_rooms_kicker', 'Accommodations');
$rooms_title = getPageSection('index', 'home_rooms_title', 'Lorem Rooms');
$rooms_view_all = getPageSection('index', 'home_rooms_view_all_href', '/rooms');

$dining_img = getPageSection('index', 'home_dining_image', $galleryPlaceholder);
$dining_kicker = getPageSection('index', 'home_dining_kicker', 'Dining');
$dining_title = getPageSection('index', 'home_dining_title', 'Lorem Dining');
$dining_body_html = getPageSection('index', 'home_dining_body_html', 'Lorem ipsum dolor sit amet, <span class="text-text-main font-semibold">consectetur adipiscing</span> elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
$dining_cta1 = getPageSection('index', 'home_dining_cta1', 'Learn More');
$dining_cta1_href = getPageSection('index', 'home_dining_cta1_href', '/dining');
$dining_cta2 = getPageSection('index', 'home_dining_cta2', 'View Details');
$dining_cta2_href = getPageSection('index', 'home_dining_cta2_href', '/dining');

// Homepage “amenities highlight” — three image cards (not news). New keys: home_amenity_grid_* ; legacy home_news_* still read if new keys empty.
$amenity_grid_kicker = trim((string)getPageSection('index', 'home_amenity_grid_kicker', ''));
if ($amenity_grid_kicker === '') {
    $amenity_grid_kicker = trim((string)getPageSection('index', 'home_news_kicker', ''));
}
$amenity_grid_title = trim((string)getPageSection('index', 'home_amenity_grid_title', ''));
if ($amenity_grid_title === '') {
    $amenity_grid_title = trim((string)getPageSection('index', 'home_news_title', ''));
}
$amenity_grid_intro = trim((string)getPageSection('index', 'home_amenity_grid_intro', ''));
$home_amenity_grid_cards = [];
for ($ni = 1; $ni <= 3; $ni++) {
    $img = trim((string)getPageSection('index', 'home_amenity_grid_' . $ni . '_image', ''));
    $ttl = trim((string)getPageSection('index', 'home_amenity_grid_' . $ni . '_title', ''));
    $desc = trim((string)getPageSection('index', 'home_amenity_grid_' . $ni . '_description', ''));
    if ($img === '') {
        $img = trim((string)getPageSection('index', 'home_news_' . $ni . '_image', ''));
    }
    if ($ttl === '') {
        $ttl = trim((string)getPageSection('index', 'home_news_' . $ni . '_title', ''));
    }
    if ($desc === '') {
        $desc = trim((string)getPageSection('index', 'home_news_' . $ni . '_description', ''));
    }
    $home_amenity_grid_cards[] = [
        'image' => $img,
        'title' => $ttl,
        'description' => $desc,
    ];
}
$hasAmenityGridCard = false;
foreach ($home_amenity_grid_cards as $gc) {
    if ($gc['image'] !== '' || $gc['title'] !== '' || $gc['description'] !== '') {
        $hasAmenityGridCard = true;
        break;
    }
}
$showAmenityGridSection = ($amenity_grid_kicker !== '' || $amenity_grid_title !== '' || $amenity_grid_intro !== '' || $hasAmenityGridCard);

$currency = getSiteSetting('currency_symbol', '$');
$featuredRooms = getFeaturedRoomsForHome(5);
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($pageTitle) ?></title>
  <?php require_once __DIR__ . '/includes/head-header.php'; ?>
  <?php if (!empty($hasBookingBridge)): ?>
  <style>
    /* Booking embed — NO extra wrapper from us.
       We style either the provider wrapper (configured in settings) if present,
       or the provider widget/form ids directly. */
    <?= $bookingWrapperSelector ?>,
    #booking-widget,
    #booking-form,
    <?= $bookingWrapperSelector ?> #booking-form {
      position: relative;
      z-index: 60;
      max-width: 72rem; /* ~max-w-6xl */
      margin-left: auto;
      margin-right: auto;
      /* Pull upward to “bridge” hero → next section (use transform to avoid margin-collapsing) */
      margin-top: 0.5rem;
      transform: translateY(-2.25rem) !important;
      margin-bottom: 2rem;
      width: 100% !important;
      padding: 14px 16px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.96);
      border: 1px solid rgba(0, 0, 0, 0.07);
      box-shadow: 0 8px 28px rgba(0, 0, 0, 0.07);
      overflow-x: auto;
    }
    @media (min-width: 1024px) {
      <?= $bookingWrapperSelector ?>,
      #booking-widget,
      #booking-form,
      <?= $bookingWrapperSelector ?> #booking-form {
        padding-left: 3rem; padding-right: 3rem; /* ~lg:px-12 */
      }
    }
    @media (min-width: 768px) {
      <?= $bookingWrapperSelector ?>,
      #booking-widget,
      #booking-form,
      <?= $bookingWrapperSelector ?> #booking-form {
        margin-top: 0.75rem;
        transform: translateY(-2.75rem) !important;
        margin-bottom: 2.5rem;
      }
    }
    <?= $bookingWrapperSelector ?> *,
    #booking-widget *,
    #booking-form * { box-sizing: border-box; }
    <?= $bookingWrapperSelector ?> #booking-widget {
      margin: 0 !important; padding: 0 !important; border: 0 !important;
      box-shadow: none !important; border-radius: 0 !important;
      background: transparent !important; max-width: none !important; width: 100% !important;
    }
    <?= $bookingWrapperSelector ?> #booking-form {
      display: flex !important; flex-wrap: wrap !important; gap: 10px !important;
      align-items: flex-end !important; justify-content: space-between !important;
      padding: 0 !important; margin: 0 !important; width: 100% !important;
    }
    /* If the provider does not include the outer wrapper, style the provider ids directly. */
    #booking-form {
      display: flex !important; flex-wrap: wrap !important; gap: 10px !important;
      align-items: flex-end !important; justify-content: space-between !important;
    }
    <?= $bookingWrapperSelector ?> #booking-form > div {
      width: auto !important; min-width: 140px !important;
      flex: 1 1 160px !important; margin: 0 !important; padding: 0 !important;
    }
    #booking-form > div {
      width: auto !important; min-width: 140px !important;
      flex: 1 1 160px !important; margin: 0 !important; padding: 0 !important;
    }
    <?= $bookingWrapperSelector ?> #booking-form label {
      font-size: 11px !important; font-weight: 700 !important;
      letter-spacing: 0.06em !important; text-transform: uppercase !important;
      margin-bottom: 6px !important; color: #363636 !important;
    }
    #booking-form label {
      font-size: 11px !important; font-weight: 700 !important;
      letter-spacing: 0.06em !important; text-transform: uppercase !important;
      margin-bottom: 6px !important; color: #363636 !important;
    }
    <?= $bookingWrapperSelector ?> #booking-form input {
      width: 100% !important; min-height: 44px !important;
      padding: 10px 12px !important; border: 1px solid #d8d0bc !important;
      border-radius: 10px !important; background: #fff !important; color: #363636 !important;
    }
    #booking-form input,
    #booking-form select {
      width: 100% !important; min-height: 44px !important;
      padding: 10px 12px !important; border: 1px solid #d8d0bc !important;
      border-radius: 10px !important; background: #fff !important; color: #363636 !important;
    }
    <?= $bookingWrapperSelector ?> #booking-form button {
      width: 100% !important; min-height: 44px !important; margin-top: 0 !important;
      border: 0 !important; border-radius: 10px !important;
      background: #411d13 !important; color: #fff !important;
      font-weight: 700 !important; cursor: pointer !important;
    }
    #booking-form button {
      width: 100% !important; min-height: 44px !important; margin-top: 0 !important;
      border: 0 !important; border-radius: 10px !important;
      background: #411d13 !important; color: #fff !important;
      font-weight: 700 !important; cursor: pointer !important;
    }
    <?= $bookingWrapperSelector ?> #booking-form button:hover {
      background: #5a2a1f !important;
    }
    #booking-form button:hover { background: #5a2a1f !important; }
    @media (max-width: 1024px) {
      <?= $bookingWrapperSelector ?> #booking-form {
        flex-direction: column !important; align-items: stretch !important;
      }
      #booking-form {
        flex-direction: column !important; align-items: stretch !important;
      }
      <?= $bookingWrapperSelector ?> #booking-form > div {
        min-width: 100% !important; flex: 1 1 100% !important;
      }
      #booking-form > div {
        min-width: 100% !important; flex: 1 1 100% !important;
      }
    }
  </style>
  <?php endif; ?>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-main font-display antialiased overflow-x-hidden selection:bg-primary/30">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<style>
  /* Homepage hero YouTube background (cover-fit like a background image) */
  #site-hero-youtube {
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    width: 100vw !important;
    height: 56.25vw !important; /* 16:9 */
    min-width: 177.77vh !important;
    min-height: 100vh !important;
    transform: translate(-50%, -50%) !important;
    pointer-events: none !important;
  }
  /* In-viewport reveal animations (lightweight, no dependencies) */
  @media (prefers-reduced-motion: reduce) {
    [data-site-inview] { opacity: 1 !important; transform: none !important; transition: none !important; animation: none !important; }
  }
  [data-site-inview] { opacity: 0; transform: translate3d(0, 0, 0); }
  [data-site-inview].site-inview--on { opacity: 1; }

  /* Slide up slowly */
  [data-site-inview="up-slow"] { transform: translate3d(0, 28px, 0); }
  [data-site-inview="up-slow"].site-inview--on {
    transform: translate3d(0, 0, 0);
    transition: transform var(--site-duration, 1400ms) ease-out, opacity 900ms ease-out;
    transition-delay: var(--site-delay, 0ms);
  }

  /* Fade in from left */
  [data-site-inview="left-fade"] { transform: translate3d(-28px, 0, 0); }
  [data-site-inview="left-fade"].site-inview--on {
    transform: translate3d(0, 0, 0);
    transition: transform var(--site-duration, 700ms) cubic-bezier(.2,.9,.2,1), opacity 700ms ease-out;
    transition-delay: var(--site-delay, 0ms);
  }
</style>

<section class="relative z-0">
  <!-- Cinematic hero section -->
  <header class="relative w-full h-screen min-h-[600px] overflow-hidden group" id="site-home-hero">
    <div class="absolute inset-0 overflow-hidden">
      <?php if ($hasHeroYoutube): ?>
        <?php $heroPosterUrl = site_media_url($hero_slide_paths[0] ?? $hero_bg); ?>
        <div class="absolute inset-0 bg-cover bg-center z-0" data-alt="Hero background" style="background-image: url('<?= e($heroPosterUrl) ?>');"></div>
        <iframe
          id="site-hero-youtube"
          class="z-[1]"
          src="https://www.youtube-nocookie.com/embed/<?= e($youtubeVideoId) ?>?autoplay=1&mute=1&controls=0&loop=1&playlist=<?= e($youtubeVideoId) ?>&modestbranding=1&rel=0&playsinline=1"
          title="Hero video"
          frameborder="0"
          loading="eager"
          referrerpolicy="strict-origin-when-cross-origin"
          allow="autoplay; encrypted-media; picture-in-picture"
        ></iframe>
      <?php else: ?>
        <?php foreach ($hero_slide_paths as $si => $slidePath): ?>
          <?php $slideUrl = site_media_url($slidePath); ?>
        <div class="hero-bg-slide absolute inset-0 bg-cover bg-center transition-opacity duration-[1200ms] ease-in-out group-hover:scale-105 <?= $si === 0 ? 'opacity-100 z-[1]' : 'opacity-0 z-0' ?>"
             data-slide-index="<?= (int)$si ?>"
             data-alt="Hero background"
             style="background-image: url('<?= e($slideUrl) ?>');">
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/40 to-black/75 z-[5] pointer-events-none"></div>

    <div class="relative h-full flex flex-col justify-center items-center text-center px-6 z-10">
      <h2 class="text-white/90 text-sm md:text-base font-medium uppercase tracking-[0.2em] mb-4 animate-[fadeIn_1s_ease-out]">
        <?= e($hero_kicker) ?>
      </h2>
      <h1 class="font-serif text-[1.75rem] leading-snug sm:text-4xl sm:leading-tight md:text-7xl lg:text-8xl text-white font-medium mb-5 sm:mb-6 md:mb-8 max-w-4xl px-1 sm:px-0 text-cinematic animate-[fadeIn_1s_ease-out_0.2s] drop-shadow-md [&_.site-hero-accent-text]:animate-none">
        <?= $hero_title ?>
      </h1>
      <p class="text-white/90 text-lg md:text-xl font-light max-w-xl mb-10 animate-[fadeIn_1s_ease-out_0.4s] drop-shadow-sm">
        <?= e($hero_subtitle) ?>
      </p>
      <div class="animate-[fadeIn_1s_ease-out_0.6s]">
        <a class="inline-block min-w-[200px] px-8 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-white bg-white/[0.08] hover:bg-white/[0.18] backdrop-blur-xl border border-white/35 ring-1 ring-white/20 shadow-[0_8px_32px_rgba(0,0,0,0.18)] transition-all duration-300"
           href="<?= e(site_href((string)$hero_cta_href)) ?>">
          <?= e($hero_cta_text) ?>
        </a>
      </div>
    </div>
  </header>
  <script>
  (function () {
    var root = document.getElementById('site-home-hero');
    if (!root) return;
    var accent = root.querySelector('.site-hero-accent-text');
    if (accent) {
      var full = (accent.textContent || '').trim();
      if (full.length > 0) {
        accent.textContent = '';
        accent.setAttribute('aria-busy', 'true');
        var i = 0;
        function tick() {
          if (i <= full.length) {
            accent.textContent = full.slice(0, i);
            i++;
            window.setTimeout(tick, i < 3 ? 120 : 55);
          } else {
            accent.removeAttribute('aria-busy');
          }
        }
        window.setTimeout(tick, 500);
      }
    }
  })();
  </script>
  <?php if (!$hasHeroYoutube && count($hero_slide_paths) > 1): ?>
  <script>
  (function () {
    var root = document.getElementById('site-home-hero');
    if (!root) return;
    var slides = root.querySelectorAll('.hero-bg-slide');
    if (slides.length < 2) return;
    var cur = 0;
    var n = slides.length;
    setInterval(function () {
      slides[cur].classList.remove('opacity-100', 'z-[1]');
      slides[cur].classList.add('opacity-0', 'z-0');
      cur = (cur + 1) % n;
      slides[cur].classList.remove('opacity-0', 'z-0');
      slides[cur].classList.add('opacity-100', 'z-[1]');
    }, 7000);
  })();
  </script>
  <?php endif; ?>
</section>

<?php if ($hasBookingBridge): ?>
<!-- Booking bridge: render provider HTML as-is (no wrapper) -->
<?= $booking_widget_html ?>
<?php endif; ?>

<!-- Asymmetrical editorial section -->
<section class="relative z-10 w-full <?= $hasBookingBridge ? 'pt-8 md:pt-12 pb-[28px] md:pb-[46px]' : 'py-24 md:py-32' ?> overflow-x-hidden lg:overflow-visible bg-background-light">
  <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: radial-gradient(#363636 1px, transparent 1px); background-size: 32px 32px;"></div>
  <div class="max-w-[1280px] mx-auto px-6 lg:px-12">
    <div class="flex flex-col lg:flex-row items-stretch lg:items-start gap-12 lg:gap-0 mb-32 relative">
      <div class="w-full lg:w-1/2 lg:pr-16 z-20 pt-10">
        <span class="block text-primary text-sm font-bold uppercase tracking-widest mb-4"><?= e($hp_kicker) ?></span>
        <h2 class="font-serif text-4xl md:text-5xl lg:text-6xl text-text-main leading-tight mb-6">
          <?= $hp_title_html ?>
        </h2>
        <div class="w-16 h-[2px] bg-primary mb-8"></div>
        <p class="text-text-muted text-lg leading-relaxed mb-8 max-w-md">
          <?= e($hp_body) ?>
        </p>
        <a class="inline-flex items-center gap-2 text-text-main font-semibold border-b border-primary pb-1 hover:text-primary transition-colors" href="<?= e(site_href((string)$hp_link_href)) ?>">
          <?= e($hp_link_text) ?> <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
      </div>
      <div class="w-full lg:w-1/2 relative min-w-0 flex flex-col items-center gap-8 lg:block">
        <div class="absolute top-[-20px] right-[-20px] w-full h-full border border-primary/30 rounded-lg hidden lg:block z-0"></div>
        <div class="relative z-10 w-full max-w-md mx-auto lg:ml-auto lg:mr-0 rounded-lg overflow-hidden shadow-2xl aspect-[4/5] lg:aspect-[3/4]">
          <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hover:scale-105 transition-transform duration-700" data-alt="Editorial"
               style="background-image: url('<?= e($hp_main_img) ?>');"></div>
        </div>
        <div class="relative lg:absolute w-full max-w-[280px] aspect-[4/5] min-h-[187px] mx-auto -mt-16 sm:-mt-28 lg:mt-0 lg:max-w-none lg:w-48 lg:h-64 lg:aspect-auto lg:mx-0 left-auto lg:left-[-40px] bottom-auto lg:bottom-[-40px] rounded-lg overflow-hidden shadow-xl z-20 border-4 border-white"
             data-site-inview="up-slow" style="--site-duration: 2200ms; --site-delay: 80ms;">
          <div class="absolute inset-0 bg-cover bg-center bg-no-repeat m-0 p-0" style="background-image: url('<?= e($hp_secondary_img) ?>');"></div>
        </div>
      </div>
    </div>

    <div class="flex flex-col-reverse lg:flex-row items-stretch lg:items-center gap-12 lg:gap-24 relative">
      <div class="w-full lg:w-7/12 relative min-w-0">
        <div class="relative rounded-lg overflow-hidden shadow-elevation aspect-video w-full">
          <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" data-alt="Architecture"
               style="background-image: url('<?= e($arch_img) ?>');"></div>
        </div>
        <div class="absolute -top-6 -right-6 bg-white p-6 shadow-xl rounded-lg max-w-[200px] hidden lg:block">
          <p class="font-serif text-2xl text-primary font-bold"><?= e($arch_badge_title) ?></p>
          <p class="text-xs text-text-muted uppercase tracking-wider mt-1"><?= e($arch_badge_sub) ?></p>
        </div>
      </div>
      <div class="w-full lg:w-5/12 z-20 min-w-0 p-0 mt-[-83px] mb-[-83px]">
        <h3 class="font-serif text-3xl md:text-4xl text-text-main mb-4"><?= e($arch_title) ?></h3>
        <p class="text-text-muted text-lg leading-relaxed mb-6">
          <?= e($arch_body) ?>
        </p>
        <ul class="space-y-3 mb-[52px]">
          <li class="flex items-center gap-3 text-text-main">
            <span class="material-symbols-outlined text-primary">check_circle</span>
            <span><?= e($arch_li1) ?></span>
          </li>
          <li class="flex items-center gap-3 text-text-main">
            <span class="material-symbols-outlined text-primary">check_circle</span>
            <span><?= e($arch_li2) ?></span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php if ($showAmenityGridSection): ?>
<!-- Amenities highlight: three cards, dark band (#282828) -->
<section class="py-[23px] md:py-[26px] border-t border-white/5" style="background-color:#282828;">
  <div class="max-w-[1280px] mx-auto px-6 lg:px-12">
    <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16">
      <?php if ($amenity_grid_kicker !== ''): ?>
      <span class="text-white/65 text-xs md:text-sm font-bold uppercase tracking-[0.2em]"><?= e($amenity_grid_kicker) ?></span>
      <?php endif; ?>
      <?php if ($amenity_grid_title !== ''): ?>
      <h2 class="font-serif text-3xl md:text-5xl text-white mt-3 md:mt-4 mb-5 leading-tight"><?= e($amenity_grid_title) ?></h2>
      <?php endif; ?>
      <div class="w-14 h-1 rounded-full mx-auto mb-6" style="background:linear-gradient(90deg, transparent, #c9a227, transparent);"></div>
      <?php if ($amenity_grid_intro !== ''): ?>
      <p class="text-white/75 text-base md:text-lg leading-relaxed"><?= e($amenity_grid_intro) ?></p>
      <?php endif; ?>
    </div>
    <?php if ($hasAmenityGridCard): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
      <?php foreach ($home_amenity_grid_cards as $ac):
          if (trim($ac['image'] . $ac['title'] . $ac['description']) === '') {
              continue;
          }
          $aimg = $ac['image'] !== '' ? site_media_url($ac['image']) : '';
          static $amenityCardIndex = 0;
          $amenityCardIndex++;
          // First card completes fastest, next cards slightly slower.
          $dur = $amenityCardIndex === 1 ? 650 : ($amenityCardIndex === 2 ? 850 : 1050);
          $delay = $amenityCardIndex === 1 ? 0 : ($amenityCardIndex === 2 ? 120 : 240);
          ?>
      <article class="flex flex-col rounded-xl overflow-hidden border border-white/10 bg-white/[0.04] shadow-lg shadow-black/20"
               data-site-inview="left-fade" style="--site-duration: <?= (int)$dur ?>ms; --site-delay: <?= (int)$delay ?>ms;">
        <div class="aspect-[4/3] bg-white/5 bg-cover bg-center" data-alt="<?= e($ac['title']) ?>"
             <?php if ($aimg !== ''): ?>style="background-image: url('<?= e($aimg) ?>');"<?php endif; ?>></div>
        <div class="p-6 md:p-7 flex-1 flex flex-col">
          <?php if ($ac['title'] !== ''): ?>
          <h3 class="font-serif text-xl md:text-2xl text-white mb-3 leading-snug"><?= e($ac['title']) ?></h3>
          <?php endif; ?>
          <?php if ($ac['description'] !== ''): ?>
          <p class="text-white/70 text-sm md:text-base leading-relaxed flex-1"><?= e($ac['description']) ?></p>
          <?php endif; ?>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="text-center mt-10 md:mt-12">
      <a class="inline-flex items-center justify-center px-7 py-3.5 rounded-full border border-white/20 text-white font-bold tracking-wide uppercase text-xs hover:bg-white/10 transition-colors"
         href="<?= e(site_href('/amenities')) ?>">
        View all amenities
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Featured rooms (CMS: mark rooms Featured in admin) -->
<section class="py-[33px] bg-white relative">
  <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
    <?php
      $roomsForSlider = is_array($featuredRooms) ? array_slice($featuredRooms, 0, 5) : [];
      $showRoomsNav = count($roomsForSlider) > 4;
    ?>
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
      <div>
        <span class="text-primary text-sm font-bold uppercase tracking-widest"><?= e($rooms_kicker) ?></span>
        <h2 class="font-serif text-4xl md:text-5xl text-text-main mt-3"><?= e($rooms_title) ?></h2>
      </div>
      <?php if ($showRoomsNav): ?>
      <div class="flex gap-2">
        <button type="button" id="homeRoomsPrev" class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center hover:bg-primary hover:border-primary hover:text-white transition-all" aria-label="Scroll rooms left">
          <span class="material-symbols-outlined">arrow_back</span>
        </button>
        <button type="button" id="homeRoomsNext" class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center hover:bg-primary hover:border-primary hover:text-white transition-all" aria-label="Scroll rooms right">
          <span class="material-symbols-outlined">arrow_forward</span>
        </button>
      </div>
      <?php endif; ?>
    </div>

    <div id="homeRoomsScroller" class="flex overflow-x-auto gap-8 pb-12 pt-4 px-2 no-scrollbar snap-x snap-mandatory scroll-smooth">
      <?php if (empty($roomsForSlider)): ?>
        <p class="text-text-muted py-8">No rooms to show yet. Add rooms and mark them as <strong>Featured</strong> in Admin → Rooms.</p>
      <?php else: ?>
        <?php foreach ($roomsForSlider as $room):
          $rtitle = (string)($room['title'] ?? '');
          $rslug = (string)($room['slug'] ?? '');
          $rprice = is_numeric($room['price'] ?? null) ? number_format((float)$room['price'], 0) : '';
          $rdesc = (string)($room['short_description'] ?? '');
          if ($rdesc === '') {
              $rdesc = (string)($room['description'] ?? '');
          }
          $rdesc = preg_replace('/\s+/', ' ', strip_tags($rdesc));
          if (function_exists('mb_substr')) {
              $rdesc = mb_strlen($rdesc) > 140 ? mb_substr($rdesc, 0, 137) . '…' : $rdesc;
          } else {
              $rdesc = strlen($rdesc) > 140 ? substr($rdesc, 0, 137) . '…' : $rdesc;
          }
          $rimg = (string)($room['main_image'] ?? '');
          ?>
      <div class="min-w-[320px] md:min-w-[400px] snap-center group">
        <div class="relative h-[500px] rounded-xl overflow-hidden shadow-lg transition-all duration-500 group-hover:shadow-2xl group-hover:-translate-y-2">
          <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110"
               data-alt="<?= e($rtitle) ?>"
               style="background-image: url('<?= e($rimg) ?>');"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/45 to-transparent pointer-events-none"></div>
          <div class="absolute bottom-0 left-0 p-8 w-full">
            <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-end mb-2">
              <h3 class="font-serif text-2xl text-white"><?= e($rtitle) ?></h3>
              <span class="inline-flex w-fit self-start sm:self-auto text-white font-bold bg-white/10 backdrop-blur-sm border border-white/30 rounded px-3 py-1 shadow-sm">
                <?= e($currency) ?><?= e($rprice) ?>/n
              </span>
            </div>
            <p class="text-white/80 text-sm mb-6 line-clamp-2"><?= e($rdesc) ?></p>
            <a class="inline-flex items-center gap-2 text-white text-sm font-bold uppercase tracking-wider border-b border-white/35 pb-1 hover:border-white transition-colors text-center"
               href="<?= e(site_url('room-details', ['slug' => $rslug])) ?>">
              View Details
              <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
            </a>
          </div>
        </div>
      </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="mt-4 flex items-center justify-start">
      <a class="inline-flex items-center gap-2 text-text-muted hover:text-primary transition-colors"
         href="<?= e(site_href((string)$rooms_view_all)) ?>">
        <span class="w-10 h-10 rounded-full border border-current flex items-center justify-center">
          <span class="material-symbols-outlined">arrow_forward</span>
        </span>
        <span class="text-sm font-medium">View All</span>
      </a>
    </div>
  </div>
</section>

<script>
(function () {
  var sc = document.getElementById('homeRoomsScroller');
  var prev = document.getElementById('homeRoomsPrev');
  var next = document.getElementById('homeRoomsNext');
  if (!sc) return;
  if (!prev || !next) return;
  var step = function (dir) {
    sc.scrollBy({ left: dir * Math.min(420, sc.clientWidth * 0.85), behavior: 'smooth' });
  };
  prev.addEventListener('click', function () { step(-1); });
  next.addEventListener('click', function () { step(1); });
})();
</script>

<script>
(function () {
  function onReady(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }
  onReady(function () {
    var els = Array.prototype.slice.call(document.querySelectorAll('[data-site-inview]'));
    if (!els.length) return;
    if (!('IntersectionObserver' in window)) {
      els.forEach(function (el) { el.classList.add('site-inview--on'); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('site-inview--on');
          io.unobserve(entry.target);
        }
      });
    }, { root: null, threshold: 0.18, rootMargin: '0px 0px -10% 0px' });
    els.forEach(function (el) { io.observe(el); });
  });
})();
</script>

<!-- Culinary Excellence Section -->
<section class="py-[34px] bg-background-light">
  <div class="max-w-[1280px] mx-auto px-6 lg:px-12">
    <div class="bg-white rounded-2xl overflow-hidden shadow-elevation flex flex-col lg:flex-row items-stretch">
      <div class="w-full lg:w-1/2 relative min-h-[260px] md:min-h-[400px]">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" data-alt="Dining"
             style="background-image: url('<?= e($dining_img) ?>');"></div>
      </div>
      <div class="w-full lg:w-1/2 p-12 lg:p-20 flex flex-col justify-center min-w-0">
        <span class="text-primary text-sm font-bold uppercase tracking-widest mb-3"><?= e($dining_kicker) ?></span>
        <h2 class="font-serif text-4xl text-text-main mb-6"><?= e($dining_title) ?></h2>
        <p class="text-text-muted text-lg mb-8">
          <?= $dining_body_html ?>
        </p>
        <div class="flex flex-row flex-nowrap gap-2 sm:gap-4 w-full min-w-0">
          <a class="flex-1 min-w-0 inline-flex items-center justify-center text-center px-3 py-2.5 sm:px-8 sm:py-3 rounded-md text-xs sm:text-sm font-bold tracking-wide transition-colors shadow-md shadow-primary/20 bg-primary text-white hover:bg-primary-light" href="<?= e(site_href((string)$dining_cta1_href)) ?>"><?= e($dining_cta1) ?></a>
          <a class="flex-1 min-w-0 inline-flex items-center justify-center text-center px-3 py-2.5 sm:px-8 sm:py-3 rounded-md text-xs sm:text-sm font-bold tracking-wide transition-colors bg-transparent border border-text-muted/30 text-text-main hover:border-text-main" href="<?= e(site_href((string)$dining_cta2_href)) ?>"><?= e($dining_cta2) ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
