<?php
require_once __DIR__ . '/includes/content-loader.php';

$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    header('Location: ' . site_url('rooms'));
    exit;
}

$room = getRoomBySlug($slug);
if (!$room) {
    header('Location: ' . site_url('rooms'));
    exit;
}

$GLOBALS['site_header_overlaps_hero'] = true;

$siteName = getSiteSetting('site_name', cms_default_setting('site_name'));
$whatsappLink = getSiteSetting('whatsapp_link', '');
$whatsappNumber = preg_replace('/[^0-9]/', '', (string)getSiteSetting('whatsapp_number', ''));
$heroBadge = getSiteSetting('room_detail_hero_badge', cms_default_setting('room_detail_hero_badge'));

$title = (string)($room['title'] ?? '');
$roomType = trim((string)($room['room_type'] ?? ''));
$pageTitle = $title ? ($title . ' - ' . $siteName) : ('Room Details - ' . $siteName);

$mainImage = (string)($room['main_image'] ?? '');
$images = [];
if ($mainImage !== '') {
    $images[] = $mainImage;
}
if (!empty($room['gallery_images']) && is_array($room['gallery_images'])) {
    foreach ($room['gallery_images'] as $img) {
        if (is_string($img) && trim($img) !== '') {
            $images[] = trim($img);
        }
    }
}
$images = array_values(array_unique($images));

$stickyImage = $images[1] ?? $images[0] ?? '';
$mobileDividerImage = $images[2] ?? $images[1] ?? '';

$priceRaw = is_numeric($room['price'] ?? null) ? (float)$room['price'] : null;
$price = $priceRaw !== null ? number_format($priceRaw, 0) : '';
$currency = getSiteSetting('currency_symbol', '$');

$description = trim((string)($room['description'] ?? ''));
$short = trim((string)($room['short_description'] ?? ''));
$paras = preg_split('/\r?\n\s*\r?\n/', $description, -1, PREG_SPLIT_NO_EMPTY);
$spacePara = isset($paras[0]) ? trim($paras[0]) : $description;
$experiencePara = isset($paras[1]) ? trim($paras[1]) : ($short !== '' ? $short : $spacePara);

$conceptQuote = $short;
if ($conceptQuote !== '' && !preg_match('/^["«]/u', $conceptQuote)) {
    $conceptQuote = '"' . $conceptQuote . '"';
}

$size = trim((string)($room['size'] ?? ''));
$maxGuests = (int)($room['max_guests'] ?? 0);

$featuresRaw = $room['features'] ?? [];
$features = [];
$bed = '';
$view = '';
if (is_array($featuresRaw)) {
    foreach ($featuresRaw as $f) {
        if (is_string($f) && trim($f) !== '') {
            $features[] = trim($f);
        }
        if (is_array($f) && !empty($f['title'])) {
            $features[] = trim((string)$f['title']);
        }
    }
}
foreach ($features as $f) {
    $lf = strtolower($f);
    if ($bed === '' && (str_contains($lf, 'bed') || str_contains($lf, 'king'))) {
        $bed = $f;
    }
    if ($view === '' && str_contains($lf, 'view')) {
        $view = $f;
    }
    if ($size === '' && (str_contains($lf, 'sqm') || str_contains($lf, 'sqft') || str_contains($lf, 'm²'))) {
        $size = $f;
    }
}

$featureChips = array_values(array_filter(array_map(static function ($s) {
    return is_string($s) ? trim($s) : '';
}, $features), static fn ($x) => $x !== ''));
$featureChips = array_values(array_unique($featureChips));

$includedItems = is_array($room['included_items'] ?? null) ? $room['included_items'] : [];
$includedItems = array_values(array_filter(array_map(function ($i) {
    return is_string($i) ? trim($i) : '';
}, $includedItems), fn ($x) => $x !== ''));

$gk = $room['good_to_know'] ?? [];
if (is_string($gk)) {
    $decodedGk = json_decode($gk, true);
    $gk = is_array($decodedGk) ? $decodedGk : [];
} elseif (!is_array($gk)) {
    $gk = [];
}

