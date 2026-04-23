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
    <div class="relative z-10 w-full px-12 max-w-screen-2xl mx-auto py-24">
      <div class="max-w-4xl">
        <span class="text-secondary uppercase tracking-[0.4em] text-[10px] mb-8 block"><?= e((string)($hero['kicker'] ?? 'The Sovereign Experience')) ?></span>
        <h1 class="text-white font-headline text-7xl md:text-9xl font-light leading-tight mb-10"><?= (string)($hero['title_html'] ?? 'Facilities & Amenities') ?></h1>
        <p class="text-white/90 text-xl max-w-xl leading-relaxed font-light"><?= e((string)($hero['body'] ?? 'Everything you need for business, leisure, and wellness.')) ?></p>
      </div>
    </div>
  </section>

  <section class="py-40 px-12 max-w-screen-2xl mx-auto" id="dining">
    <div class="editorial-grid mb-48 gap-20">
      <div class="col-span-12 lg:col-span-7"><img class="w-full aspect-[4/3] object-cover" src="<?= e($sectionImage($dining1)) ?>" alt="<?= e((string)($dining1['kicker'] ?? 'Dining')) ?>"></div>
      <div class="col-span-12 lg:col-span-5 flex flex-col justify-center md:px-8">
        <span class="text-secondary text-[10px] tracking-[0.3em] uppercase mb-6"><?= e((string)($dining1['kicker'] ?? 'Native Flavors')) ?></span>
        <h2 class="font-headline text-4xl md:text-5xl mb-6 font-light"><?= (string)($dining1['title_html'] ?? 'Dining Experience') ?></h2>
        <p class="text-on-surface-variant text-lg leading-relaxed mb-8"><?= e((string)($dining1['body'] ?? '')) ?></p>
      </div>
    </div>

    <div class="editorial-grid items-center mb-48 gap-20">
      <div class="col-span-12 lg:col-span-5 flex flex-col md:px-8">
        <span class="text-secondary text-[10px] tracking-[0.3em] uppercase mb-6"><?= e((string)($dining2['kicker'] ?? 'Oriental Mastery')) ?></span>
        <h2 class="font-headline text-4xl md:text-5xl mb-6 font-light"><?= (string)($dining2['title_html'] ?? 'Red Lotus') ?></h2>
        <p class="text-on-surface-variant text-lg leading-relaxed mb-8"><?= e((string)($dining2['body'] ?? '')) ?></p>
      </div>
      <div class="col-span-12 lg:col-span-7"><img class="w-full aspect-[16/9] object-cover" src="<?= e($sectionImage($dining2)) ?>" alt="<?= e((string)($dining2['kicker'] ?? 'Dining')) ?>"></div>
    </div>
  </section>

  <section class="bg-[#0B1F3A] text-white py-40" id="wellness">
    <div class="max-w-screen-2xl mx-auto px-12">
      <div class="mb-32 text-center">
        <span class="text-secondary uppercase tracking-[0.4em] text-[10px] mb-6 block"><?= e((string)($wellness1['kicker'] ?? 'Rejuvenation')) ?></span>
        <h2 class="font-headline text-5xl md:text-7xl font-light italic"><?= (string)($wellness1['title_html'] ?? 'The Vitality Sanctuary') ?></h2>
      </div>
      <div class="space-y-40">
        <?php foreach ([$wellness1, $wellness2, $wellness3] as $wi => $sec): ?>
        <div class="editorial-grid items-center">
          <div class="col-span-12 lg:col-span-7 <?= $wi % 2 ? 'lg:order-2' : '' ?>">
            <img class="w-full aspect-video object-cover" src="<?= e($sectionImage($sec)) ?>" alt="<?= e((string)($sec['kicker'] ?? 'Wellness')) ?>">
          </div>
          <div class="col-span-12 lg:col-span-5 <?= $wi % 2 ? 'lg:order-1' : 'lg:pl-12' ?>">
            <h3 class="font-headline text-4xl mb-6 font-light"><?= (string)($sec['title_html'] ?? 'Feature') ?></h3>
            <p class="text-white/70 font-light text-lg leading-relaxed"><?= e((string)($sec['body'] ?? '')) ?></p>
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
          <h2 class="font-headline text-5xl font-light"><?= (string)($business['title_html'] ?? 'Meetings & Events') ?></h2>
        </div>
        <p class="text-on-surface-variant max-w-sm text-lg leading-relaxed font-light"><?= e((string)($business['body'] ?? 'Host with confidence in our versatile meeting spaces.')) ?></p>
      </div>
      <img class="w-full aspect-[21/9] object-cover" src="<?= e($sectionImage($business)) ?>" alt="<?= e((string)($business['kicker'] ?? 'Business')) ?>">
    </div>
  </section>

  <section class="py-40 bg-surface-container-low" id="services">
    <div class="max-w-screen-2xl mx-auto px-12">
      <div class="text-center mb-32">
        <span class="text-secondary uppercase tracking-[0.4em] text-[10px] mb-6 block"><?= e($servicesKicker) ?></span>
        <h2 class="font-headline text-4xl md:text-5xl font-light italic"><?= e($servicesTitle) ?></h2>
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

  <section class="py-60 bg-surface text-center">
    <div class="max-w-3xl mx-auto px-6">
      <h2 class="font-headline text-5xl md:text-6xl font-light mb-10"><?= e($ctaTitle) ?></h2>
      <a class="inline-block bg-secondary text-on-secondary px-16 py-6 uppercase tracking-[0.3em] text-[10px] hover:bg-primary hover:text-white transition-all" href="<?= e(site_href($ctaBtnHref)) ?>"><?= e($ctaBtnLabel) ?></a>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
