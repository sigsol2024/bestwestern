<?php
/**
 * Shared footer.
 * Requires: content-loader.php included before this file.
 */

if (!function_exists('getSiteSetting')) {
    function getSiteSetting($key, $default = '') { return $default; }
}
if (!function_exists('e')) {
    function e($string) { return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8'); }
}

$siteName = getSiteSetting('site_name', cms_default_setting('site_name'));
$siteBrandCollection = getSiteSetting('site_brand_collection_line', cms_default_setting('site_brand_collection_line'));
$siteLogoPath = site_brand_logo_path((string)getSiteSetting('site_logo', ''), 'assets/images/logo/logo-dark.png');
$siteLogoUrl = $siteLogoPath !== '' ? site_media_url($siteLogoPath) : '';
$useFooterLogo = $siteLogoUrl !== '';
$footerEmail = getSiteSetting('footer_email', cms_default_setting('footer_email'));
$footerCopyright = getSiteSetting('footer_copyright', cms_default_setting('footer_copyright'));
$footerLine2 = getSiteSetting('footer_line_2', cms_default_setting('footer_line_2'));

$navSuitesLabel = getSiteSetting('nav_suites_label', cms_default_setting('nav_suites_label'));
$navDiningLabel = getSiteSetting('nav_dining_label', cms_default_setting('nav_dining_label'));
$navEventsLabel = getSiteSetting('nav_events_label', cms_default_setting('nav_events_label'));
$navSuitesHref = site_href(getSiteSetting('nav_suites_href', cms_default_setting('nav_suites_href')));
$navDiningHref = site_href(getSiteSetting('nav_dining_href', cms_default_setting('nav_dining_href')));
$navEventsHref = site_href(getSiteSetting('nav_events_href', cms_default_setting('nav_events_href')));

$footerPrivacyHref = trim((string)getSiteSetting('footer_privacy_href', cms_default_setting('footer_privacy_href')));
$footerTermsHref = trim((string)getSiteSetting('footer_terms_href', cms_default_setting('footer_terms_href')));

$socialMediaJson = getSiteSetting('social_media_json', '[]');
$socialMediaList = json_decode($socialMediaJson, true);
if (!is_array($socialMediaList)) {
    $socialMediaList = [];
}

function social_platform_from_url($url) {
    $u = strtolower((string)$url);
    if (strpos($u, 'instagram.com') !== false) return 'instagram';
    if (strpos($u, 'linkedin.com') !== false) return 'linkedin';
    if (strpos($u, 'tiktok.com') !== false) return 'tiktok';
    if (strpos($u, 'twitter.com') !== false || strpos($u, 'x.com') !== false) return 'x';
    if (strpos($u, 'facebook.com') !== false || strpos($u, 'fb.com') !== false) return 'facebook';
    return '';
}
function social_normalize_platform($p) {
    $v = strtolower(trim((string)$p));
    if ($v === 'twitter' || $v === 'x-twitter') return 'x';
    if ($v === 'ig') return 'instagram';
    return $v;
}
function social_platform_svg($platform) {
    $p = social_normalize_platform($platform);
    $cls = 'w-6 h-6';
    if ($p === 'facebook') {
        return '<svg class="'.$cls.'" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M22 12.06C22 6.504 17.523 2 12 2S2 6.504 2 12.06C2 17.08 5.657 21.245 10.438 22v-7.03H7.898v-2.91h2.54V9.845c0-2.522 1.492-3.915 3.777-3.915 1.094 0 2.238.196 2.238.196v2.476h-1.26c-1.242 0-1.63.776-1.63 1.57v1.888h2.773l-.443 2.91h-2.33V22C18.343 21.245 22 17.08 22 12.06z"/></svg>';
    }
    if ($p === 'instagram') {
        return '<svg class="'.$cls.'" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3z"/><path d="M12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/><path d="M17.5 6.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>';
    }
    if ($p === 'linkedin') {
        return '<svg class="'.$cls.'" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M4.98 3.5C4.98 4.88 3.88 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM.5 8h4V23h-4V8zm7.5 0h3.8v2.05h.05c.53-1 1.84-2.05 3.79-2.05 4.05 0 4.8 2.67 4.8 6.13V23h-4v-6.75c0-1.61-.03-3.68-2.24-3.68-2.24 0-2.58 1.75-2.58 3.56V23h-4V8z"/></svg>';
    }
    if ($p === 'tiktok') {
        return '<svg class="'.$cls.'" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M14 3v10.2a3.8 3.8 0 1 1-2.4-3.55V6.1c0-.6.5-1.1 1.1-1.1H14z"/><path d="M14 3c.9 2.6 2.8 4.3 5 4.6v2.3c-2-.1-3.8-.9-5-2.1V3z"/></svg>';
    }
    if ($p === 'x') {
        return '<svg class="'.$cls.'" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M18.9 2H22l-6.8 7.8L23 22h-6.2l-4.8-6.6L6 22H3l7.3-8.4L1 2h6.4l4.3 6L18.9 2zm-1.1 18h1.7L7.3 3.9H5.5L17.8 20z"/></svg>';
    }
    return '';
}

$privacyPolicySlug = 'privacy-policy';
$termsSlug = 'terms-and-conditions';
$footerPrivacyLink = $footerPrivacyHref !== '' ? site_href($footerPrivacyHref) : site_url($privacyPolicySlug);
$footerTermsLink = $footerTermsHref !== '' ? site_href($footerTermsHref) : site_url($termsSlug);
$aboutHref = site_href('/about');
$footerMenuLinks = [];
if (site_is_valid_nav_href($navSuitesHref) && site_nav_link_visible($navSuitesHref)) {
    $footerMenuLinks[] = ['label' => $navSuitesLabel, 'href' => $navSuitesHref];
}
if (site_is_valid_nav_href($navEventsHref) && site_nav_link_visible($navEventsHref)) {
    $footerMenuLinks[] = ['label' => $navEventsLabel, 'href' => $navEventsHref];
}
if (site_is_valid_nav_href($aboutHref) && site_nav_link_visible($aboutHref)) {
    $footerMenuLinks[] = ['label' => 'Our Story', 'href' => $aboutHref];
}
if (site_is_valid_nav_href($footerPrivacyLink)) {
    $footerMenuLinks[] = ['label' => 'Privacy', 'href' => $footerPrivacyLink];
}
if (site_is_valid_nav_href($footerTermsLink)) {
    $footerMenuLinks[] = ['label' => 'Terms', 'href' => $footerTermsLink];
}
if (function_exists('site_public_page_exists') && site_public_page_exists('hotel-policy')) {
    $footerMenuLinks[] = ['label' => 'Hotel Policy', 'href' => site_url('hotel-policy')];
}
?>

<footer class="bg-slate-950 text-slate-400 w-full min-h-[240px] flex flex-col justify-end font-body">
  <div class="w-full px-6 md:px-12 py-10 md:py-14 flex flex-col lg:flex-row justify-between items-start max-w-screen-2xl mx-auto gap-8 lg:gap-12">
    <div class="flex flex-col gap-6 max-w-md">
      <div class="flex flex-col">
        <?php if ($useFooterLogo): ?>
        <div class="site-brand-logo site-brand-logo--footer mb-4">
          <img src="<?= e($siteLogoUrl) ?>" alt="<?= e($siteName) ?>" class="h-16 md:h-20 w-auto max-w-[16rem] object-contain object-left rounded-lg bg-white/5 p-2 border border-white/10" decoding="async"/>
        </div>
        <?php else: ?>
        <div class="font-headline text-2xl md:text-3xl uppercase tracking-[0.25em] text-brand-gold leading-none"><?= site_brand_name_html($siteName) ?></div>
        <?php if (trim($siteBrandCollection) !== ''): ?>
        <span class="text-[10px] uppercase tracking-[0.2em] text-slate-500 mt-2"><?= site_brand_name_html($siteBrandCollection) ?></span>
        <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php if (trim($footerEmail) !== ''): ?>
      <div class="space-y-1">
        <p class="font-light tracking-wide text-[11px] text-slate-500 uppercase">Enquiries</p>
        <a class="text-base text-white hover:text-brand-gold transition-colors break-all" href="mailto:<?= e($footerEmail) ?>"><?= e($footerEmail) ?></a>
      </div>
      <?php endif; ?>
    </div>

    <div class="w-full lg:w-auto lg:flex-1 flex flex-col gap-6 lg:items-end">
      <div class="flex flex-wrap items-center gap-y-2">
        <?php foreach ($footerMenuLinks as $idx => $item): ?>
        <a class="text-slate-300 hover:text-white transition-colors text-xs md:text-sm uppercase tracking-[0.14em]" href="<?= e($item['href']) ?>">
          <?= e($item['label']) ?>
        </a>
        <?php if ($idx < count($footerMenuLinks) - 1): ?>
        <span class="mx-3 inline-block h-3 w-px bg-white/20" aria-hidden="true"></span>
        <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <div class="flex items-center gap-3">
        <?php foreach ($socialMediaList as $social):
            $url = trim((string)($social['url'] ?? ''));
            if ($url === '') {
                continue;
            }
            $platform = (string)($social['platform'] ?? '');
            if ($platform === '') {
                $platform = social_platform_from_url($url);
            }
            $icon = social_platform_svg($platform);
            if ($icon === '') {
                continue;
            }
            ?>
        <a class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-white/15 text-slate-300 hover:text-white hover:border-white/40 transition-colors"
           href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e(ucfirst((string) $platform)) ?>">
          <?= $icon ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="w-full px-6 md:px-12 py-8 md:py-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] tracking-[0.2em] uppercase text-slate-500">
    <p class="text-center md:text-left"><?= e($footerCopyright) ?></p>
    <?php if (trim($footerLine2) !== ''): ?>
    <p class="text-center md:text-right"><?= e($footerLine2) ?></p>
    <?php endif; ?>
  </div>
</footer>

<?php
@include_once __DIR__ . '/livechat.php';

if (function_exists('getSiteSetting')) {
    $footerScripts = getSiteSetting('footer_scripts', '');
    if (!empty($footerScripts)) {
        echo "\n<!-- Custom Footer Scripts -->\n";
        echo $footerScripts . "\n";
    }
}
?>
</body></html>
