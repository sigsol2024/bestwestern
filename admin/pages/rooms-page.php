<?php
$pageTitle = 'Rooms Listing Page';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/header.php';

$sectionsArray = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM page_sections WHERE page = 'rooms' ORDER BY section_key");
    $stmt->execute();
    foreach ($stmt->fetchAll() as $section) {
        $sectionsArray[$section['section_key']] = $section['content'];
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
}
$pageActiveSettingKey = 'page_active_rooms';
$pageIsActive = ((string) getSetting($pageActiveSettingKey, cms_default_setting($pageActiveSettingKey, '1'))) === '1';
?>

<form id="roomsPageForm">
  <div class="card">
    <div class="card-header"><h2>Rooms listing hero</h2></div>
    <div style="padding:20px;">
      <p class="form-help">Room cards on this page are loaded from the database. Edit individual rooms under <a href="<?= ADMIN_URL ?>pages/rooms/list.php">Rooms</a>.</p>
      <div class="form-group">
        <label for="page_title">Browser / SEO title</label>
        <input type="text" id="page_title" name="page_title" value="<?= sanitize($sectionsArray['page_title'] ?? 'Rooms & Suites') ?>">
      </div>
      <div class="form-group">
        <label for="hero_title">Hero title</label>
        <input type="text" id="hero_title" name="hero_title" value="<?= sanitize($sectionsArray['hero_title'] ?? 'Rooms & Suites') ?>">
      </div>
      <div class="form-group">
        <label for="hero_subtitle">Hero subtitle</label>
        <textarea id="hero_subtitle" name="hero_subtitle" rows="3"><?= sanitize($sectionsArray['hero_subtitle'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label for="hero_kicker">Listing kicker (line above each room title on /rooms)</label>
        <input type="text" id="hero_kicker" name="hero_kicker" value="<?= sanitize($sectionsArray['hero_kicker'] ?? 'Accommodations') ?>">
      </div>
      <div class="form-group">
        <label for="amenities_reminder_title">Amenities reminder title</label>
        <input type="text" id="amenities_reminder_title" name="amenities_reminder_title" value="<?= sanitize($sectionsArray['amenities_reminder_title'] ?? 'All suites include:') ?>">
      </div>
      <div class="form-group">
        <label for="amenities_reminder_items_json">Amenities reminder items (JSON array)</label>
        <textarea id="amenities_reminder_items_json" name="amenities_reminder_items_json" rows="4" style="font-family:monospace;font-size:12px;"><?= htmlspecialchars($sectionsArray['amenities_reminder_items_json'] ?? '["WIFI","BREAKFAST","TOILETRIES","TURNDOWN"]', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="card card--nested" style="margin-top:12px;">
        <div class="card-header"><h3>Bottom section shells (public /rooms)</h3></div>
        <div class="card-body">
          <p class="form-help">Tailwind classes on the outer <code>section</code> for the amenities strip and the closing CTA. Matches the live template defaults; clear a field and save to reset spacing to the coded defaults.</p>
          <div class="form-group">
            <label for="amenities_reminder_section_classes">Amenities reminder section classes</label>
            <input type="text" id="amenities_reminder_section_classes" name="amenities_reminder_section_classes" value="<?= sanitize($sectionsArray['amenities_reminder_section_classes'] ?? 'bg-surface-container py-[54px]') ?>" style="font-family:monospace;font-size:12px;width:100%;max-width:640px;">
          </div>
          <div class="form-group">
            <label for="final_cta_section_classes">Final CTA section classes</label>
            <input type="text" id="final_cta_section_classes" name="final_cta_section_classes" value="<?= sanitize($sectionsArray['final_cta_section_classes'] ?? 'py-7 my-0 text-center bg-white') ?>" style="font-family:monospace;font-size:12px;width:100%;max-width:640px;">
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="final_cta_title">Final CTA title</label>
          <input type="text" id="final_cta_title" name="final_cta_title" value="<?= sanitize($sectionsArray['final_cta_title'] ?? 'Need help choosing?') ?>">
        </div>
        <div class="form-group">
          <label for="final_cta_label">Final CTA button label</label>
          <input type="text" id="final_cta_label" name="final_cta_label" value="<?= sanitize($sectionsArray['final_cta_label'] ?? 'Contact Reservations') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="final_cta_href">Final CTA URL</label>
          <input type="text" id="final_cta_href" name="final_cta_href" value="<?= sanitize($sectionsArray['final_cta_href'] ?? '/contact') ?>">
        </div>
        <div class="form-group">
          <label for="final_cta_body">Final CTA body</label>
          <textarea id="final_cta_body" name="final_cta_body" rows="2"><?= sanitize($sectionsArray['final_cta_body'] ?? 'Our dedicated concierge is available 24/7 to help you select the perfect sanctuary for your stay in Yenagoa.') ?></textarea>
        </div>
      </div>
      <div class="card card--nested" style="margin-top:12px;">
        <div class="card-header"><h3>Signature suite highlight</h3></div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group">
              <label for="signature_badge">Badge</label>
              <input type="text" id="signature_badge" name="signature_badge" value="<?= sanitize($sectionsArray['signature_badge'] ?? 'Signature Suite') ?>">
            </div>
            <div class="form-group">
              <label for="signature_kicker">Kicker</label>
              <input type="text" id="signature_kicker" name="signature_kicker" value="<?= sanitize($sectionsArray['signature_kicker'] ?? 'The Pinnacle of Living') ?>">
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label>Hero background</label>
        <button type="button" class="btn btn-outline" onclick="openMediaModal('hero_bg','hero_bg_preview')">Select image</button>
        <input type="hidden" id="hero_bg" name="hero_bg" value="<?= sanitize($sectionsArray['hero_bg'] ?? '') ?>">
        <div id="hero_bg_preview" class="image-preview" style="<?= !empty($sectionsArray['hero_bg']) ? 'display:block;' : 'display:none;' ?>">
          <?php if (!empty($sectionsArray['hero_bg'])): ?>
            <img src="<?= SITE_URL . ltrim($sectionsArray['hero_bg'], '/') ?>" style="max-width:500px;max-height:280px;">
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header"><h2>Page visibility</h2></div>
    <div style="padding:20px;">
      <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="__page_active" value="1" <?= $pageIsActive ? 'checked' : '' ?>>
        <span>Active</span>
      </label>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Save</button>
</form>

<script>
window.insertSelectedMediaOverride = function () {
  var selected = mediaModalState.selectedMedia;
  if (!selected || mediaModalState.targetInputId !== 'hero_bg') return false;
  document.getElementById('hero_bg').value = selected.path;
  var preview = document.getElementById('hero_bg_preview');
  preview.style.display = 'block';
  preview.innerHTML = '<img src="<?= SITE_URL ?>' + selected.path.replace(/^\/+/, '') + '" style="max-width:500px;max-height:280px;">';
  closeMediaModal();
  return true;
};
document.getElementById('roomsPageForm').addEventListener('submit', function (e) {
  e.preventDefault();
  savePageForm(this, 'rooms', { amenities_reminder_items_json: 'json' }, { pageActiveSettingKey: 'page_active_rooms' })
    .then(function () { showToast('Saved', 'success'); })
    .catch(function (err) { showToast(err.message || 'Save failed', 'error'); });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
