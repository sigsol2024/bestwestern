<?php
require_once __DIR__ . '/includes/content-loader.php';

$pageTitle = getPageSection('contact', 'page_title', 'Contact Us');
$hero_kicker = getPageSection('contact', 'hero_kicker', 'Connect With Us');
$hero_title = getPageSection('contact', 'hero_title', 'Get in Touch');
$hero_intro = getPageSection('contact', 'hero_intro', 'Whether you are planning a grand event or seeking a quiet retreat, our curators are ready to assist you in crafting the perfect experience.');

$directory_title = getPageSection('contact', 'directory_title', 'Directory');
$estate_label = getPageSection('contact', 'estate_label', 'The Estate');
$address_html = getPageSection('contact', 'address_html', "10 Julius Berger Road, Swali,<br/>\nYenagoa, Bayelsa State, Nigeria");

$reservations_label = getPageSection('contact', 'reservations_label', 'Reservations');
$reservations_phone = getPageSection('contact', 'reservations_phone', cms_default_setting('footer_phone'));
$front_desk_label = getPageSection('contact', 'front_desk_label', 'Front Desk');
$front_desk_phone = getPageSection('contact', 'front_desk_phone', cms_default_setting('footer_phone'));

$email_1_label = getPageSection('contact', 'email_1_label', 'Central Liaison');
$email_1_value = getPageSection('contact', 'email_1_value', cms_default_setting('contact_email'));
$email_2_label = getPageSection('contact', 'email_2_label', 'Events & Sales');
$email_2_value = getPageSection('contact', 'email_2_value', 'sales@bestwesternplusyenagoa.com');
$email_3_label = getPageSection('contact', 'email_3_label', 'Concierge');
$email_3_value = getPageSection('contact', 'email_3_value', 'concierge@bestwesternplusyenagoa.com');

$media_kit_label = getPageSection('contact', 'media_kit_label', 'Download Media Kit');
$media_kit_href = getPageSection('contact', 'media_kit_href', '#');

$location_title = getPageSection('contact', 'location_title', 'Our Location');
$location_body = getPageSection('contact', 'location_body', "Situated in the heart of Swali, our hotel offers seamless access to the city's commercial and cultural hubs.");
$directions_label = getPageSection('contact', 'directions_label', 'Get Directions');
$directions_href = getPageSection('contact', 'directions_href', '#');

$map_address = getPageSection('contact', 'map_address', '');
$map_embed_url = getPageSection('contact', 'map_embed_url', '');
$map_fallback_image = getPageSection(
    'contact',
    'map_fallback_image',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuB6rR_TsSC24hf7vfuZ5JGSFqJ9-uhDMGCMHP7PBUffuXm-rZ-3qX0qsLNZRiJ6tc6oPL6pDUPYQv6oZYk9g3mR8H5CAtQcfd7H1WcANrEy83idG5uWM3Ucq7fl8hRKVc93IYDWhKuuDr5YSLC7hPSei5T_RuYGghzhvNkuFPQUqeutVtQGf4gARSOWOVYnydwpbUStVhAUddSJ_N4fMxN44VZ17kUYcD54_WAUfp2kOPEPrpZ3kHAxApsq4aJcZqjN7ou1DrezKTU'
);
$googleMapsApiKey = getSiteSetting('google_maps_api_key', '');

$mapAddressPlain = trim(strip_tags(str_replace(['<br/>', '<br />', '<br>'], ', ', (string) $address_html)));
if (trim((string) $map_address) !== '') {
    $mapAddressPlain = trim((string) $map_address);
}

$mapUrl = '';
if (trim((string) $map_embed_url) !== '') {
    $mapUrl = trim((string) $map_embed_url);
} elseif (trim((string) $googleMapsApiKey) !== '' && $mapAddressPlain !== '') {
    $mapUrl = 'https://www.google.com/maps/embed/v1/place?key=' . urlencode((string) $googleMapsApiKey) . '&q=' . urlencode($mapAddressPlain);
} elseif ($mapAddressPlain !== '') {
    $mapUrl = 'https://www.google.com/maps?q=' . urlencode($mapAddressPlain) . '&output=embed';
}

$directionsUrl = trim((string) $directions_href);
if ($directionsUrl === '' || $directionsUrl === '#') {
    $directionsUrl = $mapAddressPlain !== ''
        ? ('https://www.google.com/maps/dir/?api=1&destination=' . urlencode($mapAddressPlain))
        : '#';
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($pageTitle) ?></title>
  <?php require_once __DIR__ . '/includes/head-header.php'; ?>
  <style>
    .noto-serif { font-family: 'Noto Serif', serif; }
    .inter { font-family: 'Inter', sans-serif; }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
    }
    body[data-page="contact"] {
      background-color: #fdf9f3;
      color: #1c1c18;
    }
    body[data-page="contact"] .site-header-nav--over-hero:not(.site-header-nav--scrolled) .site-header-desktop-link--inactive,
    body[data-page="contact"] .site-header-nav--over-hero:not(.site-header-nav--scrolled) .site-header-mobile-trigger {
      color: #1c1c18;
    }
  </style>