$expHeading = trim((string)($gk['experience_heading'] ?? '')) ?: 'The Experience';
$whoHeading = trim((string)($gk['who_heading'] ?? '')) ?: "Who it's for";
$viewHeading = trim((string)($gk['view_heading'] ?? '')) ?: 'The View';
$whoBody = trim((string)($gk['who_body'] ?? ''));
$viewBody = trim((string)($gk['view_body'] ?? ''));
$testimonialQuote = trim((string)($gk['testimonial_quote'] ?? ''));
$testimonialBy = trim((string)($gk['testimonial_by'] ?? ''));
$floorPlanUrl = trim((string)($gk['floor_plan_url'] ?? ''));
$bookingBadge = trim((string)($gk['booking_badge'] ?? ''));
$rateLabel = trim((string)($gk['rate_label'] ?? '')) ?: 'Standard Rate';
$panelFootnote = trim((string)($gk['panel_footnote'] ?? ''));
$trendingMessage = trim((string)($gk['trending_message'] ?? ''));
$bookingGuestsDefault = trim((string)($gk['booking_guests_default'] ?? ''));
$bookingTrustLine = trim((string)($gk['booking_trust_line'] ?? ''));
$bookingTrustSubline = trim((string)($gk['booking_trust_subline'] ?? ''));

$urgencyMessage = trim((string)($room['urgency_message'] ?? ''));

$amenitiesRaw = is_array($room['amenities'] ?? null) ? $room['amenities'] : [];
$amenityCards = [];
foreach ($amenitiesRaw as $a) {
    if (is_string($a) && trim($a) !== '') {
        $amenityCards[] = ['icon' => 'check_circle', 'title' => trim($a), 'desc' => 'Included'];
    } elseif (is_array($a)) {
        $t = trim((string)($a['title'] ?? $a['name'] ?? ''));
        if ($t === '') {
            continue;
        }
        $amenityCards[] = [
            'icon' => trim((string)($a['icon'] ?? 'check_circle')),
            'title' => $t,
            'desc' => trim((string)($a['description'] ?? $a['desc'] ?? 'Included')),
        ];
    }
}

$bookUrl = htmlspecialchars_decode((string)($room['book_url'] ?? ''), ENT_QUOTES);
$bookUrl = trim($bookUrl);
if ($bookUrl === '') {
    $bookUrl = htmlspecialchars_decode((string)$whatsappLink, ENT_QUOTES);
    $bookUrl = trim($bookUrl);
}
if ($bookUrl === '' && $whatsappNumber !== '') {
    $bookUrl = 'https://wa.me/' . $whatsappNumber;
}
if ($bookUrl === '') {
    $bookUrl = site_url('contact');
}

