<?php
require_once __DIR__ . '/includes/content-loader.php';

$heroPlaceholder = cms_default_setting('placeholder_hero_image');

$pageTitle = getPageSection('rooms', 'page_title', 'Rooms & Suites');
$heroTitle = getPageSection('rooms', 'hero_title', 'Rooms & Suites');
$heroSubtitle = getPageSection('rooms', 'hero_subtitle', 'Sanctuaries of comfort on the shores of Oxbow Lake');
$heroBg = getPageSection('rooms', 'hero_bg', $heroPlaceholder);
$heroKicker = getPageSection('rooms', 'hero_kicker', 'Accommodations');
$compareLabel = getPageSection('rooms', 'compare_label', 'Compare all rooms');
$amenitiesReminderTitle = getPageSection('rooms', 'amenities_reminder_title', 'All suites include:');
$amenitiesReminderItemsRaw = (string)getPageSection('rooms', 'amenities_reminder_items_json', '["WIFI","BREAKFAST","TOILETRIES","TURNDOWN"]');
$amenitiesReminderItems = json_decode($amenitiesReminderItemsRaw, true);
if (!is_array($amenitiesReminderItems) || $amenitiesReminderItems === []) {
    $amenitiesReminderItems = ['WIFI', 'BREAKFAST', 'TOILETRIES', 'TURNDOWN'];
}
$finalCtaTitle = getPageSection('rooms', 'final_cta_title', 'Need help choosing?');
$finalCtaBody = getPageSection('rooms', 'final_cta_body', 'Our dedicated concierge is available 24/7 to help you select the perfect sanctuary for your stay in Yenagoa.');
$finalCtaLabel = getPageSection('rooms', 'final_cta_label', 'Contact Reservations');
$finalCtaHref = getPageSection('rooms', 'final_cta_href', '/contact');

$bookingCheckinLabel = getPageSection('rooms', 'booking_checkin_label', 'Check-in');
$bookingCheckinValue = getPageSection('rooms', 'booking_checkin_value', 'Dec 14, 2024');
$bookingCheckoutLabel = getPageSection('rooms', 'booking_checkout_label', 'Check-out');
$bookingCheckoutValue = getPageSection('rooms', 'booking_checkout_value', 'Dec 18, 2024');
$bookingGuestsLabel = getPageSection('rooms', 'booking_guests_label', 'Guests');
$bookingGuestsValue = getPageSection('rooms', 'booking_guests_value', '2 Adults, 1 Room');
$bookingCtaLabel = getPageSection('rooms', 'booking_cta_label', 'Check Availability');
$signatureBadge = getPageSection('rooms', 'signature_badge', 'Signature Suite');
$signatureKicker = getPageSection('rooms', 'signature_kicker', 'The Pinnacle of Living');

$currency = getSiteSetting('currency_symbol', '$');
$rooms = getRooms(['is_active' => 1]);
if (!is_array($rooms)) {
    $rooms = [];
}
$roomCount = count($rooms);

$signatureRoom = null;
foreach ($rooms as $r) {
    if ((int)($r['is_featured'] ?? 0) === 1) {
        $signatureRoom = $r;
        break;
    }
}
if ($signatureRoom === null && $rooms !== []) {
    $signatureRoom = $rooms[0];
}

$listRooms = array_values(array_filter($rooms, static function ($room) use ($signatureRoom) {
    if (!$signatureRoom) {
        return true;
    }
    return (int)($room['id'] ?? 0) !== (int)($signatureRoom['id'] ?? -1);
}));
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($pageTitle) ?></title>
  <?php require_once __DIR__ . '/includes/head-header.php'; ?>
