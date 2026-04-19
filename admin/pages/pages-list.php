<?php
$pageTitle = 'Pages';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/header.php';

$availablePages = [
  'index' => ['name' => 'Homepage', 'editor' => 'homepage.php', 'icon' => 'fa-home'],
  'about' => ['name' => 'About', 'editor' => 'about-page.php', 'icon' => 'fa-info-circle'],
  'rooms' => ['name' => 'Rooms listing (hero & intro)', 'editor' => 'rooms-page.php', 'icon' => 'fa-list'],
  'contact' => ['name' => 'Contact', 'editor' => 'contact-page.php', 'icon' => 'fa-envelope'],
  'gallery' => ['name' => 'Gallery', 'editor' => 'gallery-page.php', 'icon' => 'fa-images'],
  'dining' => ['name' => 'Dining', 'editor' => 'dining-page.php', 'icon' => 'fa-utensils'],
  'amenities' => ['name' => 'Amenities', 'editor' => 'amenities-page.php', 'icon' => 'fa-spa'],
  'hotel-policy' => ['name' => 'Hotel Policy', 'editor' => 'hotel-policy-page.php', 'icon' => 'fa-clipboard-list'],
  'privacy-policy' => ['name' => 'Privacy Policy', 'editor' => 'privacy-policy-page.php', 'icon' => 'fa-user-shield'],
  'terms-and-conditions' => ['name' => 'Terms & Conditions', 'editor' => 'terms-and-conditions-page.php', 'icon' => 'fa-file-contract'],
];

try {
    $stmt = $pdo->prepare("SELECT DISTINCT page FROM page_sections ORDER BY page");
    $stmt->execute();
    $pagesInDb = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $pagesInDb = [];
}

$pageStatuses = [];
foreach ($availablePages as $pageKey => $info) {
    $settingKey = 'page_active_' . $pageKey;
    $defaultActive = $pageKey === 'index' ? '1' : cms_default_setting($settingKey, '1');
    $pageStatuses[$pageKey] = ((string) getSetting($settingKey, $defaultActive)) === '1';
}
?>

<div class="card">
  <div class="card-header"><h2>Pages</h2></div>
  <div style="padding:20px;">
    <p style="margin-bottom:20px;color:var(--text-muted);">Edit copy and images per page. <strong>Rooms</strong> are managed under <a href="<?= ADMIN_URL ?>pages/rooms/list.php">Rooms</a> (including Featured for the homepage slider).</p>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;">
      <?php foreach ($availablePages as $pageKey => $info): ?>
        <div class="page-card">
          <div style="display:flex;align-items:center;margin-bottom:15px;">
            <i class="fas <?= $info['icon'] ?>" style="font-size:32px;color:var(--primary-color);margin-right:15px;"></i>
            <div>
              <div class="page-card__title-row">
                <h3 style="margin:0;font-size:18px;font-weight:600;"><?= sanitize($info['name']) ?></h3>
                <?php if ($pageKey === 'index'): ?>
                  <span class="page-status-badge page-status-badge--home">Always live</span>
                <?php else: ?>
                  <span class="page-status-badge <?= $pageStatuses[$pageKey] ? 'page-status-badge--live' : 'page-status-badge--draft' ?>">
                    <?= $pageStatuses[$pageKey] ? 'Live' : 'Draft' ?>
                  </span>
                <?php endif; ?>
              </div>
              <?php if (in_array($pageKey, $pagesInDb)): ?>
                <small style="color: var(--success-color);"><i class="fas fa-check-circle"></i> Has saved content</small>
              <?php else: ?>
                <small style="color: var(--text-muted);"><i class="fas fa-circle"></i> Using site defaults</small>
              <?php endif; ?>
            </div>
          </div>
          <?php if ($pageKey !== 'index'): ?>
            <div class="page-status-panel">
              <div>
                <strong class="page-status-panel__title"><?= $pageStatuses[$pageKey] ? 'Live' : 'Draft' ?></strong>
                <small class="text-muted">Inactive pages redirect visitors to the homepage.</small>
              </div>
              <label style="display:flex;align-items:center;gap:8px;white-space:nowrap;">
                <input
                  type="checkbox"
                  class="js-page-status-toggle"
                  data-page="<?= sanitize($pageKey) ?>"
                  data-setting-key="<?= sanitize('page_active_' . $pageKey) ?>"
                  <?= $pageStatuses[$pageKey] ? 'checked' : '' ?>
                >
                <span>Active</span>
              </label>
            </div>
          <?php else: ?>
            <div class="page-status-panel">
              <div>
                <strong class="page-status-panel__title">Always live</strong>
                <small class="text-muted">Homepage stays available and acts as the redirect target for inactive pages.</small>
              </div>
            </div>
          <?php endif; ?>
          <a href="<?= ADMIN_URL ?>pages/<?= $info['editor'] ?>" class="btn btn-primary" style="width:100%;"><i class="fas fa-edit"></i> Edit Page</a>
        </div>
      <?php endforeach; ?>
    </div>
    <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border-color);">
      <a href="<?= ADMIN_URL ?>pages/rooms/list.php" class="btn btn-outline"><i class="fas fa-bed"></i> Manage all rooms</a>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.js-page-status-toggle').forEach(function (toggle) {
  toggle.addEventListener('change', function () {
    const settingKey = toggle.getAttribute('data-setting-key');
    const isActive = toggle.checked ? '1' : '0';
    const panel = toggle.closest('.page-status-panel');
    const card = toggle.closest('.page-card');
    const titleEl = panel ? panel.querySelector('.page-status-panel__title') : null;
    const badgeEl = card ? card.querySelector('.page-status-badge') : null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const originalChecked = !toggle.checked;

    toggle.disabled = true;

    fetch('<?= ADMIN_URL ?>api/settings.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({ [settingKey]: isActive })
    })
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      if (data.success) {
        if (titleEl) titleEl.textContent = toggle.checked ? 'Live' : 'Draft';
        if (badgeEl) {
          badgeEl.textContent = toggle.checked ? 'Live' : 'Draft';
          badgeEl.classList.toggle('page-status-badge--live', toggle.checked);
          badgeEl.classList.toggle('page-status-badge--draft', !toggle.checked);
        }
        if (typeof showToast === 'function') {
          showToast('Page status updated.', 'success');
        }
      } else {
        toggle.checked = originalChecked;
        if (typeof showToast === 'function') {
          showToast(data.message || 'Failed to update page status.', 'error');
        }
      }
    })
    .catch(function () {
      toggle.checked = originalChecked;
      if (typeof showToast === 'function') {
        showToast('Failed to update page status.', 'error');
      }
    })
    .finally(function () {
      toggle.disabled = false;
    });
  });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
