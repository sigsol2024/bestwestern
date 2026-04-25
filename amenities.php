<?php
require_once __DIR__ . '/includes/content-loader.php';

$cmsDefaults = require __DIR__ . '/includes/cms-defaults.php';
$pageTitle = getPageSection('amenities', 'page_title', 'Facilities & Amenities');
$ctaTitle = getPageSection('amenities', 'cta_title', 'Ready to Experience Our Facilities?');
$ctaBtnLabel = getPageSection('amenities', 'cta_btn_label', 'Book Your Stay');
$ctaBtnHref = getPageSection('amenities', 'cta_btn_href', '/contact');
$servicesTitle = getPageSection('amenities', 'services_title', 'Signature Guest Services');
$servicesKicker = getPageSection('amenities', 'services_kicker', 'Impeccable Care');
$servicesRaw = (string)getPageSection('amenities', 'services_items_json', '');
$servicesItems = json_decode($servicesRaw, true);
if (!is_array($servicesItems) || $servicesItems === []) {
    $servicesItems = [
        ['title' => '24h Concierge', 'subtitle' => 'Dedicated to your every whim'],
        ['title' => 'Airport Transfer', 'subtitle' => 'Luxury chauffeur fleet'],
        ['title' => 'Laundry & Press', 'subtitle' => 'Same-day valet service'],
        ['title' => 'High-Speed WiFi', 'subtitle' => 'Gigabit fiber throughout'],
        ['title' => 'Secure Parking', 'subtitle' => '24/7 guarded premises'],
        ['title' => 'Room Service', 'subtitle' => 'Global dining 24/7'],
    ];
}
$raw = getPageSection('amenities', 'sections_json', '');
$sections = json_decode($raw, true);
if (!is_array($sections) || $sections === []) {
    $sections = $cmsDefaults['amenities_sections'];
}

$pickSection = static function (int $idx) use ($sections): array {
    return isset($sections[$idx]) && is_array($sections[$idx]) ? $sections[$idx] : [];
};

$hero = $pickSection(0);
$dining1 = $pickSection(1);
$dining2 = $pickSection(2);
$wellness1 = $pickSection(3);
$wellness2 = $pickSection(4);
$wellness3 = $pickSection(5);
$business = $pickSection(6);
$sectionImage = static function (array $sec, string $fallback = ''): string {
    $bg = trim((string)($sec['bg'] ?? ''));
    if ($bg !== '') return $bg;
    if (!empty($sec['gallery'][0]) && is_string($sec['gallery'][0])) return trim((string)$sec['gallery'][0]);
    if (!empty($sec['gallery_images'][0]) && is_string($sec['gallery_images'][0])) return trim((string)$sec['gallery_images'][0]);
    return $fallback;
};

$dining1BreakfastLabel = trim((string)getPageSection('amenities', 'dining1_breakfast_label', 'Breakfast'));
$dining1BreakfastTime = trim((string)getPageSection('amenities', 'dining1_breakfast_time', '06:30 - 10:30'));
$dining1DinnerLabel = trim((string)getPageSection('amenities', 'dining1_dinner_label', 'Dinner'));
$dining1DinnerTime = trim((string)getPageSection('amenities', 'dining1_dinner_time', '18:00 - 22:00'));
$dining1MenuLabel = trim((string)getPageSection('amenities', 'dining1_menu_label', 'View Full Menu'));
$dining1MenuHref = trim((string)getPageSection('amenities', 'dining1_menu_href', '#'));

$dining2ServiceNote = trim((string)getPageSection('amenities', 'dining2_service_note', 'Evening Service Only'));
$dining2Hours = trim((string)getPageSection('amenities', 'dining2_hours', '18:00 - 23:00'));
$dining2CtaLabel = trim((string)getPageSection('amenities', 'dining2_cta_label', 'Book Table'));
$dining2CtaHref = trim((string)getPageSection('amenities', 'dining2_cta_href', '#'));

$loungeKicker = trim((string)getPageSection('amenities', 'lounge_kicker', 'Evening Ambience'));
$loungeTitleHtml = (string)getPageSection('amenities', 'lounge_title_html', 'The Lounge &amp; Bar');
$loungeBody = trim((string)getPageSection('amenities', 'lounge_body', 'Premium spirits, signature cocktails, and live jazz sessions. The perfect venue for winding down or meeting colleagues in a refined atmosphere.'));
$loungeHoursLabel = trim((string)getPageSection('amenities', 'lounge_hours_label', 'Operating Hours'));
$loungeHours = trim((string)getPageSection('amenities', 'lounge_hours', '12:00 - Midnight Daily'));
$loungeImage = trim((string)getPageSection('amenities', 'lounge_image', $sectionImage($dining2)));