</head>
<body class="bg-surface text-on-background font-body selection:bg-secondary-container overflow-x-hidden">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<main>
  <section class="relative w-full h-[600px] overflow-hidden">
    <img class="absolute inset-0 w-full h-full object-cover" src="<?= e($heroBg) ?>" alt="Rooms hero">
    <div class="absolute inset-0 bg-primary/40"></div>
    <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-6 md:px-12">
      <h1 class="font-headline italic text-6xl md:text-7xl text-white mb-4"><?= e($heroTitle) ?></h1>
      <p class="font-body text-lg text-white/90 max-w-2xl font-light tracking-wide mb-12"><?= e($heroSubtitle) ?></p>
      <div class="bg-white p-2 rounded-sm shadow-2xl flex flex-col md:flex-row items-stretch md:items-center w-full max-w-4xl divide-y md:divide-y-0 md:divide-x divide-outline-variant/30">
        <div class="flex-1 px-8 py-4 text-left">
          <span class="block text-[9px] uppercase tracking-widest text-outline mb-1"><?= e($bookingCheckinLabel) ?></span>
          <span class="text-sm font-medium text-primary"><?= e($bookingCheckinValue) ?></span>
        </div>
        <div class="flex-1 px-8 py-4 text-left">
          <span class="block text-[9px] uppercase tracking-widest text-outline mb-1"><?= e($bookingCheckoutLabel) ?></span>
          <span class="text-sm font-medium text-primary"><?= e($bookingCheckoutValue) ?></span>
        </div>
        <div class="flex-1 px-8 py-4 text-left">
          <span class="block text-[9px] uppercase tracking-widest text-outline mb-1"><?= e($bookingGuestsLabel) ?></span>
          <span class="text-sm font-medium text-primary"><?= e($bookingGuestsValue) ?></span>
        </div>
        <div class="px-4 py-2 flex items-center">
          <a class="bg-secondary text-white px-8 py-4 text-[10px] font-bold uppercase tracking-widest hover:bg-primary transition-colors whitespace-nowrap" href="<?= e(site_href($finalCtaHref)) ?>">
            <?= e($bookingCtaLabel) ?>
          </a>
        </div>
      </div>
    </div>
  </section>

  <section class="border-b border-outline-variant/30 bg-surface-container-low">
    <div class="max-w-[1440px] mx-auto px-12 py-8 flex flex-col md:flex-row justify-between items-center gap-6">
      <div class="flex items-center space-x-8">
        <span class="text-[10px] uppercase tracking-[0.2em] text-primary font-bold"><?= (int)$roomCount ?> Suites Available</span>
        <div class="h-4 w-px bg-outline-variant/50"></div>
        <div class="flex items-center space-x-4">
          <span class="text-[9px] uppercase tracking-widest text-outline">Sort by:</span>
          <span class="text-secondary font-bold text-[10px] uppercase tracking-widest border-b border-secondary pb-1">Recommended</span>
          <span class="text-primary/60 text-[10px] uppercase tracking-widest">Price</span>
          <span class="text-primary/60 text-[10px] uppercase tracking-widest">Size</span>
        </div>
      </div>
      <a class="text-secondary text-[10px] uppercase tracking-widest font-bold flex items-center gap-2 group border-b border-transparent hover:border-secondary transition-all" href="<?= e(site_url('rooms')) ?>">
        <?= e($compareLabel) ?>
      </a>
    </div>
  </section>

  <section class="max-w-[1440px] mx-auto px-12 py-24 space-y-40">
    <?php foreach ($listRooms as $idx => $room):
        $title = (string)($room['title'] ?? '');
        $slug = (string)($room['slug'] ?? '');
        $price = is_numeric($room['price'] ?? null) ? number_format((float)$room['price'], 0) : '';
        $desc = trim((string)($room['short_description'] ?? ''));
        if ($desc === '') { $desc = trim((string)($room['description'] ?? '')); }
        $desc = preg_replace('/\s+/', ' ', strip_tags($desc));
        $features = is_array($room['features'] ?? null) ? $room['features'] : [];
        $size = trim((string)($room['size'] ?? ''));
        $view = trim((string)($room['location'] ?? ''));
        $facts = array_filter([
            $size !== '' ? $size : null,
            isset($room['max_guests']) && (int)$room['max_guests'] > 0 ? ((int)$room['max_guests'] . ' ADULTS') : null,
            isset($features[0]) && is_string($features[0]) ? strtoupper(trim((string)$features[0])) : null,
            $view !== '' ? strtoupper($view) : null,
        ]);
        $factLine = $facts !== [] ? implode(' • ', $facts) : 'Luxury Suite';
        $img = (string)($room['main_image'] ?? '');
        $img = $img !== '' ? $img : $heroPlaceholder;
        $reverse = ($idx % 2) === 1;
    ?>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-center">
      <div class="md:col-span-7 aspect-[16/10] overflow-hidden <?= $reverse ? 'md:order-2' : '' ?>">
        <img class="w-full h-full object-cover grayscale-[20%] hover:grayscale-0 transition-all duration-700" src="<?= e($img) ?>" alt="<?= e($title) ?>">
      </div>
      <div class="md:col-span-5 space-y-6 <?= $reverse ? 'md:order-1' : '' ?>">
        <div class="space-y-1">
          <span class="text-[9px] uppercase tracking-[0.3em] text-secondary font-bold"><?= e($heroKicker) ?></span>
          <h2 class="text-5xl font-headline text-primary"><?= e($title) ?></h2>
        </div>
        <div class="text-[10px] tracking-[0.15em] text-outline font-medium uppercase"><?= e($factLine) ?></div>
        <p class="text-on-surface-variant font-light leading-relaxed max-w-md"><?= e($desc) ?></p>
        <div class="text-2xl font-headline text-primary"><?= e($currency) ?><?= e($price) ?> <span class="text-[10px] font-body text-outline tracking-widest uppercase align-middle ml-2">/night</span></div>
        <div class="pt-4">
          <a class="inline-block bg-secondary text-white px-10 py-4 uppercase tracking-[0.2em] text-[10px] font-bold hover:bg-primary transition-all" href="<?= e(site_url('room-details', ['slug' => $slug])) ?>">View Suite</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <?php if ($signatureRoom): ?>
    <?php
      $st = (string)($signatureRoom['title'] ?? '');
      $ss = (string)($signatureRoom['slug'] ?? '');
      $sp = is_numeric($signatureRoom['price'] ?? null) ? number_format((float)$signatureRoom['price'], 0) : '';
      $sd = trim((string)($signatureRoom['short_description'] ?? ''));
      if ($sd === '') { $sd = trim((string)($signatureRoom['description'] ?? '')); }
      $sd = preg_replace('/\s+/', ' ', strip_tags($sd));
      $si = trim((string)($signatureRoom['main_image'] ?? ''));
      if ($si === '') { $si = $heroPlaceholder; }
      $sFacts = [];
      if (trim((string)($signatureRoom['size'] ?? '')) !== '') $sFacts[] = trim((string)$signatureRoom['size']);
      if ((int)($signatureRoom['max_guests'] ?? 0) > 0) $sFacts[] = (int)$signatureRoom['max_guests'] . ' ADULTS';
      $sfRaw = is_array($signatureRoom['features'] ?? null) ? $signatureRoom['features'] : [];
      foreach ($sfRaw as $sf) {
          if (is_string($sf) && trim($sf) !== '') { $sFacts[] = strtoupper(trim($sf)); }
      }
      $sFacts = array_values(array_unique($sFacts));
    ?>
    <div class="relative border border-secondary/20 p-1 md:p-2 bg-white">
      <div class="absolute top-10 right-10 z-20">
        <span class="bg-secondary text-white px-4 py-1.5 text-[9px] uppercase tracking-[0.3em] font-bold shadow-lg"><?= e($signatureBadge) ?></span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 bg-surface-container-low items-center">
        <div class="aspect-[4/3] md:aspect-auto h-full overflow-hidden">
          <img class="w-full h-full object-cover" src="<?= e($si) ?>" alt="<?= e($st) ?>">
        </div>
        <div class="p-12 md:p-20 space-y-8">
          <div class="space-y-4">
            <span class="text-[10px] uppercase tracking-[0.4em] text-secondary font-bold block"><?= e($signatureKicker) ?></span>
            <h2 class="text-5xl md:text-6xl font-headline text-primary leading-tight"><?= e($st) ?></h2>
          </div>
          <div class="text-[10px] tracking-[0.2em] text-primary/60 font-bold uppercase"><?= e(implode(' • ', array_slice($sFacts, 0, 4))) ?></div>
          <p class="text-on-surface-variant font-light leading-relaxed max-w-lg italic"><?= e($sd) ?></p>
          <div class="text-4xl font-headline text-primary"><?= e($currency) ?><?= e($sp) ?> <span class="text-[10px] font-body text-outline tracking-widest uppercase align-middle ml-2">/night</span></div>
          <div class="pt-4">
            <a class="bg-primary text-white px-12 py-5 uppercase tracking-[0.3em] text-[10px] font-bold hover:bg-secondary transition-all inline-block" href="<?= e(site_url('room-details', ['slug' => $ss])) ?>">View Suite</a>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </section>

  <section class="bg-surface-container py-24">
    <div class="max-w-[1440px] mx-auto px-12">
      <div class="flex flex-col md:flex-row items-baseline gap-10 md:gap-20">
        <h3 class="font-headline italic text-3xl text-primary whitespace-nowrap"><?= e($amenitiesReminderTitle) ?></h3>
        <div class="flex flex-wrap gap-x-10 gap-y-5 text-[11px] uppercase tracking-[0.3em] font-bold text-primary/60">
          <?php foreach ($amenitiesReminderItems as $ri): ?>
            <span class="flex items-center gap-4"><?= e((string)$ri) ?> <span class="w-6 h-px bg-secondary"></span></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="py-40 text-center bg-white">
    <div class="max-w-2xl mx-auto px-6 space-y-8">
      <h3 class="text-4xl font-headline text-primary"><?= e($finalCtaTitle) ?></h3>
      <p class="text-on-surface-variant font-light tracking-wide leading-relaxed"><?= e($finalCtaBody) ?></p>
      <div class="pt-4">
        <a class="inline-block text-secondary border-b-2 border-secondary pb-3 text-[11px] tracking-[0.3em] uppercase font-bold hover:text-primary hover:border-primary transition-all" href="<?= e(site_href($finalCtaHref)) ?>">
          <?= e($finalCtaLabel) ?>
        </a>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
