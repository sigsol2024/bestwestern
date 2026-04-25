<?php
/**
 * Shared header / navigation.
 * Requires: content-loader.php included before this file.
 */

if (!function_exists('getSiteSetting')) {
    function getSiteSetting($key, $default = '') { return $default; }
}
if (!function_exists('e')) {
    function e($string) { return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8'); }
}

// Optional: site-wide injected body scripts
if (function_exists('getSiteSetting')) {
    $bodyScripts = getSiteSetting('body_scripts', '');
    if (!empty($bodyScripts)) {
        echo "\n<!-- Custom Body Scripts -->\n";
        echo $bodyScripts . "\n";
    }
}

$siteName = getSiteSetting('site_name', cms_default_setting('site_name'));
$siteBrandCollection = getSiteSetting('site_brand_collection_line', cms_default_setting('site_brand_collection_line'));
/** Dark / full-color logo for light backgrounds (header): CMS or assets/images/logo/logo-dark.png */
$siteLogoDarkPath = site_brand_logo_path((string)getSiteSetting('site_logo', ''), 'assets/images/logo/logo-dark.png');
$siteLogoDarkUrl = $siteLogoDarkPath !== '' ? site_media_url($siteLogoDarkPath) : '';
$useHeaderLogo = $siteLogoDarkUrl !== '';

$navSuitesLabel = getSiteSetting('nav_suites_label', cms_default_setting('nav_suites_label'));
$navDiningLabel = getSiteSetting('nav_dining_label', cms_default_setting('nav_dining_label'));
$navExperienceLabel = getSiteSetting('nav_experience_label', cms_default_setting('nav_experience_label'));
$navStoryLabel = getSiteSetting('nav_story_label', cms_default_setting('nav_story_label'));

$navSuitesHref = site_href(getSiteSetting('nav_suites_href', cms_default_setting('nav_suites_href')));
$navDiningHref = site_href(getSiteSetting('nav_dining_href', cms_default_setting('nav_dining_href')));
$navExperienceHref = site_href(getSiteSetting('nav_experience_href', cms_default_setting('nav_experience_href')));
$navStoryHref = site_href(getSiteSetting('nav_story_href', cms_default_setting('nav_story_href')));
$navContactHref = site_href('/contact');

$aboutNavLabel = 'About Us';
$aboutPageHref = site_href('/about');
$aboutSectionHref = site_url('index') . '#home-about';
$aboutNavHref = (site_is_valid_nav_href($aboutPageHref) && site_nav_link_visible($aboutPageHref))
    ? $aboutPageHref
    : $aboutSectionHref;

$diningNavLabel = trim((string) $navDiningLabel) !== '' ? (string) $navDiningLabel : 'Dining';
$diningPageHref = site_href('/dining');
$diningSectionHref = site_url('index') . '#home-dining';
$diningNavHref = (site_is_valid_nav_href($diningPageHref) && site_nav_link_visible($diningPageHref))
    ? $diningPageHref
    : $diningSectionHref;

$ctaLabel = getSiteSetting('nav_cta_label', cms_default_setting('nav_cta_label'));
$ctaHref = site_href(getSiteSetting('nav_cta_href', cms_default_setting('nav_cta_href')));

$headerNavLinks = [
    [$navSuitesLabel, $navSuitesHref],
    [$aboutNavLabel, $aboutNavHref],
    [$navExperienceLabel, $navExperienceHref],
    [$diningNavLabel, $diningNavHref],
    [$navStoryLabel, $navStoryHref],
    ['Contact', $navContactHref],
];
$headerNavLinks = array_values(array_filter($headerNavLinks, static function ($row) {
    $href = (string) $row[1];
    return site_is_valid_nav_href($href) && site_nav_link_visible($href);
}));

$showNavCta = site_is_valid_nav_href($ctaHref) && site_nav_link_visible($ctaHref);

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestPath = is_string($requestPath) ? rtrim($requestPath, '/') : '';
if ($requestPath === '') {
    $requestPath = '/';
}

$headerOverlapsHero = !empty($GLOBALS['site_header_overlaps_hero']);
?>

<!-- Fixed top navigation (BW layout) -->
<nav class="site-header-nav fixed top-0 w-full z-50 flex justify-between items-center px-6 md:px-12 py-6 md:py-8 max-w-screen-2xl mx-auto left-1/2 -translate-x-1/2 text-on-surface bg-transparent backdrop-blur-md transition-[background-color,box-shadow] duration-300 <?= $headerOverlapsHero ? 'site-header-nav--over-hero' : '' ?>">
  <a class="site-brand-logo site-brand-logo--header flex flex-col shrink-0 min-w-0 rounded-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold/40 pe-2" href="<?= e(site_url('index')) ?>" aria-label="<?= e($siteName) ?>">
    <?php if ($useHeaderLogo): ?>
    <img src="<?= e($siteLogoDarkUrl) ?>" alt="<?= e($siteName) ?>" class="h-10 md:h-11 w-auto max-w-[14rem] object-contain object-left" decoding="async"/>
    <?php if (trim($siteBrandCollection) !== ''): ?>
    <span class="font-body text-[9px] uppercase tracking-[0.2em] text-on-surface-variant mt-1 max-w-[16rem]"><?= site_brand_name_html($siteBrandCollection) ?></span>
    <?php endif; ?>
    <?php else: ?>
    <div class="font-headline text-xl md:text-2xl tracking-tighter text-slate-900 leading-none"><?= site_brand_name_html($siteName) ?></div>
    <?php if (trim($siteBrandCollection) !== ''): ?>
    <span class="font-body text-[9px] uppercase tracking-[0.2em] text-on-surface-variant mt-1 max-w-[16rem]"><?= site_brand_name_html($siteBrandCollection) ?></span>
    <?php endif; ?>
    <?php endif; ?>
  </a>
  <div class="site-header-desktop-nav hidden md:flex items-center space-x-8 lg:space-x-10">
    <?php foreach ($headerNavLinks as $navRow):
        [$navLabel, $navHref] = $navRow;
        $navPath = parse_url((string) $navHref, PHP_URL_PATH);
        $navPath = is_string($navPath) ? rtrim($navPath, '/') : '';
        if ($navPath === '') {
            $navPath = '/';
        }
        $isActive = ($requestPath === $navPath || ($navPath !== '/' && strpos($requestPath, $navPath) === 0));
        $linkClass = 'site-header-desktop-link font-body uppercase tracking-[0.2em] text-xs font-semibold transition-colors pb-1 border-b-2 ';
        $linkClass .= $isActive
            ? 'site-header-desktop-link--active text-brand-gold border-brand-red/30'
            : 'site-header-desktop-link--inactive border-transparent';
        ?>
    <a class="<?= e($linkClass) ?>" href="<?= e(site_href((string) $navHref)) ?>"><?= e((string) $navLabel) ?></a>
    <?php endforeach; ?>
  </div>
  <div class="flex items-center gap-3 md:gap-4 shrink-0">
    <?php if ($showNavCta): ?>
    <a class="hidden md:inline-flex bg-brand-gold text-white px-6 lg:px-8 py-2.5 lg:py-3 font-body uppercase tracking-[0.2em] text-xs font-bold hover:brightness-110 transition-all duration-300 text-center" href="<?= e(site_href($ctaHref)) ?>"><?= e($ctaLabel) ?></a>
    <?php endif; ?>
    <button class="site-header-mobile-trigger md:hidden p-2 rounded-lg text-on-surface hover:bg-black/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold/40" type="button" id="siteMobileMenuBtn" aria-label="Open menu" aria-expanded="false" aria-controls="siteMobileMenuOverlay">
      <span class="material-symbols-outlined text-3xl">menu</span>
    </button>
  </div>
</nav>

<?php if (!$headerOverlapsHero): ?>
<div class="site-fixed-nav-spacer h-[5.5rem] md:h-[7.5rem] shrink-0" aria-hidden="true"></div>
<?php endif; ?>

<!-- Mobile menu -->
<div id="siteMobileMenuOverlay" class="site-mobile-menu-overlay fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 md:hidden" aria-hidden="true">
  <div id="siteMobileMenuModal" class="site-mobile-menu-modal w-full max-w-sm bg-surface rounded-xl shadow-2xl border border-outline-variant/40 overflow-hidden max-h-[90vh] flex flex-col">
    <div class="flex items-center justify-between px-5 py-4 border-b border-outline-variant/30 shrink-0">
      <span class="font-headline text-lg font-semibold text-on-surface"><?= e($siteName) ?></span>
      <button type="button" id="siteMobileMenuClose" class="p-2 rounded-lg text-on-surface-variant hover:bg-black/[0.04] focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold/40" aria-label="Close menu">
        <span class="material-symbols-outlined text-2xl">close</span>
      </button>
    </div>
    <nav class="flex flex-col p-3 gap-0.5 overflow-y-auto" aria-label="Mobile">
      <?php foreach ($headerNavLinks as $navRow):
          [$navLabel, $navHref] = $navRow;
          if (!site_is_valid_nav_href((string) $navHref)) {
              continue;
          } ?>
      <a class="site-mobile-menu-link px-4 py-3.5 rounded-lg text-on-surface font-medium hover:bg-brand-gold/15 hover:text-brand-red transition-colors" href="<?= e(site_href((string) $navHref)) ?>"><?= e((string) $navLabel) ?></a>
      <?php endforeach; ?>
      <?php if ($showNavCta): ?>
      <a class="site-mobile-menu-link mt-2 mx-1 px-4 py-3.5 rounded-lg bg-brand-gold text-white font-bold text-center hover:brightness-110 transition-colors" href="<?= e(site_href($ctaHref)) ?>"><?= e($ctaLabel) ?></a>
      <?php endif; ?>
    </nav>
  </div>
</div>
<script>
(function () {
  var overlay = document.getElementById('siteMobileMenuOverlay');
  var openBtn = document.getElementById('siteMobileMenuBtn');
  var closeBtn = document.getElementById('siteMobileMenuClose');
  var modal = document.getElementById('siteMobileMenuModal');
  function openMenu() {
    if (!overlay) return;
    overlay.classList.add('open');
    overlay.setAttribute('aria-hidden', 'false');
    if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    if (!overlay) return;
    overlay.classList.remove('open');
    overlay.setAttribute('aria-hidden', 'true');
    if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }
  if (openBtn) openBtn.addEventListener('click', openMenu);
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  if (overlay) {
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeMenu();
    });
  }
  if (modal) {
    modal.addEventListener('click', function (e) {
      e.stopPropagation();
    });
  }
  var links = document.querySelectorAll('.site-mobile-menu-link');
  for (var i = 0; i < links.length; i++) {
    links[i].addEventListener('click', closeMenu);
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay && overlay.classList.contains('open')) closeMenu();
  });
})();