</head>
<body data-page="contact" class="bg-surface text-on-surface font-body selection:bg-secondary-container selection:text-on-secondary-container overflow-x-hidden">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<main class="pt-32">
  <header class="px-12 mb-16 max-w-screen-2xl mx-auto">
    <div class="grid grid-cols-12 gap-8 items-end">
      <div class="col-span-12 md:col-span-7">
        <span class="inter uppercase tracking-[0.3em] text-[10px] font-bold text-secondary mb-4 block"><?= e($hero_kicker) ?></span>
        <h1 class="noto-serif text-6xl md:text-8xl font-light text-primary leading-tight"><?= e($hero_title) ?></h1>
      </div>
      <div class="col-span-12 md:col-span-5 pb-4">
        <p class="inter text-sm text-on-surface-variant max-w-xs leading-relaxed opacity-80">
          <?= e($hero_intro) ?>
        </p>
      </div>
    </div>
  </header>

  <section class="max-w-screen-2xl mx-auto px-12 pb-24">
    <div class="flex flex-col lg:flex-row gap-0 bg-surface-container-low rounded-lg overflow-hidden border border-outline-variant/10 min-h-[700px]">
      <div class="w-full lg:w-2/5 p-12 md:p-20 bg-surface-container-lowest flex flex-col justify-between">
        <div>
          <h2 class="noto-serif italic text-3xl text-primary mb-12"><?= e($directory_title) ?></h2>

          <div class="mb-12">
            <p class="inter text-[10px] uppercase tracking-widest text-on-surface-variant/60 font-semibold mb-4"><?= e($estate_label) ?></p>
            <div class="inter text-lg text-primary leading-relaxed">
              <?= $address_html ?>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
            <div>
              <p class="inter text-[10px] uppercase tracking-widest text-on-surface-variant/60 font-semibold mb-4"><?= e($reservations_label) ?></p>
              <a class="inter text-sm text-primary font-semibold hover:text-secondary transition-colors" href="tel:<?= e(preg_replace('/[^0-9+]/', '', (string) $reservations_phone)) ?>">
                <?= e($reservations_phone) ?>
              </a>
            </div>
            <div>
              <p class="inter text-[10px] uppercase tracking-widest text-on-surface-variant/60 font-semibold mb-4"><?= e($front_desk_label) ?></p>
              <a class="inter text-sm text-primary font-semibold hover:text-secondary transition-colors" href="tel:<?= e(preg_replace('/[^0-9+]/', '', (string) $front_desk_phone)) ?>">
                <?= e($front_desk_phone) ?>
              </a>
            </div>
          </div>

          <div class="space-y-8">
            <div>
              <p class="inter text-[10px] uppercase tracking-widest text-on-surface-variant/60 font-semibold mb-2"><?= e($email_1_label) ?></p>
              <a class="inter text-sm text-secondary hover:underline underline-offset-4 decoration-1 font-medium" href="mailto:<?= e($email_1_value) ?>"><?= e($email_1_value) ?></a>
            </div>
            <div>
              <p class="inter text-[10px] uppercase tracking-widest text-on-surface-variant/60 font-semibold mb-2"><?= e($email_2_label) ?></p>
              <a class="inter text-sm text-secondary hover:underline underline-offset-4 decoration-1 font-medium" href="mailto:<?= e($email_2_value) ?>"><?= e($email_2_value) ?></a>
            </div>
            <div>
              <p class="inter text-[10px] uppercase tracking-widest text-on-surface-variant/60 font-semibold mb-2"><?= e($email_3_label) ?></p>
              <a class="inter text-sm text-secondary hover:underline underline-offset-4 decoration-1 font-medium" href="mailto:<?= e($email_3_value) ?>"><?= e($email_3_value) ?></a>
            </div>
          </div>
        </div>

        <div class="mt-16 pt-12 border-t border-outline-variant/10">
          <a class="group flex items-center gap-4 text-secondary inter text-[11px] uppercase tracking-[0.3em] font-bold" href="<?= e(site_href((string) $media_kit_href)) ?>">
            <?= e($media_kit_label) ?>
            <span class="w-12 h-[1px] bg-secondary group-hover:w-16 transition-all duration-300"></span>
          </a>
        </div>
      </div>

      <div class="w-full lg:w-3/5 relative overflow-hidden bg-primary-container min-h-[420px]">
        <?php if ($mapUrl !== ''): ?>
          <iframe
            class="absolute inset-0 w-full h-full border-0"
            src="<?= e($mapUrl) ?>"
            allowfullscreen
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Map showing <?= e($pageTitle) ?> location"></iframe>
        <?php else: ?>
          <div class="absolute inset-0 grayscale contrast-125 opacity-30 mix-blend-overlay">
            <img alt="Map overlay" class="w-full h-full object-cover" src="<?= e($map_fallback_image) ?>"/>
          </div>
        <?php endif; ?>

        <div class="absolute inset-0 bg-gradient-to-t from-primary-container/80 via-transparent to-transparent pointer-events-none"></div>

        <div class="absolute bottom-12 left-12 right-12">
          <div class="p-10 bg-surface-container-lowest/5 backdrop-blur-2xl border border-white/10 rounded-lg">
            <div class="flex items-start gap-6">
              <span class="material-symbols-outlined text-secondary text-4xl mt-1">location_on</span>
              <div>
                <h3 class="noto-serif text-3xl text-white mb-2"><?= e($location_title) ?></h3>
                <p class="inter text-sm text-slate-300 leading-relaxed mb-8 max-w-sm"><?= e($location_body) ?></p>
                <?php if ($directionsUrl !== '#'): ?>
                <a class="inline-flex items-center gap-2 inter text-[11px] uppercase tracking-[0.2em] font-bold text-secondary hover:text-white transition-colors bg-white/5 py-3 px-6 rounded border border-white/10"
                   href="<?= e($directionsUrl) ?>" target="_blank" rel="noopener noreferrer">
                  <?= e($directions_label) ?>
                  <span class="material-symbols-outlined text-sm">open_in_new</span>
                </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
