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
$bentoTiles = $bentoDefaultArr;
if ($bentoJsonRaw !== '') {
    $decoded = json_decode($bentoJsonRaw, true);
    if (is_array($decoded)) {
        for ($i = 0; $i < 4; $i++) {
            if (!isset($decoded[$i]) || !is_array($decoded[$i])) {
                continue;
            }
            $bentoTiles[$i]['image'] = trim((string)($decoded[$i]['image'] ?? $bentoTiles[$i]['image']));
            $bentoTiles[$i]['title'] = (string)($decoded[$i]['title'] ?? $bentoTiles[$i]['title']);
            $bentoTiles[$i]['subtitle'] = (string)($decoded[$i]['subtitle'] ?? '');
        }
    }
}
$bentoJsonSerialized = json_encode($bentoTiles, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$bentoTileRoleLabels = [
    'Large left tile',
    'Top right tile',
    'Bottom right tile',
    'Wide bottom tile',
];

$bulletsJsonRaw = trim((string)($sectionsArray['home_location_bullets_json'] ?? ''));
if ($bulletsJsonRaw === '') {
    $bulletsJsonPretty = json_encode($bulletsDefaultArr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
} else {
    $bulletsJsonPretty = $bulletsJsonRaw;
}

$heroGallerySlides = ['', '', '', '', ''];
$heroGalleryRaw = trim((string) ($sectionsArray['hero_gallery_slides_json'] ?? ''));
if ($heroGalleryRaw !== '') {
    $hgDecoded = json_decode($heroGalleryRaw, true);
    if (is_array($hgDecoded)) {
        $gi = 0;
        foreach ($hgDecoded as $item) {
            if ($gi >= 5) {
                break;
            }
            $p = is_string($item) ? trim($item) : '';
            if ($p !== '') {
                $heroGallerySlides[$gi] = $p;
                $gi++;
            }
        }
    }
} else {
    $heroGallerySlides[0] = hsec($sectionsArray, 'hero_bg', $heroPlaceholder);
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
        <label>Hero fallback image</label>
        <p class="form-help">Used when the gallery list below is empty, and as the first slide default until you add gallery images.</p>
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
        <label>Hero gallery slides (up to 5)</label>
        <p class="form-help">Each image becomes a full-screen hero slide with fade transitions, arrows, and dots. Use at least two images for the slider controls to appear on the live site.</p>
        <input type="hidden" id="hero_gallery_slides_json" name="hero_gallery_slides_json" value="">
        <?php for ($hg = 0; $hg < 5; $hg++): ?>
        <div class="form-row" style="align-items:flex-end;margin-bottom:12px;">
          <div class="form-group" style="flex:1;">
            <label for="hero_slide_<?= $hg ?>">Slide <?= $hg + 1 ?></label>
            <input type="hidden" id="hero_slide_<?= $hg ?>" value="<?= sanitize($heroGallerySlides[$hg]) ?>">
            <div style="margin-bottom:6px;">
              <button type="button" class="btn btn-outline btn-sm" onclick="openMediaModal('hero_slide_<?= $hg ?>','hero_slide_<?= $hg ?>_preview')">Select</button>
              <button type="button" class="btn btn-outline btn-sm" onclick="(function(){var i=document.getElementById('hero_slide_<?= $hg ?>');var p=document.getElementById('hero_slide_<?= $hg ?>_preview');if(i)i.value='';if(p){p.style.display='none';p.innerHTML='';}})()">Clear</button>
            </div>
            <div id="hero_slide_<?= $hg ?>_preview" class="image-preview" style="<?= !empty($heroGallerySlides[$hg]) ? 'display:block;' : 'display:none;' ?>">
              <?php if (!empty($heroGallerySlides[$hg])): ?>
                <img src="<?= SITE_URL . ltrim($heroGallerySlides[$hg], '/') ?>" style="max-width:320px;max-height:180px;" alt="">
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endfor; ?>
      </div>
      <div class="form-group">
        <label for="booking_widget_html">Booking widget HTML (optional)</label>
        <textarea id="booking_widget_html" name="booking_widget_html" rows="8" style="font-family:monospace;font-size:12px;"><?= htmlspecialchars($sectionsArray['booking_widget_html'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <p class="form-help">If set, the provider embed appears in a bar at the bottom of the homepage hero. If empty, no booking bar is shown on the hero (use the header <strong>Reserve</strong> link from Settings → Header).</p>
      </div>
      <div class="form-group">
        <label for="home_booking_guarantee_line">Best rate line (optional; not shown on hero currently)</label>
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
      <p class="form-help" style="margin-bottom:16px;">Four tiles in fixed order (same layout as the homepage bento). Each tile needs an image, title, and optional subtitle.</p>
      <?php for ($bi = 0; $bi < 4; $bi++):
          $tile = $bentoTiles[$bi];
          $imgPath = (string)($tile['image'] ?? '');
          $tid = 'bento_tile_' . $bi . '_image';
          $pid = 'bento_tile_' . $bi . '_preview';
          ?>
      <div class="form-group" style="border:1px solid #ddd;border-radius:8px;padding:16px;margin-bottom:16px;background:#fafafa;">
        <strong style="display:block;margin-bottom:12px;"><?= htmlspecialchars($bentoTileRoleLabels[$bi], ENT_QUOTES, 'UTF-8') ?></strong>
        <div class="form-group" style="margin-bottom:12px;">
          <label>Image</label>
          <div style="margin-bottom:8px;">
            <button type="button" class="btn btn-outline" onclick="openMediaModal('<?= $tid ?>','<?= $pid ?>')">Select image</button>
          </div>
          <input type="hidden" id="<?= $tid ?>" value="<?= htmlspecialchars($imgPath, ENT_QUOTES, 'UTF-8') ?>">
          <div id="<?= $pid ?>" class="image-preview" style="<?= $imgPath !== '' ? 'display:block;margin-top:10px;' : 'display:none;' ?>">
            <?php if ($imgPath !== ''): ?>
              <img src="<?= SITE_URL . ltrim($imgPath, '/') ?>" style="max-width:420px;max-height:220px;border-radius:4px;" alt="">
            <?php endif; ?>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:12px;">
          <label for="bento_tile_<?= $bi ?>_title">Title</label>
          <input type="text" id="bento_tile_<?= $bi ?>_title" value="<?= htmlspecialchars((string)($tile['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" style="width:100%;max-width:560px;">
        </div>
        <div class="form-group" style="margin-bottom:0;">
          <label for="bento_tile_<?= $bi ?>_subtitle">Subtitle <span style="font-weight:normal;color:#666;">(optional)</span></label>
          <input type="text" id="bento_tile_<?= $bi ?>_subtitle" value="<?= htmlspecialchars((string)($tile['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" style="width:100%;max-width:560px;" placeholder="e.g. Open Daily • 6AM - 10PM">
        </div>
      </div>
      <?php endfor; ?>
      <textarea id="home_facilities_bento_json" name="home_facilities_bento_json" style="display:none;"><?= htmlspecialchars($bentoJsonSerialized, ENT_QUOTES, 'UTF-8') ?></textarea>
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
        <label for="home_location_map_embed_url">Custom map embed URL <span style="font-weight:normal;color:#666;">(optional)</span></label>
        <textarea id="home_location_map_embed_url" name="home_location_map_embed_url" rows="2" style="font-family:monospace;font-size:12px;width:100%;max-width:640px;" placeholder="https://www.google.com/maps/embed?..."><?= htmlspecialchars(trim((string)($sectionsArray['home_location_map_embed_url'] ?? '')), ENT_QUOTES, 'UTF-8') ?></textarea>
        <p class="form-help">If set, this URL is used for the homepage map <strong>instead of</strong> building one from the address. Paste the <code>src</code> from Google Maps → Share → Embed a map.</p>
      </div>
      <div class="form-group">
        <label>Fallback map image <span style="font-weight:normal;color:#666;">(only if no address / no embed)</span></label>
        <button type="button" class="btn btn-outline" onclick="openMediaModal('home_location_map_image','home_location_map_preview')">Select</button>
        <input type="hidden" id="home_location_map_image" name="home_location_map_image" value="<?= hsec($sectionsArray, 'home_location_map_image') ?>">
        <div id="home_location_map_preview" class="image-preview" style="<?= !empty($sectionsArray['home_location_map_image']) ? 'display:block;' : 'display:none;' ?>">
          <?php if (!empty($sectionsArray['home_location_map_image'])): ?>
            <img src="<?= SITE_URL . ltrim($sectionsArray['home_location_map_image'], '/') ?>" style="max-width:500px;max-height:280px;" alt="">
          <?php endif; ?>
        </div>
        <p class="form-help">The homepage shows a <strong>live Google Map</strong> from the address above when possible: add your <strong>Google Maps API key</strong> under <a href="<?= ADMIN_URL ?>pages/settings.php">Settings</a> for the official embed, or the site falls back to a basic embed without a key. This image is used only when the address is empty and no custom embed URL is set.</p>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Save all</button>
</form>

<script>
(function () {
  var map = {
    hero_bg: 'hero_bg_preview',
    hero_slide_0: 'hero_slide_0_preview',
    hero_slide_1: 'hero_slide_1_preview',
    hero_slide_2: 'hero_slide_2_preview',
    hero_slide_3: 'hero_slide_3_preview',
    hero_slide_4: 'hero_slide_4_preview',
    home_philosophy_main_img: 'home_philosophy_main_preview',
    home_dining_image_top: 'home_dining_image_top_preview',
    home_dining_image_bottom: 'home_dining_image_bottom_preview',
    home_location_map_image: 'home_location_map_preview',
    bento_tile_0_image: 'bento_tile_0_preview',
    bento_tile_1_image: 'bento_tile_1_preview',
    bento_tile_2_image: 'bento_tile_2_preview',
    bento_tile_3_image: 'bento_tile_3_preview'
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
function syncHeroGallerySlidesJson() {
  var paths = [];
  for (var i = 0; i < 5; i++) {
    var el = document.getElementById('hero_slide_' + i);
    var p = el && el.value ? String(el.value).trim() : '';
    if (p) paths.push(p);
  }
  var ta = document.getElementById('hero_gallery_slides_json');
  if (ta) ta.value = JSON.stringify(paths);
}

function syncHomeBentoJsonFromTiles() {
  var out = [];
  for (var i = 0; i < 4; i++) {
    var imgEl = document.getElementById('bento_tile_' + i + '_image');
    var titleEl = document.getElementById('bento_tile_' + i + '_title');
    var subEl = document.getElementById('bento_tile_' + i + '_subtitle');
    out.push({
      image: imgEl ? String(imgEl.value || '').trim() : '',
      title: titleEl ? String(titleEl.value || '') : '',
      subtitle: subEl ? String(subEl.value || '') : ''
    });
  }
  var ta = document.getElementById('home_facilities_bento_json');
  if (ta) ta.value = JSON.stringify(out);
}
</script>

<script>
document.getElementById('homepageForm').addEventListener('submit', function (e) {
  e.preventDefault();
  syncHeroGallerySlidesJson();
  syncHomeBentoJsonFromTiles();
  var form = this;
  var typeOverrides = {
    hero_title: 'html',
    booking_widget_html: 'html',
    home_philosophy_title_html: 'html',
    home_philosophy_body: 'html',
    home_dining_heading_html: 'html',
    home_facilities_bento_json: 'json',
    home_location_bullets_json: 'json',
    hero_gallery_slides_json: 'json'
  };
  savePageForm(form, 'index', typeOverrides)
    .then(function () { showToast('Saved', 'success'); })
    .catch(function (err) { showToast(err.message || 'Save failed', 'error'); });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
