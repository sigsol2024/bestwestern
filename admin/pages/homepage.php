<?php
$pageTitle = 'Homepage Editor';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/header.php';

$sectionsArray = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM page_sections WHERE page = 'index' ORDER BY section_key");
    $stmt->execute();
    foreach ($stmt->fetchAll() as $section) {
        $sectionsArray[$section['section_key']] = $section['content'];
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
}

function hsec($sectionsArray, $key, $default = '') {
    return sanitize($sectionsArray[$key] ?? $default);
}

$heroPlaceholder = cms_default_setting('placeholder_hero_image');
$detailPlaceholder = cms_default_setting('placeholder_detail_image');
$galleryPlaceholder = cms_default_setting('placeholder_gallery_image');
$roomPlaceholder = cms_default_setting('placeholder_room_image');

$bentoDefaultArr = [
    ['image' => $heroPlaceholder, 'title' => 'The Infinity Pool', 'subtitle' => 'Open Daily • 6AM - 10PM'],
    ['image' => $detailPlaceholder, 'title' => 'Wellness Spa', 'subtitle' => ''],
    ['image' => $galleryPlaceholder, 'title' => 'Elite Gym', 'subtitle' => ''],
    ['image' => $roomPlaceholder, 'title' => 'Akassa Hall', 'subtitle' => 'Business & Events'],
];
$bulletsDefaultArr = ['5 min to Government House', '15 min to Airport', 'Oxbow Lake waterfront'];