$wellnessIntroKicker = trim((string)getPageSection('amenities', 'wellness_intro_kicker', (string)($wellness1['kicker'] ?? 'Rejuvenation')));
$wellnessIntroTitleHtml = (string)getPageSection('amenities', 'wellness_intro_title_html', (string)($wellness1['title_html'] ?? 'The Vitality <span class="italic">Sanctuary</span>'));

$wellnessRows = [$wellness1, $wellness2, $wellness3];
$wellnessRowIds = ['pool', 'gym', 'spa'];
$wellnessRowMeta = [
    [
        'meta_type' => 'split',
        'left_label' => trim((string)getPageSection('amenities', 'wellness1_left_label', 'Hours')),
        'left_value' => trim((string)getPageSection('amenities', 'wellness1_left_value', '06:00 - 22:00')),
        'right_label' => trim((string)getPageSection('amenities', 'wellness1_right_label', 'Amenities')),
        'right_value' => trim((string)getPageSection('amenities', 'wellness1_right_value', 'Poolside Service')),
    ],
    [
        'meta_type' => 'badge',
        'badge_text' => trim((string)getPageSection('amenities', 'wellness2_badge_text', '24 / 7 Access for Residents')),
    ],
    [
        'meta_type' => 'footer',
        'footer_note' => trim((string)getPageSection('amenities', 'wellness3_footer_note', 'Daily 09:00 - 20:00')),
        'footer_link_label' => trim((string)getPageSection('amenities', 'wellness3_link_label', 'Treatments Menu')),
        'footer_link_href' => trim((string)getPageSection('amenities', 'wellness3_link_href', '#')),
    ],
];

$akassaTitle = trim((string)getPageSection('amenities', 'business_akassa_title', 'Akassa Conference Hall'));
$akassaBody = trim((string)getPageSection('amenities', 'business_akassa_body', 'Our premier venue for large-scale summits, product launches, and social galas. Features fully integrated AV systems and cinematic lighting.'));
$akassaCapacityValue = trim((string)getPageSection('amenities', 'business_akassa_capacity_value', '500'));
$akassaCapacityLabel = trim((string)getPageSection('amenities', 'business_akassa_capacity_label', 'Guest Capacity'));
$akassaCtaLabel = trim((string)getPageSection('amenities', 'business_akassa_cta_label', 'Request Inquiry'));
$akassaCtaHref = trim((string)getPageSection('amenities', 'business_akassa_cta_href', '#'));
$chambersRaw = (string)getPageSection('amenities', 'business_chambers_json', '');
$chambers = json_decode($chambersRaw, true);
if (!is_array($chambers) || $chambers === []) {
    $chambers = [
        ['title' => 'Nun Chamber', 'body' => 'Ideal for high-level board meetings and strategic workshops. Ergonomic executive seating and absolute privacy.', 'badge' => '50 Guest Capacity'],
        ['title' => 'Epele Chamber', 'body' => 'A quiet and focused space for breakout sessions, smaller seminars, and intimate corporate presentations.', 'badge' => '30 Guest Capacity'],
        ['title' => 'Business Center', 'body' => 'Full administrative support with high-speed workstations, scanning, and printing services available 24/7.', 'badge' => 'Resident Concierge'],
    ];
}

$hasRenderableLink = static function (string $label, string $href): bool {
    $label = trim($label);
    $href = trim($href);
    return $label !== '' && $href !== '' && $href !== '#';
};
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($pageTitle) ?></title>
  <?php require_once __DIR__ . '/includes/head-header.php'; ?>
  <style>.editorial-grid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:3rem;}</style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-secondary-fixed selection:text-on-secondary-fixed">
