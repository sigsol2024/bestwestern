<?php
$pageTitle = 'About Page';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/header.php';

$sections = [];
try {
    $stmt = $pdo->prepare("SELECT section_key, content FROM page_sections WHERE page = 'about'");
    $stmt->execute();
    foreach ($stmt->fetchAll() as $row) {
        $sections[$row['section_key']] = $row['content'];
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
}
$pageActiveSettingKey = 'page_active_about';
$pageIsActive = ((string) getSetting($pageActiveSettingKey, cms_default_setting($pageActiveSettingKey, '1'))) === '1';
?>

<form id="aboutPageForm">
  <div class="card"><div class="card-header"><h2>Meta</h2></div><div style="padding:20px;">
    <div class="form-group">
      <label for="page_title">Browser title</label>
      <input type="text" id="page_title" name="page_title" value="<?= sanitize($sections['page_title'] ?? 'Our Story') ?>">
    </div>
  </div></div>

  <div class="card"><div class="card-header"><h2>Hero (Our Story)</h2></div><div style="padding:20px;">
    <div class="form-group">
      <label for="hero_title_html">Heading (HTML allowed)</label>
      <textarea id="hero_title_html" name="hero_title_html" rows="2"><?= htmlspecialchars($sections['hero_title_html'] ?? 'Our Story', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
    <div class="form-group">
      <label for="hero_property_line">Kicker line</label>
      <input type="text" id="hero_property_line" name="hero_property_line" value="<?= sanitize($sections['hero_property_line'] ?? 'A Best Western Plus Property') ?>">
    </div>
    <div class="form-group">
      <label for="hero_intro">Intro paragraph</label>
      <textarea id="hero_intro" name="hero_intro" rows="3"><?= sanitize($sections['hero_intro'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label>Hero image</label>
      <button type="button" class="btn btn-outline" onclick="openMediaModal('hero_bg','hero_bg_preview')">Select</button>
      <input type="hidden" id="hero_bg" name="hero_bg" value="<?= sanitize($sections['hero_bg'] ?? '') ?>">
      <div id="hero_bg_preview" class="image-preview" style="<?= !empty($sections['hero_bg']) ? 'display:block;' : 'display:none;' ?>">
        <?php if (!empty($sections['hero_bg'])): ?>
          <img src="<?= SITE_URL . ltrim($sections['hero_bg'], '/') ?>" style="max-width:500px;">
        <?php endif; ?>
      </div>
    </div>
    <div class="form-group">
      <label for="hero_bg_alt">Hero image alt text</label>
      <input type="text" id="hero_bg_alt" name="hero_bg_alt" value="<?= sanitize($sections['hero_bg_alt'] ?? 'Luxury hotel exterior with modernist architecture') ?>">
    </div>
  </div></div>

  <div class="card"><div class="card-header"><h2>Philosophy</h2></div><div style="padding:20px;">
    <div class="form-row">
      <div class="form-group">
        <label for="philosophy_kicker">Kicker</label>
        <input type="text" id="philosophy_kicker" name="philosophy_kicker" value="<?= sanitize($sections['philosophy_kicker'] ?? 'Philosophy') ?>">
      </div>
      <div class="form-group">
        <label for="philosophy_title">Title</label>
        <input type="text" id="philosophy_title" name="philosophy_title" value="<?= sanitize($sections['philosophy_title'] ?? 'The Art of Living Intentionally') ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="philosophy_p1">Paragraph 1</label>
      <textarea id="philosophy_p1" name="philosophy_p1" rows="3"><?= sanitize($sections['philosophy_p1'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label for="philosophy_p2">Paragraph 2</label>
      <textarea id="philosophy_p2" name="philosophy_p2" rows="3"><?= sanitize($sections['philosophy_p2'] ?? '') ?></textarea>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Image 1</label>
        <button type="button" class="btn btn-outline" onclick="openMediaModal('philosophy_image_1','philosophy_image_1_preview')">Select</button>
        <input type="hidden" id="philosophy_image_1" name="philosophy_image_1" value="<?= sanitize($sections['philosophy_image_1'] ?? '') ?>">
        <div id="philosophy_image_1_preview" class="image-preview" style="<?= !empty($sections['philosophy_image_1']) ? 'display:block;' : 'display:none;' ?>">
          <?php if (!empty($sections['philosophy_image_1'])): ?><img src="<?= SITE_URL . ltrim($sections['philosophy_image_1'], '/') ?>" style="max-width:320px;"><?php endif; ?>
        </div>
      </div>
      <div class="form-group">
        <label>Image 2</label>
        <button type="button" class="btn btn-outline" onclick="openMediaModal('philosophy_image_2','philosophy_image_2_preview')">Select</button>
        <input type="hidden" id="philosophy_image_2" name="philosophy_image_2" value="<?= sanitize($sections['philosophy_image_2'] ?? '') ?>">
        <div id="philosophy_image_2_preview" class="image-preview" style="<?= !empty($sections['philosophy_image_2']) ? 'display:block;' : 'display:none;' ?>">
          <?php if (!empty($sections['philosophy_image_2'])): ?><img src="<?= SITE_URL . ltrim($sections['philosophy_image_2'], '/') ?>" style="max-width:320px;"><?php endif; ?>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="philosophy_image_1_alt">Image 1 alt</label>
        <input type="text" id="philosophy_image_1_alt" name="philosophy_image_1_alt" value="<?= sanitize($sections['philosophy_image_1_alt'] ?? 'Hotel lobby details') ?>">
      </div>
      <div class="form-group">
        <label for="philosophy_image_2_alt">Image 2 alt</label>
        <input type="text" id="philosophy_image_2_alt" name="philosophy_image_2_alt" value="<?= sanitize($sections['philosophy_image_2_alt'] ?? 'Luxury spa environment') ?>">
      </div>
    </div>
  </div></div>

  <div class="card"><div class="card-header"><h2>Rooted in the Delta</h2></div><div style="padding:20px;">
    <div class="form-group">
      <label for="culture_title">Title</label>
      <input type="text" id="culture_title" name="culture_title" value="<?= sanitize($sections['culture_title'] ?? 'Rooted in the Delta') ?>">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="culture_feature_1_title">Feature 1 title</label>
        <input type="text" id="culture_feature_1_title" name="culture_feature_1_title" value="<?= sanitize($sections['culture_feature_1_title'] ?? 'Indigenous Soul') ?>">
      </div>
      <div class="form-group">
        <label for="culture_feature_2_title">Feature 2 title</label>
        <input type="text" id="culture_feature_2_title" name="culture_feature_2_title" value="<?= sanitize($sections['culture_feature_2_title'] ?? 'Local Artistry') ?>">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="culture_feature_1_body">Feature 1 body</label>
        <textarea id="culture_feature_1_body" name="culture_feature_1_body" rows="3"><?= sanitize($sections['culture_feature_1_body'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label for="culture_feature_2_body">Feature 2 body</label>
        <textarea id="culture_feature_2_body" name="culture_feature_2_body" rows="3"><?= sanitize($sections['culture_feature_2_body'] ?? '') ?></textarea>
      </div>
    </div>
    <div class="form-group">
      <label>Section image</label>
      <button type="button" class="btn btn-outline" onclick="openMediaModal('culture_image','culture_image_preview')">Select</button>
      <input type="hidden" id="culture_image" name="culture_image" value="<?= sanitize($sections['culture_image'] ?? '') ?>">
      <div id="culture_image_preview" class="image-preview" style="<?= !empty($sections['culture_image']) ? 'display:block;' : 'display:none;' ?>">
        <?php if (!empty($sections['culture_image'])): ?><img src="<?= SITE_URL . ltrim($sections['culture_image'], '/') ?>" style="max-width:500px;"><?php endif; ?>
      </div>
    </div>
    <div class="form-group">
      <label for="culture_image_alt">Image alt</label>
      <input type="text" id="culture_image_alt" name="culture_image_alt" value="<?= sanitize($sections['culture_image_alt'] ?? 'Aerial cinematic view of a winding river') ?>">
    </div>
  </div></div>

  <div class="card"><div class="card-header"><h2>Heritage</h2></div><div style="padding:20px;">
    <div class="form-group">
      <label for="heritage_title">Title</label>
      <input type="text" id="heritage_title" name="heritage_title" value="<?= sanitize($sections['heritage_title'] ?? 'Our Heritage') ?>">
    </div>
    <div class="form-group">
      <label for="heritage_body">Body</label>
      <textarea id="heritage_body" name="heritage_body" rows="3"><?= sanitize($sections['heritage_body'] ?? '') ?></textarea>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="heritage_link_label">Link label</label>
        <input type="text" id="heritage_link_label" name="heritage_link_label" value="<?= sanitize($sections['heritage_link_label'] ?? 'EXPLORE OUR TIMELINE') ?>">
      </div>
      <div class="form-group">
        <label for="heritage_link_href">Link URL</label>
        <input type="text" id="heritage_link_href" name="heritage_link_href" value="<?= sanitize($sections['heritage_link_href'] ?? '#') ?>">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Image 1</label>
        <button type="button" class="btn btn-outline" onclick="openMediaModal('heritage_image_1','heritage_image_1_preview')">Select</button>
        <input type="hidden" id="heritage_image_1" name="heritage_image_1" value="<?= sanitize($sections['heritage_image_1'] ?? '') ?>">
        <div id="heritage_image_1_preview" class="image-preview" style="<?= !empty($sections['heritage_image_1']) ? 'display:block;' : 'display:none;' ?>">
          <?php if (!empty($sections['heritage_image_1'])): ?><img src="<?= SITE_URL . ltrim($sections['heritage_image_1'], '/') ?>" style="max-width:360px;"><?php endif; ?>
        </div>
      </div>
      <div class="form-group">
        <label>Image 2</label>
        <button type="button" class="btn btn-outline" onclick="openMediaModal('heritage_image_2','heritage_image_2_preview')">Select</button>
        <input type="hidden" id="heritage_image_2" name="heritage_image_2" value="<?= sanitize($sections['heritage_image_2'] ?? '') ?>">
        <div id="heritage_image_2_preview" class="image-preview" style="<?= !empty($sections['heritage_image_2']) ? 'display:block;' : 'display:none;' ?>">
          <?php if (!empty($sections['heritage_image_2'])): ?><img src="<?= SITE_URL . ltrim($sections['heritage_image_2'], '/') ?>" style="max-width:360px;"><?php endif; ?>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="heritage_image_1_alt">Image 1 alt</label>
        <input type="text" id="heritage_image_1_alt" name="heritage_image_1_alt" value="<?= sanitize($sections['heritage_image_1_alt'] ?? 'Professional hotel staff') ?>">
      </div>
      <div class="form-group">
        <label for="heritage_image_2_alt">Image 2 alt</label>
        <input type="text" id="heritage_image_2_alt" name="heritage_image_2_alt" value="<?= sanitize($sections['heritage_image_2_alt'] ?? 'Sophisticated hotel bar area') ?>">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="heritage_quote">Quote</label>
        <input type="text" id="heritage_quote" name="heritage_quote" value="<?= sanitize($sections['heritage_quote'] ?? '"To serve is a privilege; to curate is an art."') ?>">
      </div>
      <div class="form-group">
        <label for="heritage_quote_byline">Quote byline</label>
        <input type="text" id="heritage_quote_byline" name="heritage_quote_byline" value="<?= sanitize($sections['heritage_quote_byline'] ?? '— Our Founding Philosophy') ?>">
      </div>
    </div>
  </div></div>

  <div class="card"><div class="card-header"><h2>Final Call to Experience</h2></div><div style="padding:20px;">
    <div class="form-group">
      <label>Background image</label>
      <button type="button" class="btn btn-outline" onclick="openMediaModal('experience_bg','experience_bg_preview')">Select</button>
      <input type="hidden" id="experience_bg" name="experience_bg" value="<?= sanitize($sections['experience_bg'] ?? '') ?>">
      <div id="experience_bg_preview" class="image-preview" style="<?= !empty($sections['experience_bg']) ? 'display:block;' : 'display:none;' ?>">
        <?php if (!empty($sections['experience_bg'])): ?><img src="<?= SITE_URL . ltrim($sections['experience_bg'], '/') ?>" style="max-width:500px;"><?php endif; ?>
      </div>
    </div>
    <div class="form-group">
      <label for="experience_bg_alt">Background image alt</label>
      <input type="text" id="experience_bg_alt" name="experience_bg_alt" value="<?= sanitize($sections['experience_bg_alt'] ?? 'Luxury hotel swimming pool at twilight') ?>">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="experience_title">Title</label>
        <input type="text" id="experience_title" name="experience_title" value="<?= sanitize($sections['experience_title'] ?? 'Write Your Own Story') ?>">
      </div>
      <div class="form-group">
        <label for="experience_button_label">Button label</label>
        <input type="text" id="experience_button_label" name="experience_button_label" value="<?= sanitize($sections['experience_button_label'] ?? 'Begin Your Stay') ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="experience_button_href">Button URL</label>
      <input type="text" id="experience_button_href" name="experience_button_href" value="<?= sanitize($sections['experience_button_href'] ?? '/rooms') ?>">
    </div>
  </div></div>

  <div class="card"><div class="card-header"><h2>Page visibility</h2></div><div style="padding:20px;">
    <label style="display:flex;align-items:center;gap:8px;">
      <input type="checkbox" name="__page_active" value="1" <?= $pageIsActive ? 'checked' : '' ?>>
      <span>Active</span>
    </label>
  </div></div>

  <button type="submit" class="btn btn-primary">Save all</button>
</form>

<script>
(function () {
  var map = {
    hero_bg: 'hero_bg_preview',
    philosophy_image_1: 'philosophy_image_1_preview',
    philosophy_image_2: 'philosophy_image_2_preview',
    culture_image: 'culture_image_preview',
    heritage_image_1: 'heritage_image_1_preview',
    heritage_image_2: 'heritage_image_2_preview',
    experience_bg: 'experience_bg_preview'
  };
  window.insertSelectedMediaOverride = function () {
    var tid = mediaModalState.targetInputId || '';
    var selected = mediaModalState.selectedMedia ? [mediaModalState.selectedMedia] : [];
    if (!selected || selected.length === 0) return false;

    // Single-image fields
    var pid = map[tid];
    if (!pid) return false;
    var s = selected[0];
    document.getElementById(tid).value = s.path;
    var p = document.getElementById(pid);
    p.style.display = 'block';
    p.innerHTML = '<img src="<?= SITE_URL ?>' + s.path.replace(/^\/+/, '') + '" style="max-width:500px;">';
    closeMediaModal();
    return true;
  };
})();
document.getElementById('aboutPageForm').addEventListener('submit', function (e) {
  e.preventDefault();
  savePageForm(this, 'about', {}, { pageActiveSettingKey: 'page_active_about' })
    .then(function () { showToast('Saved', 'success'); })
    .catch(function (err) { showToast(err.message || 'Save failed', 'error'); });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