$bentoJsonRaw = trim((string)($sectionsArray['home_facilities_bento_json'] ?? ''));
if ($bentoJsonRaw === '') {
    $bentoJsonPretty = json_encode($bentoDefaultArr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
} else {
    $bentoJsonPretty = $bentoJsonRaw;
}

$bulletsJsonRaw = trim((string)($sectionsArray['home_location_bullets_json'] ?? ''));
if ($bulletsJsonRaw === '') {
    $bulletsJsonPretty = json_encode($bulletsDefaultArr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
} else {
    $bulletsJsonPretty = $bulletsJsonRaw;
}
?>

<form id="homepageForm">
  <div class="card">
    <div class="card-header"><h2>Hero</h2></div>
    <div style="padding:20px;">
      <div class="form-group">
        <label for="hero_trust_badge">Trust line (beside stars)</label>
        <input id="hero_trust_badge" name="hero_trust_badge" type="text" value="<?= hsec($sectionsArray, 'hero_trust_badge', 'Travelers Choice 2026') ?>">
      </div>
      <div class="form-group">
        <label for="hero_show_stars">Show five stars before trust line</label>
        <select id="hero_show_stars" name="hero_show_stars">
          <option value="1" <?= trim((string)($sectionsArray['hero_show_stars'] ?? '1')) === '1' ? 'selected' : '' ?>>Yes</option>
          <option value="0" <?= trim((string)($sectionsArray['hero_show_stars'] ?? '1')) === '0' ? 'selected' : '' ?>>No</option>
        </select>
      </div>
      <div class="form-group">
        <label for="hero_title">Title (HTML allowed)</label>
        <textarea id="hero_title" name="hero_title" rows="3"><?= htmlspecialchars($sectionsArray['hero_title'] ?? 'Luxury on the Shores<br/><span class="italic text-surface">of Oxbow Lake.</span>', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-group">
        <label for="hero_subtitle">Subtitle</label>
        <textarea id="hero_subtitle" name="hero_subtitle" rows="2"><?= hsec($sectionsArray, 'hero_subtitle', 'An international standard of hospitality in the heart of Bayelsa.') ?></textarea>
      </div>
      <div class="form-group">
        <label>Hero image</label>
        <div style="margin-bottom:10px;">
          <button type="button" class="btn btn-outline" onclick="openMediaModal('hero_bg','hero_bg_preview')">Select Image</button>
        </div>
        <input type="hidden" id="hero_bg" name="hero_bg" value="<?= hsec($sectionsArray, 'hero_bg') ?>">
        <div id="hero_bg_preview" class="image-preview" style="<?= !empty($sectionsArray['hero_bg']) ? 'display:block;' : 'display:none;' ?>">
          <?php if (!empty($sectionsArray['hero_bg'])): ?>
            <img src="<?= SITE_URL . ltrim($sectionsArray['hero_bg'], '/') ?>" style="max-width:500px;max-height:300px;" alt="">
          <?php endif; ?>
        </div>
      </div>
      <div class="form-group">
        <label for="booking_widget_html">Booking widget HTML (optional)</label>
        <textarea id="booking_widget_html" name="booking_widget_html" rows="8" style="font-family:monospace;font-size:12px;"><?= htmlspecialchars($sectionsArray['booking_widget_html'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <p class="form-help">If set, the provider embed fills the booking bar on the homepage. If empty, the bar shows a decorative layout and the global <strong>Reserve</strong> button from Settings → Header.</p>
      </div>
      <div class="form-group">
        <label for="home_booking_guarantee_line">Best rate line (decorative bar only)</label>
        <input id="home_booking_guarantee_line" name="home_booking_guarantee_line" type="text" value="<?= hsec($sectionsArray, 'home_booking_guarantee_line', 'Best Rate Guarantee') ?>">
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Brand story</h2></div>
    <div style="padding:20px;">
      <div class="form-group">
        <label for="home_philosophy_kicker">Kicker</label>
        <input id="home_philosophy_kicker" name="home_philosophy_kicker" type="text" value="<?= hsec($sectionsArray, 'home_philosophy_kicker', 'Our Heritage') ?>">
      </div>
      <div class="form-group">
        <label for="home_philosophy_title_html">Title (HTML)</label>
        <textarea id="home_philosophy_title_html" name="home_philosophy_title_html" rows="2"><?= htmlspecialchars($sectionsArray['home_philosophy_title_html'] ?? 'Where Heritage Meets Hospitality', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-group">
        <label for="home_philosophy_body">Body (HTML allowed)</label>
        <textarea id="home_philosophy_body" name="home_philosophy_body" rows="6"><?= htmlspecialchars($sectionsArray['home_philosophy_body'] ?? '<p>Nestled in the heart of Bayelsa State, our hotel blends rich heritage with modern hospitality. As part of the Best Western <span class="text-brand-red font-semibold">Plus</span> collection, we uphold a legacy of excellence while delivering a distinctively Nigerian warmth.</p>', ENT_QUOTES, 'UTF-8') ?></textarea>
        <p class="form-help">Rendered as HTML on the homepage (same trust model as hero title). Plain text still works.</p>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="home_philosophy_link_text">Link text</label>
          <input id="home_philosophy_link_text" name="home_philosophy_link_text" type="text" value="<?= hsec($sectionsArray, 'home_philosophy_link_text', 'Explore the Story') ?>">
        </div>
        <div class="form-group">
          <label for="home_philosophy_link_href">Link URL</label>
          <input id="home_philosophy_link_href" name="home_philosophy_link_href" type="text" value="<?= hsec($sectionsArray, 'home_philosophy_link_href', '/about') ?>">
        </div>
      </div>
      <div class="form-group">
        <label>Main image</label>
        <button type="button" class="btn btn-outline" onclick="openMediaModal('home_philosophy_main_img','home_philosophy_main_preview')">Select</button>
        <input type="hidden" id="home_philosophy_main_img" name="home_philosophy_main_img" value="<?= hsec($sectionsArray, 'home_philosophy_main_img') ?>">
        <div id="home_philosophy_main_preview" class="image-preview" style="<?= !empty($sectionsArray['home_philosophy_main_img']) ? 'display:block;' : 'display:none;' ?>">
          <?php if (!empty($sectionsArray['home_philosophy_main_img'])): ?>
            <img src="<?= SITE_URL . ltrim($sectionsArray['home_philosophy_main_img'], '/') ?>" style="max-width:400px;max-height:260px;" alt="">
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Featured rooms strip</h2></div>
    <div style="padding:20px;">
      <p class="form-help">Cards load from <strong>Rooms</strong> marked <strong>Featured</strong>.</p>
      <div class="form-group">
        <label for="home_rooms_title">Title</label>
        <input id="home_rooms_title" name="home_rooms_title" type="text" value="<?= hsec($sectionsArray, 'home_rooms_title', 'Sanctuaries of Calm') ?>">
      </div>
      <div class="form-group">
        <label for="home_rooms_kicker">Subtitle (uppercase line under title)</label>
        <input id="home_rooms_kicker" name="home_rooms_kicker" type="text" value="<?= hsec($sectionsArray, 'home_rooms_kicker', 'Exquisite suites designed for the refined traveler') ?>">
      </div>
      <div class="form-group">
        <label for="home_rooms_view_all_href">View all suites URL</label>
        <input id="home_rooms_view_all_href" name="home_rooms_view_all_href" type="text" value="<?= hsec($sectionsArray, 'home_rooms_view_all_href', '/rooms') ?>">
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Dining (dark section)</h2></div>
    <div style="padding:20px;">
      <div class="form-row">
        <div class="form-group">
          <label for="home_dining_kicker">Kicker</label>
          <input id="home_dining_kicker" name="home_dining_kicker" type="text" value="<?= hsec($sectionsArray, 'home_dining_kicker', 'Gastronomy') ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="home_dining_heading_html">Heading (HTML)</label>
        <textarea id="home_dining_heading_html" name="home_dining_heading_html" rows="2"><?= htmlspecialchars($sectionsArray['home_dining_heading_html'] ?? '<span class="italic">Culinary Excellence</span>', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="home_dining_venue1_title">Venue 1 title</label>
          <input id="home_dining_venue1_title" name="home_dining_venue1_title" type="text" value="<?= hsec($sectionsArray, 'home_dining_venue1_title', 'Mama Oxbow') ?>">
        </div>
        <div class="form-group">
          <label for="home_dining_venue2_title">Venue 2 title</label>
          <input id="home_dining_venue2_title" name="home_dining_venue2_title" type="text" value="<?= hsec($sectionsArray, 'home_dining_venue2_title', 'Red Lotus') ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="home_dining_venue1_body">Venue 1 description</label>
        <textarea id="home_dining_venue1_body" name="home_dining_venue1_body" rows="3"><?= hsec($sectionsArray, 'home_dining_venue1_body') ?></textarea>
      </div>
      <div class="form-group">
        <label for="home_dining_venue2_body">Venue 2 description</label>
        <textarea id="home_dining_venue2_body" name="home_dining_venue2_body" rows="3"><?= hsec($sectionsArray, 'home_dining_venue2_body') ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Image (top / left column)</label>
          <button type="button" class="btn btn-outline" onclick="openMediaModal('home_dining_image_top','home_dining_image_top_preview')">Select</button>
          <input type="hidden" id="home_dining_image_top" name="home_dining_image_top" value="<?= hsec($sectionsArray, 'home_dining_image_top') ?>">
          <div id="home_dining_image_top_preview" class="image-preview" style="<?= !empty($sectionsArray['home_dining_image_top']) ? 'display:block;' : 'display:none;' ?>">
            <?php if (!empty($sectionsArray['home_dining_image_top'])): ?>
              <img src="<?= SITE_URL . ltrim($sectionsArray['home_dining_image_top'], '/') ?>" style="max-width:400px;max-height:220px;" alt="">
            <?php endif; ?>
          </div>
        </div>
        <div class="form-group">
          <label>Image (bottom / right column)</label>
          <button type="button" class="btn btn-outline" onclick="openMediaModal('home_dining_image_bottom','home_dining_image_bottom_preview')">Select</button>
          <input type="hidden" id="home_dining_image_bottom" name="home_dining_image_bottom" value="<?= hsec($sectionsArray, 'home_dining_image_bottom') ?>">
          <div id="home_dining_image_bottom_preview" class="image-preview" style="<?= !empty($sectionsArray['home_dining_image_bottom']) ? 'display:block;' : 'display:none;' ?>">
            <?php if (!empty($sectionsArray['home_dining_image_bottom'])): ?>
              <img src="<?= SITE_URL . ltrim($sectionsArray['home_dining_image_bottom'], '/') ?>" style="max-width:400px;max-height:220px;" alt="">
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Leisure &amp; wellness (bento)</h2></div>
    <div style="padding:20px;">
      <div class="form-group">
        <label for="home_facilities_title">Section title</label>
        <input id="home_facilities_title" name="home_facilities_title" type="text" value="<?= hsec($sectionsArray, 'home_facilities_title', 'Leisure & Wellness') ?>">
      </div>
      <div class="form-group">
        <label for="home_facilities_blurb">Right blurb (small uppercase)</label>
        <textarea id="home_facilities_blurb" name="home_facilities_blurb" rows="2"><?= hsec($sectionsArray, 'home_facilities_blurb', 'Designed to rejuvenate your senses and enhance your productivity.') ?></textarea>
      </div>
      <div class="form-group">
        <label for="home_facilities_bento_json">Four tiles (JSON array)</label>
        <textarea id="home_facilities_bento_json" name="home_facilities_bento_json" rows="16" style="font-family:monospace;font-size:12px;"><?= htmlspecialchars($bentoJsonPretty, ENT_QUOTES, 'UTF-8') ?></textarea>
        <p class="form-help">Exactly <strong>four</strong> objects in order: large left tile, top-right, bottom-right, wide bottom. Keys: <code>image</code>, <code>title</code>, <code>subtitle</code> (optional).</p>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Location</h2></div>
    <div style="padding:20px;">
      <div class="form-group">
        <label for="home_location_title">Title</label>
        <input id="home_location_title" name="home_location_title" type="text" value="<?= hsec($sectionsArray, 'home_location_title', 'The Serenity of Oxbow Lake') ?>">
      </div>
      <div class="form-group">
        <label for="home_location_body">Body</label>
        <textarea id="home_location_body" name="home_location_body" rows="4"><?= hsec($sectionsArray, 'home_location_body') ?></textarea>
      </div>
      <div class="form-group">
        <label for="home_location_bullets_json">Bullets (JSON array of strings)</label>
        <textarea id="home_location_bullets_json" name="home_location_bullets_json" rows="6" style="font-family:monospace;font-size:12px;"><?= htmlspecialchars($bulletsJsonPretty, ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-group">
        <label for="home_location_address">Address line</label>
        <input id="home_location_address" name="home_location_address" type="text" value="<?= hsec($sectionsArray, 'home_location_address', 'Oxbow Lake Rd, Yenagoa, Bayelsa') ?>">
      </div>
      <div class="form-group">
        <label>Map / location image</label>
        <button type="button" class="btn btn-outline" onclick="openMediaModal('home_location_map_image','home_location_map_preview')">Select</button>
        <input type="hidden" id="home_location_map_image" name="home_location_map_image" value="<?= hsec($sectionsArray, 'home_location_map_image') ?>">
        <div id="home_location_map_preview" class="image-preview" style="<?= !empty($sectionsArray['home_location_map_image']) ? 'display:block;' : 'display:none;' ?>">
          <?php if (!empty($sectionsArray['home_location_map_image'])): ?>
            <img src="<?= SITE_URL . ltrim($sectionsArray['home_location_map_image'], '/') ?>" style="max-width:500px;max-height:280px;" alt="">
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Save all</button>
</form>

<script>
(function () {
  var map = {
    hero_bg: 'hero_bg_preview',
    home_philosophy_main_img: 'home_philosophy_main_preview',
    home_dining_image_top: 'home_dining_image_top_preview',
    home_dining_image_bottom: 'home_dining_image_bottom_preview',
    home_location_map_image: 'home_location_map_preview'
  };
  window.insertSelectedMediaOverride = function () {
    var list = mediaModalState.allowMultiple
      ? mediaModalState.selectedMediaMultiple
      : (mediaModalState.selectedMedia ? [mediaModalState.selectedMedia] : []);
    if (!list.length) return false;
    var tid = mediaModalState.targetInputId;
    var selected = list[0];
    var prevId = map[tid];
    if (!prevId) return false;
    var input = document.getElementById(tid);
    var preview = document.getElementById(prevId);
    if (input) input.value = selected.path;
    if (preview) {
      preview.style.display = 'block';
      preview.innerHTML = '<img src="<?= SITE_URL ?>' + selected.path.replace(/^\/+/, '') + '" style="max-width:500px;max-height:300px;">';
    }
    closeMediaModal();
    if (typeof showToast === 'function') showToast('Image selected', 'success');
    return true;
  };
})();
</script>

<script>
document.getElementById('homepageForm').addEventListener('submit', function (e) {
  e.preventDefault();
  var form = this;
  var typeOverrides = {
    hero_title: 'html',
    booking_widget_html: 'html',
    home_philosophy_title_html: 'html',
    home_philosophy_body: 'html',
    home_dining_heading_html: 'html',
    home_facilities_bento_json: 'json',
    home_location_bullets_json: 'json'
  };
  savePageForm(form, 'index', typeOverrides)
    .then(function () { showToast('Saved', 'success'); })
    .catch(function (err) { showToast(err.message || 'Save failed', 'error'); });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