(function () {
  var nav = document.querySelector('.site-header-nav--over-hero');
  if (!nav) return;
  var threshold = 32;
  function onScroll() {
    if (window.scrollY > threshold) {
      nav.classList.add('site-header-nav--scrolled');
    } else {
      nav.classList.remove('site-header-nav--scrolled');
    }
  }
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
})();
</script>
<style>
  /* Desktop nav: white on hero at top, dark after scroll */
  .site-header-nav:not(.site-header-nav--over-hero) .site-header-desktop-link--inactive {
    color: #1e293b;
  }
  .site-header-nav:not(.site-header-nav--over-hero) .site-header-desktop-link--inactive:hover {
    color: #E31837;
  }
  .site-header-nav--over-hero:not(.site-header-nav--scrolled) .site-header-desktop-link--inactive {
    color: rgba(255, 255, 255, 0.92);
  }
  .site-header-nav--over-hero:not(.site-header-nav--scrolled) .site-header-desktop-link--inactive:hover {
    color: #ffffff;
  }
  .site-header-nav--over-hero.site-header-nav--scrolled .site-header-desktop-link--inactive {
    color: #0f172a;
  }
  .site-header-nav--over-hero.site-header-nav--scrolled .site-header-desktop-link--inactive:hover {
    color: #E31837;
  }
  .site-header-nav--over-hero.site-header-nav--scrolled {
    background-color: rgba(255, 255, 255, 0.92);
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.06);
  }
  .site-header-nav--over-hero:not(.site-header-nav--scrolled) .site-header-mobile-trigger {
    color: rgba(255, 255, 255, 0.95);
  }
  .site-header-nav--over-hero:not(.site-header-nav--scrolled) .site-header-mobile-trigger:hover {
    background-color: rgba(255, 255, 255, 0.12);
  }
  .site-header-nav--over-hero.site-header-nav--scrolled .site-header-mobile-trigger {
    color: #1c1c18;
  }
</style>
