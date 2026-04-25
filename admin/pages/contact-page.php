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
    <div class="card-header"><h2>Hero</h2></div>
    <div style="padding:20px;">
      <div class="form-group">
        <label for="page_title">Page title</label>
        <input type="text" id="page_title" name="page_title" value="<?= sanitize($sections['page_title'] ?? 'Contact Us') ?>">
      </div>
      <div class="form-group">
        <label for="hero_kicker">Kicker</label>
        <input type="text" id="hero_kicker" name="hero_kicker" value="<?= sanitize($sections['hero_kicker'] ?? 'Connect With Us') ?>">
      </div>
      <div class="form-group">
        <label for="hero_title">Heading</label>
        <input type="text" id="hero_title" name="hero_title" value="<?= sanitize($sections['hero_title'] ?? 'Get in Touch') ?>">
      </div>
      <div class="form-group">
        <label for="hero_intro">Intro text</label>
        <textarea id="hero_intro" name="hero_intro" rows="4"><?= sanitize($sections['hero_intro'] ?? "Whether you are planning a grand event or seeking a quiet retreat, our curators are ready to assist you in crafting the perfect experience.") ?></textarea>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Directory Panel</h2></div>
    <div style="padding:20px;">
      <div class="form-group">
        <label for="directory_title">Directory heading</label>
        <input type="text" id="directory_title" name="directory_title" value="<?= sanitize($sections['directory_title'] ?? 'Directory') ?>">
      </div>
      <div class="form-group">
        <label for="estate_label">Address label</label>
        <input type="text" id="estate_label" name="estate_label" value="<?= sanitize($sections['estate_label'] ?? 'The Estate') ?>">
      </div>
      <div class="form-group">
        <label for="address_html">Address (HTML, use &lt;br/&gt;)</label>
        <textarea id="address_html" name="address_html" rows="4"><?= htmlspecialchars($sections['address_html'] ?? "10 Julius Berger Road, Swali,<br/>\nYenagoa, Bayelsa State, Nigeria", ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="reservations_label">Reservations label</label>
          <input type="text" id="reservations_label" name="reservations_label" value="<?= sanitize($sections['reservations_label'] ?? 'Reservations') ?>">
        </div>
        <div class="form-group">
          <label for="reservations_phone">Reservations phone</label>
          <input type="text" id="reservations_phone" name="reservations_phone" value="<?= sanitize($sections['reservations_phone'] ?? cms_default_setting('footer_phone')) ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="front_desk_label">Front desk label</label>
          <input type="text" id="front_desk_label" name="front_desk_label" value="<?= sanitize($sections['front_desk_label'] ?? 'Front Desk') ?>">
        </div>
        <div class="form-group">
          <label for="front_desk_phone">Front desk phone</label>
          <input type="text" id="front_desk_phone" name="front_desk_phone" value="<?= sanitize($sections['front_desk_phone'] ?? cms_default_setting('footer_phone')) ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="email_1_label">Email block 1 label</label>
          <input type="text" id="email_1_label" name="email_1_label" value="<?= sanitize($sections['email_1_label'] ?? 'Central Liaison') ?>">
        </div>
        <div class="form-group">
          <label for="email_1_value">Email block 1 address</label>
          <input type="text" id="email_1_value" name="email_1_value" value="<?= sanitize($sections['email_1_value'] ?? cms_default_setting('contact_email')) ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="email_2_label">Email block 2 label</label>
          <input type="text" id="email_2_label" name="email_2_label" value="<?= sanitize($sections['email_2_label'] ?? 'Events & Sales') ?>">
        </div>
        <div class="form-group">
          <label for="email_2_value">Email block 2 address</label>
          <input type="text" id="email_2_value" name="email_2_value" value="<?= sanitize($sections['email_2_value'] ?? 'sales@bestwesternplusyenagoa.com') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="email_3_label">Email block 3 label</label>
          <input type="text" id="email_3_label" name="email_3_label" value="<?= sanitize($sections['email_3_label'] ?? 'Concierge') ?>">
        </div>
        <div class="form-group">
          <label for="email_3_value">Email block 3 address</label>
          <input type="text" id="email_3_value" name="email_3_value" value="<?= sanitize($sections['email_3_value'] ?? 'concierge@bestwesternplusyenagoa.com') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="media_kit_label">Media kit CTA label</label>
          <input type="text" id="media_kit_label" name="media_kit_label" value="<?= sanitize($sections['media_kit_label'] ?? 'Download Media Kit') ?>">
        </div>
        <div class="form-group">
          <label for="media_kit_href">Media kit CTA URL</label>
          <input type="text" id="media_kit_href" name="media_kit_href" value="<?= sanitize($sections['media_kit_href'] ?? '#') ?>">
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Map / Location Panel</h2></div>
    <div style="padding:20px;">
      <div class="form-group">
        <label for="location_title">Location card title</label>
        <input type="text" id="location_title" name="location_title" value="<?= sanitize($sections['location_title'] ?? 'Our Location') ?>">
      </div>
      <div class="form-group">
        <label for="location_body">Location card body</label>
        <textarea id="location_body" name="location_body" rows="3"><?= sanitize($sections['location_body'] ?? "Situated in the heart of Swali, our hotel offers seamless access to the city's commercial and cultural hubs.") ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="directions_label">Directions button label</label>
          <input type="text" id="directions_label" name="directions_label" value="<?= sanitize($sections['directions_label'] ?? 'Get Directions') ?>">
        </div>
        <div class="form-group">
          <label for="directions_href">Directions URL (optional)</label>
          <input type="text" id="directions_href" name="directions_href" value="<?= sanitize($sections['directions_href'] ?? '#') ?>">
          <p class="form-help">Leave as # to auto-generate from map address.</p>
        </div>
      </div>
      <div class="form-group">
        <label for="map_address">Map address (used for embed)</label>
        <input type="text" id="map_address" name="map_address" value="<?= sanitize($sections['map_address'] ?? '') ?>" placeholder="123 Lorem Avenue, Ipsum City, Country">
        <p class="form-help">Optional. If empty, map embed uses the address above.</p>
      </div>
      <div class="form-group">
        <label for="map_embed_url">Custom Google map embed URL (optional)</label>
        <input type="text" id="map_embed_url" name="map_embed_url" value="<?= sanitize($sections['map_embed_url'] ?? '') ?>" placeholder="https://www.google.com/maps/embed?...">
        <p class="form-help">If provided, this overrides generated map URL.</p>
      </div>
      <div class="form-group">
        <label for="map_fallback_image">Map fallback image URL (used when no map URL is available)</label>
        <input type="text" id="map_fallback_image" name="map_fallback_image" value="<?= sanitize($sections['map_fallback_image'] ?? '') ?>">
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
