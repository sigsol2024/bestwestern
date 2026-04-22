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
$siteLogoLightPath = site_brand_logo_path((string)getSiteSetting('site_logo_light', ''), 'assets/images/logo/logo-light.png');
$siteLogoLightUrl = $siteLogoLightPath !== '' ? site_media_url($siteLogoLightPath) : '';
$useFooterLogo = $siteLogoLightUrl !== '';
$footerEmail = getSiteSetting('footer_email', cms_default_setting('footer_email'));
$footerCopyright = getSiteSetting('footer_copyright', cms_default_setting('footer_copyright'));
$footerLine2 = getSiteSetting('footer_line_2', cms_default_setting('footer_line_2'));
$footerTrustLine = getSiteSetting('footer_trust_line', cms_default_setting('footer_trust_line'));

$navSuitesLabel = getSiteSetting('nav_suites_label', cms_default_setting('nav_suites_label'));
$navDiningLabel = getSiteSetting('nav_dining_label', cms_default_setting('nav_dining_label'));
$navEventsLabel = getSiteSetting('nav_events_label', cms_default_setting('nav_events_label'));
$navSuitesHref = site_href(getSiteSetting('nav_suites_href', cms_default_setting('nav_suites_href')));
$navDiningHref = site_href(getSiteSetting('nav_dining_href', cms_default_setting('nav_dining_href')));
$navEventsHref = site_href(getSiteSetting('nav_events_href', cms_default_setting('nav_events_href')));

$footerCareersHref = trim((string)getSiteSetting('footer_careers_href', cms_default_setting('footer_careers_href')));
$footerPressHref = trim((string)getSiteSetting('footer_press_href', cms_default_setting('footer_press_href')));
$footerSustainabilityHref = trim((string)getSiteSetting('footer_sustainability_href', cms_default_setting('footer_sustainability_href')));
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
$footerSustainLink = $footerSustainabilityHref !== '' ? site_href($footerSustainabilityHref) : '';
$footerCareersLink = $footerCareersHref !== '' ? site_href($footerCareersHref) : '';
$footerPressLink = $footerPressHref !== '' ? site_href($footerPressHref) : '';
?>

<footer class="bg-slate-950 text-slate-400 w-full min-h-[320px] flex flex-col justify-end font-body">
  <div class="w-full px-6 md:px-12 py-16 md:py-24 flex flex-col lg:flex-row justify-between items-start lg:items-end max-w-screen-2xl mx-auto gap-12 lg:gap-16">
    <div class="flex flex-col gap-10 max-w-md">
      <div class="flex flex-col">
        <?php if ($useFooterLogo): ?>
        <div class="site-brand-logo site-brand-logo--footer mb-4">
          <img src="<?= e($siteLogoLightUrl) ?>" alt="<?= e($siteName) ?>" class="h-16 md:h-20 w-auto max-w-[12rem] object-contain object-left brightness-0 invert opacity-90" decoding="async"/>
        </div>
        <?php else: ?>
        <div class="font-headline text-2xl md:text-3xl uppercase tracking-[0.25em] text-brand-gold leading-none"><?= site_brand_name_html($siteName) ?></div>
        <?php if (trim($siteBrandCollection) !== ''): ?>
        <span class="text-[10px] uppercase tracking-[0.2em] text-slate-500 mt-2"><?= site_brand_name_html($siteBrandCollection) ?></span>
        <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php if (trim($footerEmail) !== ''): ?>
      <div class="space-y-2">
        <p class="font-light tracking-wide text-sm text-slate-500">ENQUIRIES</p>
        <a class="text-xl text-white hover:text-brand-gold transition-colors break-all" href="mailto:<?= e($footerEmail) ?>"><?= e($footerEmail) ?></a>
      </div>
      <?php endif; ?>
      <?php if (trim($footerTrustLine) !== ''): ?>
      <div class="flex items-center gap-3 border border-white/10 p-4 rounded-sm self-start">
        <div class="flex text-brand-gold">
          <?php for ($si = 0; $si < 5; $si++): ?>
          <span class="material-symbols-outlined !text-sm" style="font-variation-settings:'FILL'1,'wght'400;">star</span>
          <?php endfor; ?>
        </div>
        <span class="text-[10px] text-white uppercase tracking-widest font-bold"><?= e($footerTrustLine) ?></span>
      </div>
      <?php endif; ?>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-10 md:gap-12 w-full lg:w-auto lg:flex-1">
      <div class="flex flex-col gap-3">
        <span class="text-white font-bold text-xs uppercase tracking-widest">Explore</span>
        <?php if (site_is_valid_nav_href($navSuitesHref) && site_nav_link_visible($navSuitesHref)): ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= e($navSuitesHref) ?>"><?= e($navSuitesLabel) ?></a>
        <?php endif; ?>
        <?php if (site_is_valid_nav_href($navDiningHref) && site_nav_link_visible($navDiningHref)): ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= e($navDiningHref) ?>"><?= e($navDiningLabel) ?></a>
        <?php endif; ?>
        <?php if (site_is_valid_nav_href($navEventsHref) && site_nav_link_visible($navEventsHref)): ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= e($navEventsHref) ?>"><?= e($navEventsLabel) ?></a>
        <?php endif; ?>
      </div>
      <div class="flex flex-col gap-3">
        <span class="text-white font-bold text-xs uppercase tracking-widest">Identity</span>
        <?php
        $aboutHref = site_href('/about');
        if (site_is_valid_nav_href($aboutHref) && site_nav_link_visible($aboutHref)): ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= e($aboutHref) ?>">Our Story</a>
        <?php endif; ?>
        <?php if ($footerCareersLink !== '' && site_is_valid_nav_href($footerCareersLink)): ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= e($footerCareersLink) ?>">Careers</a>
        <?php endif; ?>
        <?php if ($footerPressLink !== '' && site_is_valid_nav_href($footerPressLink)): ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= e($footerPressLink) ?>">Press Room</a>
        <?php endif; ?>
      </div>
      <div class="flex flex-col gap-3">
        <span class="text-white font-bold text-xs uppercase tracking-widest">Connect</span>
        <?php foreach ($socialMediaList as $social):
            $url = trim((string)($social['url'] ?? ''));
            if ($url === '') {
                continue;
            }
            $platform = (string)($social['platform'] ?? '');
            if ($platform === '') {
                $platform = social_platform_from_url($url);
            }
            $platform = social_normalize_platform($platform);
            $label = $platform !== '' ? ucfirst($platform === 'x' ? 'X' : $platform) : 'Link';
            ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer"><?= e($label) ?></a>
        <?php endforeach; ?>
      </div>
      <div class="flex flex-col gap-3">
        <span class="text-white font-bold text-xs uppercase tracking-widest">Legal</span>
        <?php if (site_is_valid_nav_href($footerPrivacyLink)): ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= e($footerPrivacyLink) ?>">Privacy</a>
        <?php endif; ?>
        <?php if ($footerSustainLink !== '' && site_is_valid_nav_href($footerSustainLink)): ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= e($footerSustainLink) ?>">Sustainability</a>
        <?php endif; ?>
        <?php if (site_is_valid_nav_href($footerTermsLink)): ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= e($footerTermsLink) ?>">Terms</a>
        <?php endif; ?>
        <?php if (function_exists('site_public_page_exists') && site_public_page_exists('hotel-policy')): ?>
        <a class="text-slate-400 hover:text-white transition-colors text-sm underline-offset-4 hover:underline" href="<?= e(site_url('hotel-policy')) ?>">Hotel Policy</a>
        <?php endif; ?>
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
