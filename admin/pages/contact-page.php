<?php
$pageTitle = 'Contact Page';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/header.php';

$sections = [];
try {
    $stmt = $pdo->prepare("SELECT section_key, content FROM page_sections WHERE page = 'contact'");
    $stmt->execute();
    foreach ($stmt->fetchAll() as $row) {
        $sections[$row['section_key']] = $row['content'];
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
}
$pageActiveSettingKey = 'page_active_contact';
$pageIsActive = ((string) getSetting($pageActiveSettingKey, cms_default_setting($pageActiveSettingKey, '1'))) === '1';
?>

<form id="contactPageForm">
  <div class="card">
    <div class="card-header"><h2>Meta & intro</h2></div>
    <div style="padding:20px;">
      <div class="form-group">
        <label for="page_title">Page title</label>
        <input type="text" id="page_title" name="page_title" value="<?= sanitize($sections['page_title'] ?? 'Contact Us') ?>">
      </div>
      <div class="form-group">
        <label for="intro_kicker">Kicker</label>
        <input type="text" id="intro_kicker" name="intro_kicker" value="<?= sanitize($sections['intro_kicker'] ?? 'Concierge Services') ?>">
      </div>
      <div class="form-group">
        <label for="intro_title">Heading</label>
        <input type="text" id="intro_title" name="intro_title" value="<?= sanitize($sections['intro_title'] ?? 'Get in Touch') ?>">
      </div>
      <div class="form-group">
        <label for="intro_body">Intro body</label>
        <textarea id="intro_body" name="intro_body" rows="3"><?= sanitize($sections['intro_body'] ?? '') ?></textarea>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Contact details & map</h2></div>
    <div style="padding:20px;">
      <div class="form-group">
        <label for="address_html">Address (HTML, use &lt;br/&gt;)</label>
        <textarea id="address_html" name="address_html" rows="4"><?= htmlspecialchars($sections['address_html'] ?? "123 Lorem Avenue<br/>\nIpsum City<br/>\nCountry", ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-group">
        <label for="map_address">Map address (used for embed)</label>
        <input type="text" id="map_address" name="map_address" value="<?= sanitize($sections['map_address'] ?? '') ?>" placeholder="123 Lorem Avenue, Ipsum City, Country">
        <p class="form-help">Optional. If empty, we auto-derive from the Address field.</p>
      </div>
      <div class="form-group">
        <label for="map_embed_url">Custom map embed URL (optional)</label>
        <input type="text" id="map_embed_url" name="map_embed_url" value="<?= sanitize($sections['map_embed_url'] ?? '') ?>" placeholder="https://www.google.com/maps/embed?...">
        <p class="form-help">Paste a Google Maps embed URL (Share → Embed). If set, it overrides the generated map.</p>
      </div>
      <div class="form-group">
        <label for="directions_href">Directions link</label>
        <input type="text" id="directions_href" name="directions_href" value="<?= sanitize($sections['directions_href'] ?? '#') ?>">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="concierge_phone">Concierge phone</label>
          <input type="text" id="concierge_phone" name="concierge_phone" value="<?= sanitize($sections['concierge_phone'] ?? cms_default_setting('footer_phone')) ?>">
        </div>
        <div class="form-group">
          <label for="inquiries_email">Inquiries email</label>
          <input type="text" id="inquiries_email" name="inquiries_email" value="<?= sanitize($sections['inquiries_email'] ?? cms_default_setting('contact_email')) ?>">
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
  return false;
};
document.getElementById('contactPageForm').addEventListener('submit', function (e) {
  e.preventDefault();
  savePageForm(this, 'contact', {}, { pageActiveSettingKey: 'page_active_contact' })
    .then(function () { showToast('Saved', 'success'); })
    .catch(function (err) { showToast(err.message || 'Save failed', 'error'); });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