<?php require_once __DIR__ . '/includes/header.php'; ?>
<main>
  <section class="relative h-[85vh] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
      <img class="w-full h-full object-cover brightness-[0.85]" src="<?= e($sectionImage($hero)) ?>" alt="<?= e((string)($hero['kicker'] ?? 'Amenities')) ?>">
      <div class="absolute inset-0 bg-gradient-to-t from-[#0B1F3A]/30 to-transparent"></div>
    </div>
    <div class="relative z-10 w-full px-6 md:px-12 lg:px-24 max-w-screen-2xl mx-auto py-24">
      <div class="max-w-4xl">
        <span class="text-secondary uppercase tracking-[0.4em] text-[10px] mb-8 block"><?= e((string)($hero['kicker'] ?? 'The Sovereign Experience')) ?></span>
        <h1 class="text-white font-headline text-6xl md:text-8xl font-light leading-tight mb-10"><?= (string)($hero['title_html'] ?? 'Facilities & Amenities') ?></h1>
        <p class="text-white/90 text-xl max-w-xl leading-relaxed font-light"><?= e((string)($hero['body'] ?? 'Everything you need for business, leisure, and wellness.')) ?></p>
      </div>
    </div>
  </section>

  <section class="py-28 px-12 max-w-screen-2xl mx-auto" id="dining">
    <div class="editorial-grid mb-24 gap-20">
      <div class="col-span-12 lg:col-span-7"><img class="w-full aspect-[4/3] object-cover" src="<?= e($sectionImage($dining1)) ?>" alt="<?= e((string)($dining1['kicker'] ?? 'Dining')) ?>"></div>
      <div class="col-span-12 lg:col-span-5 flex flex-col justify-center md:px-8">
        <span class="text-secondary text-[10px] tracking-[0.3em] uppercase mb-6"><?= e((string)($dining1['kicker'] ?? 'Native Flavors')) ?></span>
        <h2 class="font-headline text-5xl mb-8 font-light leading-tight"><?= (string)($dining1['title_html'] ?? 'Dining Experience') ?></h2>
        <p class="text-on-surface-variant text-lg leading-relaxed mb-10"><?= e((string)($dining1['body'] ?? '')) ?></p>
        <div class="mb-12 p-8 bg-surface-container-low border-l border-secondary/40">
          <div class="flex justify-between mb-4 border-b border-outline-variant/20 pb-4">
            <span class="text-xs uppercase tracking-widest font-semibold opacity-70"><?= e($dining1BreakfastLabel) ?></span>
            <span class="text-xs font-medium"><?= e($dining1BreakfastTime) ?></span>
          </div>
          <div class="flex justify-between pt-2">
            <span class="text-xs uppercase tracking-widest font-semibold opacity-70"><?= e($dining1DinnerLabel) ?></span>
            <span class="text-xs font-medium"><?= e($dining1DinnerTime) ?></span>
          </div>
        </div>
        <?php if ($hasRenderableLink($dining1MenuLabel, $dining1MenuHref)): ?>
        <a class="text-secondary border-b border-secondary/40 pb-2 self-start text-xs uppercase tracking-widest hover:border-secondary transition-all" href="<?= e(site_href($dining1MenuHref)) ?>"><?= e($dining1MenuLabel) ?></a>
        <?php endif; ?>
      </div>
    </div>

    <div class="editorial-grid items-center mb-24 gap-20">
      <div class="col-span-12 lg:col-span-5 flex flex-col md:px-8">
        <span class="text-secondary text-[10px] tracking-[0.3em] uppercase mb-6"><?= e((string)($dining2['kicker'] ?? 'Oriental Mastery')) ?></span>
        <h2 class="font-headline text-5xl mb-8 font-light"><?= (string)($dining2['title_html'] ?? 'Red Lotus') ?></h2>
        <p class="text-on-surface-variant text-lg leading-relaxed mb-10"><?= e((string)($dining2['body'] ?? '')) ?></p>
        <div class="mb-10">
          <span class="text-[10px] uppercase tracking-widest font-bold block mb-2 opacity-50"><?= e($dining2ServiceNote) ?></span>
          <span class="font-headline text-2xl font-light"><?= e($dining2Hours) ?></span>
        </div>
        <?php if ($hasRenderableLink($dining2CtaLabel, $dining2CtaHref)): ?>
        <a class="inline-block bg-[#0B1F3A] text-white px-10 py-5 uppercase tracking-[0.3em] text-[10px] hover:opacity-90 transition-all self-start" href="<?= e(site_href($dining2CtaHref)) ?>"><?= e($dining2CtaLabel) ?></a>
        <?php endif; ?>
      </div>
      <div class="col-span-12 lg:col-span-7"><img class="w-full aspect-[16/9] object-cover" src="<?= e($sectionImage($dining2)) ?>" alt="<?= e((string)($dining2['kicker'] ?? 'Dining')) ?>"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
      <div><img class="w-full h-[600px] object-cover" src="<?= e($loungeImage) ?>" alt="<?= e($loungeKicker !== '' ? $loungeKicker : 'Lounge') ?>"></div>
      <div class="md:px-8 lg:px-16">
        <span class="text-secondary text-[10px] tracking-[0.3em] uppercase mb-6 block"><?= e($loungeKicker) ?></span>
        <h3 class="font-headline text-5xl mb-8 font-light"><?= (string)$loungeTitleHtml ?></h3>
        <p class="text-on-surface-variant text-lg leading-relaxed mb-12"><?= e($loungeBody) ?></p>
        <div class="flex flex-col gap-2">
          <span class="text-[10px] uppercase tracking-[0.3em] font-bold opacity-50"><?= e($loungeHoursLabel) ?></span>
          <span class="font-headline text-2xl font-light"><?= e($loungeHours) ?></span>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-[#0B1F3A] text-white py-28 overflow-hidden" id="wellness">
    <div class="max-w-screen-2xl mx-auto px-12">
      <div class="mb-20 text-center">
        <span class="text-secondary uppercase tracking-[0.4em] text-[10px] mb-6 block"><?= e($wellnessIntroKicker) ?></span>
        <h2 class="font-headline text-6xl md:text-7xl font-light italic"><?= (string)$wellnessIntroTitleHtml ?></h2>
      </div>
      <div class="space-y-24">
        <?php foreach ($wellnessRows as $wi => $sec):
          $rowId = $wellnessRowIds[$wi] ?? '';
        ?>
        <div class="editorial-grid items-center"<?= $rowId !== '' ? ' id="' . e($rowId) . '"' : '' ?>>
          <div class="col-span-12 lg:col-span-7 <?= $wi % 2 ? 'lg:order-2' : '' ?>">
            <img class="w-full aspect-video object-cover" src="<?= e($sectionImage($sec)) ?>" alt="<?= e((string)($sec['kicker'] ?? 'Wellness')) ?>">
          </div>
          <div class="col-span-12 lg:col-span-5 <?= $wi % 2 ? 'lg:order-1' : 'lg:pl-12' ?>">
            <h3 class="font-headline text-4xl mb-6 font-light"><?= (string)($sec['title_html'] ?? 'Feature') ?></h3>
            <p class="text-white/70 font-light text-lg leading-relaxed mb-10"><?= e((string)($sec['body'] ?? '')) ?></p>
            <?php if (($wellnessRowMeta[$wi]['meta_type'] ?? '') === 'split'): ?>
            <div class="flex gap-12 border-t border-white/10 pt-8">
              <div>
                <span class="text-[10px] opacity-40 uppercase block mb-2 tracking-widest"><?= e((string)($wellnessRowMeta[$wi]['left_label'] ?? '')) ?></span>
                <span class="text-secondary font-medium text-lg"><?= e((string)($wellnessRowMeta[$wi]['left_value'] ?? '')) ?></span>
              </div>
              <div>
                <span class="text-[10px] opacity-40 uppercase block mb-2 tracking-widest"><?= e((string)($wellnessRowMeta[$wi]['right_label'] ?? '')) ?></span>
                <span class="text-white text-xs font-light uppercase tracking-widest"><?= e((string)($wellnessRowMeta[$wi]['right_value'] ?? '')) ?></span>
              </div>
            </div>
            <?php elseif (($wellnessRowMeta[$wi]['meta_type'] ?? '') === 'badge'): ?>
            <div class="bg-white/5 p-8 inline-block">
              <span class="text-secondary text-xs font-bold tracking-[0.3em] uppercase"><?= e((string)($wellnessRowMeta[$wi]['badge_text'] ?? '')) ?></span>
            </div>
            <?php elseif (($wellnessRowMeta[$wi]['meta_type'] ?? '') === 'footer'): ?>
            <div class="flex items-center justify-between border-t border-white/10 pt-8">
              <span class="text-xs uppercase tracking-[0.2em] opacity-50"><?= e((string)($wellnessRowMeta[$wi]['footer_note'] ?? '')) ?></span>
              <?php
                $footerLinkLabel = trim((string)($wellnessRowMeta[$wi]['footer_link_label'] ?? ''));
                $footerLinkHref = trim((string)($wellnessRowMeta[$wi]['footer_link_href'] ?? ''));
              ?>
              <?php if ($hasRenderableLink($footerLinkLabel, $footerLinkHref)): ?>
              <a class="text-secondary text-xs uppercase tracking-widest font-bold border-b border-secondary/40 pb-1" href="<?= e(site_href($footerLinkHref)) ?>"><?= e($footerLinkLabel) ?></a>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="py-40 bg-surface" id="business">
    <div class="max-w-screen-2xl mx-auto px-12">
      <div class="flex flex-col md:flex-row justify-between items-end mb-32 gap-12">
        <div class="max-w-xl">
          <span class="text-secondary uppercase tracking-[0.4em] text-[10px] mb-6 block"><?= e((string)($business['kicker'] ?? 'Corporate Excellence')) ?></span>
          <h2 class="font-headline text-6xl font-light"><?= (string)($business['title_html'] ?? 'Meetings & Events') ?></h2>
        </div>
        <p class="text-on-surface-variant max-w-sm text-lg leading-relaxed font-light"><?= e((string)($business['body'] ?? 'Host with confidence in our versatile meeting spaces.')) ?></p>
      </div>
      <div class="space-y-20">
        <div class="editorial-grid items-start gap-12 border-b border-outline-variant/10 pb-20">
          <div class="col-span-12 lg:col-span-8">
            <img class="w-full aspect-[21/9] object-cover" src="<?= e($sectionImage($business)) ?>" alt="<?= e((string)($business['kicker'] ?? 'Business')) ?>">
          </div>
          <div class="col-span-12 lg:col-span-4 flex flex-col h-full justify-center">
            <h3 class="font-headline text-3xl mb-6"><?= e($akassaTitle) ?></h3>
            <p class="text-on-surface-variant text-base mb-8 leading-relaxed"><?= e($akassaBody) ?></p>
            <div class="flex items-baseline gap-4 mb-10">
              <span class="text-4xl font-light italic text-[#0B1F3A]"><?= e($akassaCapacityValue) ?></span>
              <span class="text-[10px] uppercase tracking-widest opacity-60"><?= e($akassaCapacityLabel) ?></span>
            </div>
            <?php if ($hasRenderableLink($akassaCtaLabel, $akassaCtaHref)): ?>
            <a class="px-12 py-5 border border-[#0B1F3A] text-[10px] uppercase tracking-[0.3em] hover:bg-[#0B1F3A] hover:text-white transition-all self-start inline-block" href="<?= e(site_href($akassaCtaHref)) ?>"><?= e($akassaCtaLabel) ?></a>
            <?php endif; ?>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16 mt-10">
          <?php foreach ($chambers as $ch):
            $chTitle = trim((string)($ch['title'] ?? ''));
            if ($chTitle === '') { continue; }
            $chBody = trim((string)($ch['body'] ?? ''));
            $chBadge = trim((string)($ch['badge'] ?? ''));
          ?>
          <div class="p-10 border-l border-outline-variant/20 hover:bg-surface-container-low transition-all">
            <h4 class="font-headline text-2xl mb-4"><?= e($chTitle) ?></h4>
            <p class="text-sm text-on-surface-variant mb-10 leading-relaxed"><?= e($chBody) ?></p>
            <span class="text-secondary text-[10px] font-bold tracking-[0.2em] uppercase"><?= e($chBadge) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="py-[66px] bg-surface-container-low" id="services">
    <div class="max-w-screen-2xl mx-auto px-12">
      <div class="text-center mb-32">
        <span class="text-secondary uppercase tracking-[0.4em] text-[10px] mb-6 block"><?= e($servicesKicker) ?></span>
        <h2 class="font-headline text-5xl font-light italic"><?= e($servicesTitle) ?></h2>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-x-24 gap-y-24">
        <?php foreach ($servicesItems as $srv):
          $st = trim((string)($srv['title'] ?? ''));
          if ($st === '') { continue; }
          $sb = trim((string)($srv['subtitle'] ?? $srv['body'] ?? ''));
        ?>
        <div class="text-center">
          <span class="font-headline text-2xl mb-4 block"><?= e($st) ?></span>
          <?php if ($sb !== ''): ?>
          <p class="text-[10px] text-on-surface-variant uppercase tracking-[0.3em] leading-relaxed"><?= e($sb) ?></p>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