$occupancyLabel = $maxGuests > 0
    ? 'Up to ' . $maxGuests . ' guest' . ($maxGuests > 1 ? 's' : '')
    : '—';
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($pageTitle) ?></title>
  <?php require_once __DIR__ . '/includes/head-header.php'; ?>
  <style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .slider-dot.active { background-color: #fff; width: 2rem; }
  </style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-secondary-container selection:text-on-secondary-container overflow-x-hidden">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<main>
  <section class="relative min-h-[70vh] h-[80vh] max-h-[900px] w-full overflow-hidden group">
    <div class="flex h-full w-full overflow-x-auto snap-x snap-mandatory hide-scrollbar" id="hero-slider">
      <?php foreach ($images as $img): ?>
      <div class="flex-none w-full h-full snap-start relative">
        <img class="w-full h-full object-cover" src="<?= e($img) ?>" alt="<?= e($title) ?>">
        <div class="absolute inset-0 bg-black/35"></div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if (count($images) > 1): ?>
    <button type="button" class="absolute left-10 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center text-white/50 hover:text-white transition-colors z-20 group-hover:opacity-100 opacity-0 duration-500" id="heroPrevBtn">
      <span class="material-symbols-outlined !text-4xl font-extralight">west</span>
    </button>
    <button type="button" class="absolute right-10 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center text-white/50 hover:text-white transition-colors z-20 group-hover:opacity-100 opacity-0 duration-500" id="heroNextBtn">
      <span class="material-symbols-outlined !text-4xl font-extralight">east</span>
    </button>
    <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex gap-4 z-20" id="heroDotsWrap"></div>
    <?php endif; ?>

    <div class="absolute inset-0 pointer-events-none flex items-end pb-16 md:pb-24 pt-24 md:pt-28 px-6 md:px-12 max-w-screen-2xl mx-auto left-0 right-0">
      <div class="max-w-2xl text-white pointer-events-auto">
        <span class="font-body uppercase tracking-[0.5em] text-[10px] font-bold mb-4 block opacity-80"><?= e($heroBadge) ?></span>
        <h1 class="font-headline text-3xl sm:text-4xl md:text-5xl leading-tight mb-6"><?= e($title) ?></h1>
        <div class="flex items-center gap-8">
          <a class="text-[10px] font-bold tracking-[0.3em] uppercase border-b border-white/30 pb-1 hover:border-white transition-all" href="#suiteDetails">View Details</a>
        </div>
      </div>
    </div>
  </section>

  <div id="suiteDetails" class="max-w-screen-2xl mx-auto px-6 md:px-12 py-24 grid grid-cols-12 gap-16">
    <div class="col-span-12 lg:col-span-8 space-y-24">
      <div class="flex flex-wrap items-center gap-x-8 gap-y-4 py-8 border-y border-outline-variant/20">
        <div class="text-[11px] uppercase tracking-[0.3em] font-bold text-primary"><?= e($size !== '' ? $size : 'Suite') ?></div>
        <div class="w-px h-4 bg-outline-variant/50 hidden md:block"></div>
        <div class="text-[11px] uppercase tracking-[0.3em] font-bold text-primary"><?= e($view !== '' ? $view : 'Premium View') ?></div>
        <div class="w-px h-4 bg-outline-variant/50 hidden md:block"></div>
        <div class="text-[11px] uppercase tracking-[0.3em] font-bold text-primary"><?= e($occupancyLabel) ?></div>
      </div>

      <div class="grid md:grid-cols-3 gap-12">
        <div>
          <h3 class="font-headline text-2xl mb-4 italic text-primary"><?= e($expHeading) ?></h3>
          <p class="text-on-surface-variant text-[14px] leading-relaxed font-light"><?= nl2br(e($experiencePara !== '' ? $experiencePara : $spacePara)) ?></p>
        </div>
        <div>
          <h3 class="font-headline text-2xl mb-4 italic text-primary">The Space</h3>
          <p class="text-on-surface-variant text-[14px] leading-relaxed font-light"><?= nl2br(e($spacePara !== '' ? $spacePara : $description)) ?></p>
        </div>
        <div>
          <h3 class="font-headline text-2xl mb-4 italic text-primary"><?= e($viewBody !== '' ? $viewHeading : 'Essentials') ?></h3>
          <?php if ($viewBody !== ''): ?>
          <p class="text-on-surface-variant text-[14px] leading-relaxed font-light"><?= nl2br(e($viewBody)) ?></p>
          <?php else: ?>
          <p class="text-on-surface-variant text-[14px] leading-relaxed font-light"><?= e($bed !== '' ? $bed : 'King Bed') ?> • <?= e($size !== '' ? $size : 'Luxury Suite') ?></p>
          <?php endif; ?>
        </div>
      </div>

      <?php if ($whoBody !== '' || $floorPlanUrl !== ''): ?>
      <div class="grid md:grid-cols-2 gap-10 items-start">
        <?php if ($whoBody !== ''): ?>
        <div>
          <h3 class="font-headline text-2xl mb-4 italic text-primary"><?= e($whoHeading) ?></h3>
          <p class="text-on-surface-variant text-[14px] leading-relaxed font-light"><?= nl2br(e($whoBody)) ?></p>
        </div>
        <?php endif; ?>
        <?php if ($floorPlanUrl !== ''): ?>
        <div class="md:text-right">
          <a class="inline-flex items-center gap-2 text-[10px] font-bold tracking-[0.3em] uppercase border-b border-primary pb-1 text-primary hover:text-secondary hover:border-secondary transition-colors" href="<?= e($floorPlanUrl) ?>" target="_blank" rel="noopener noreferrer">
            <span class="material-symbols-outlined text-[18px]">download</span>
            Floor plan
          </a>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <?php if ($testimonialQuote !== ''): ?>
      <blockquote class="border-l-2 border-primary pl-8 py-2">
        <p class="font-headline text-2xl md:text-3xl italic text-primary leading-snug"><?= nl2br(e($testimonialQuote)) ?></p>
        <?php if ($testimonialBy !== ''): ?>
        <footer class="mt-6 text-[11px] uppercase tracking-[0.25em] text-secondary font-bold"><?= e($testimonialBy) ?></footer>
        <?php endif; ?>
      </blockquote>
      <?php endif; ?>

      <?php if (!empty($amenityCards)): ?>
      <div class="bg-primary/5 p-12">
        <div class="flex justify-between items-end mb-10">
          <h3 class="font-headline text-3xl italic text-primary">Refined Essentials</h3>
          <div class="text-[10px] uppercase tracking-widest text-secondary font-bold"><?= e($bookingBadge !== '' ? $bookingBadge : 'Best Western Plus Collection') ?></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
          <?php foreach (array_slice($amenityCards, 0, 12) as $card): ?>
          <div class="space-y-4">
            <h4 class="text-[10px] uppercase tracking-[0.2em] font-bold text-secondary"><?= e($card['title']) ?></h4>
            <p class="text-xs text-on-surface-variant font-light"><?= e($card['desc'] !== '' ? $card['desc'] : 'Included') ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <div class="col-span-12 lg:col-span-4">
      <div class="sticky top-32 bg-white border border-outline-variant/20 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] p-10">
        <?php if ($bookingBadge !== ''): ?>
        <div class="mb-4 text-[10px] uppercase tracking-[0.35em] font-bold text-secondary"><?= e($bookingBadge) ?></div>
        <?php endif; ?>
        <div class="mb-8">
          <div class="flex justify-between items-start mb-2">
            <span class="text-on-surface-variant text-[10px] uppercase tracking-[0.2em] font-bold"><?= e($rateLabel) ?></span>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="font-headline text-4xl font-bold text-primary"><?= e($currency) ?><?= e($price !== '' ? $price : '—') ?></span>
            <span class="text-[10px] text-on-surface-variant uppercase tracking-widest font-bold">/ night</span>
          </div>
        </div>
        <?php if ($urgencyMessage !== ''): ?>
        <div class="mb-8 flex items-center gap-2 text-error text-[11px] font-bold italic">
          <span class="material-symbols-outlined text-[16px]">error</span>
          <?= e($urgencyMessage) ?>
        </div>
        <?php endif; ?>
        <?php if ($trendingMessage !== ''): ?>
        <div class="mb-8 flex items-center gap-2 text-primary/60 text-[11px] font-medium">
          <span class="material-symbols-outlined text-[16px]">trending_up</span>
          <?= e($trendingMessage) ?>
        </div>
        <?php endif; ?>
        <form class="space-y-6 mb-6" onsubmit="event.preventDefault(); window.location.href='<?= e($bookUrl) ?>';">
          <div class="border border-outline-variant/30 p-5 bg-white">
            <label class="block text-[9px] uppercase tracking-widest font-bold mb-2 opacity-50">Guests</label>
            <input class="w-full text-xs border-none p-0 focus:ring-0 font-medium" type="text" value="<?= e($bookingGuestsDefault !== '' ? $bookingGuestsDefault : ($maxGuests > 0 ? ($maxGuests . ' Adults') : '2 Adults')) ?>">
          </div>
          <button class="w-full inline-flex items-center justify-center bg-primary text-white py-5 text-[11px] font-bold tracking-[0.4em] hover:bg-secondary transition-all uppercase shadow-lg shadow-primary/20" type="submit">Reserve Suite</button>
        </form>
        <?php if ($bookingTrustLine !== '' || $bookingTrustSubline !== ''): ?>
        <div class="pt-4 border-t border-outline-variant/10 space-y-2">
          <?php if ($bookingTrustLine !== ''): ?>
          <div class="flex items-center justify-center gap-2 text-[10px] text-on-surface-variant font-medium">
            <span class="material-symbols-outlined text-[14px] text-green-600">check_circle</span>
            <?= e($bookingTrustLine) ?>
          </div>
          <?php endif; ?>
          <?php if ($bookingTrustSubline !== ''): ?>
          <p class="text-[10px] text-center text-on-surface-variant/60 italic"><?= e($bookingTrustSubline) ?></p>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if ($panelFootnote !== ''): ?>
        <p class="mt-6 text-[11px] text-on-surface-variant leading-relaxed font-light"><?= nl2br(e($panelFootnote)) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php
    $similarRooms = getFeaturedRoomsForHome(6);
    if (!is_array($similarRooms)) {
        $similarRooms = [];
    }
    $similarRooms = array_values(array_filter($similarRooms, static function ($r) use ($slug) {
        return (string)($r['slug'] ?? '') !== (string)$slug;
    }));
  ?>
  <?php if ($similarRooms !== []): ?>
  <section class="bg-surface-container-low py-24 px-6 md:px-12 border-t border-outline-variant/20">
    <div class="max-w-screen-2xl mx-auto">
      <div class="flex justify-between items-end mb-12">
        <div>
          <span class="text-secondary text-[10px] uppercase tracking-[0.5em] font-bold mb-4 block">Selection</span>
          <h2 class="font-headline text-4xl md:text-5xl italic text-primary">Similar Accommodations</h2>
        </div>
        <a class="text-[10px] font-bold tracking-[0.3em] border-b border-primary pb-1 text-primary hover:text-secondary hover:border-secondary transition-colors" href="<?= e(site_url('rooms')) ?>">VIEW ALL ROOMS</a>
      </div>
      <div class="grid md:grid-cols-3 gap-10">
        <?php foreach (array_slice($similarRooms, 0, 3) as $sr):
            $st = (string)($sr['title'] ?? '');
            $ss = (string)($sr['slug'] ?? '');
            $sp = is_numeric($sr['price'] ?? null) ? number_format((float)$sr['price'], 0) : '';
            $si = (string)($sr['main_image'] ?? '');
            if ($si === '') { continue; }
        ?>
        <a class="group cursor-pointer" href="<?= e(site_url('room-details', ['slug' => $ss])) ?>">
          <div class="aspect-[4/5] overflow-hidden mb-6 relative">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000" src="<?= e($si) ?>" alt="<?= e($st) ?>">
            <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-colors"></div>
            <div class="absolute bottom-8 left-8 right-8 text-white">
              <h4 class="font-headline text-3xl mb-2"><?= e($st) ?></h4>
              <span class="text-[10px] uppercase tracking-widest font-light opacity-80">From <?= e($currency) ?><?= e($sp) ?></span>
            </div>
          </div>
          <span class="text-[10px] font-bold tracking-[0.3em] text-secondary border-b border-transparent hover:border-secondary transition-all">DISCOVER ROOM</span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>
</main>

<script>
(function () {
  var slider = document.getElementById('hero-slider');
  if (!slider) return;
  var slides = slider.children.length;
  if (slides < 2) return;
  var dotsWrap = document.getElementById('heroDotsWrap');
  var prev = document.getElementById('heroPrevBtn');
  var next = document.getElementById('heroNextBtn');
  var idx = 0;
  var autoMs = 6000;
  var timer = null;
  var dots = [];
  function go(i) {
    idx = ((i % slides) + slides) % slides;
    slider.scrollTo({ left: slider.clientWidth * idx, behavior: 'smooth' });
    dots.forEach(function (d, di) { d.classList.toggle('active', di === idx); });
  }
  function restartAuto() {
    if (timer) {
      clearInterval(timer);
    }
    timer = setInterval(function () { go(idx + 1); }, autoMs);
  }
  for (var i = 0; i < slides; i++) {
    var b = document.createElement('button');
    b.type = 'button';
    b.className = 'slider-dot w-2 h-2 rounded-full bg-white/40 transition-all duration-300';
    (function (j) { b.addEventListener('click', function () { go(j); }); })(i);
    dotsWrap.appendChild(b);
    dots.push(b);
  }
  if (prev) prev.addEventListener('click', function () { go(idx - 1); restartAuto(); });
  if (next) next.addEventListener('click', function () { go(idx + 1); restartAuto(); });
  slider.addEventListener('scroll', function () {
    var current = Math.round(slider.scrollLeft / Math.max(1, slider.clientWidth));
    if (current !== idx) {
      idx = current;
      dots.forEach(function (d, di) { d.classList.toggle('active', di === idx); });
    }
  });
  go(0);
  restartAuto();
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
