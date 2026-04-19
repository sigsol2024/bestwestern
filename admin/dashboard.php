<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$stats = [
    'pages' => 0,
    'sections' => 0,
    'rooms' => 0,
    'active_rooms' => 0,
    'media' => 0,
    'admins' => 0,
];

$recentActivity = [];

try {
    $stats['pages'] = (int) $pdo->query("SELECT COUNT(DISTINCT page) FROM page_sections")->fetchColumn();
    $stats['sections'] = (int) $pdo->query("SELECT COUNT(*) FROM page_sections")->fetchColumn();
    $stats['rooms'] = (int) $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
    $stats['active_rooms'] = (int) $pdo->query("SELECT COUNT(*) FROM rooms WHERE is_active = 1")->fetchColumn();
    $stats['media'] = (int) $pdo->query("SELECT COUNT(*) FROM media")->fetchColumn();
    $stats['admins'] = (int) $pdo->query("SELECT COUNT(*) FROM admin_users WHERE is_active = 1")->fetchColumn();

    $activitySql = "
        SELECT *
        FROM (
            SELECT
                'Page content updated' AS action_label,
                CONCAT('Page: ', page, ' / ', section_key) AS action_detail,
                updated_at AS action_time,
                'Pages' AS action_area,
                ? AS action_href
            FROM page_sections
            WHERE updated_at IS NOT NULL

            UNION ALL

            SELECT
                'Setting updated' AS action_label,
                CONCAT('Setting: ', setting_key) AS action_detail,
                updated_at AS action_time,
                'Settings' AS action_area,
                ? AS action_href
            FROM site_settings
            WHERE updated_at IS NOT NULL

            UNION ALL

            SELECT
                CASE WHEN is_active = 1 THEN 'Room updated' ELSE 'Room saved as inactive' END AS action_label,
                CONCAT(title, ' (', slug, ')') AS action_detail,
                updated_at AS action_time,
                'Rooms' AS action_area,
                ? AS action_href
            FROM rooms
            WHERE updated_at IS NOT NULL

            UNION ALL

            SELECT
                'Media uploaded' AS action_label,
                COALESCE(original_name, filename) AS action_detail,
                uploaded_at AS action_time,
                'Media' AS action_area,
                ? AS action_href
            FROM media
            WHERE uploaded_at IS NOT NULL

            UNION ALL

            SELECT
                'Admin signed in' AS action_label,
                username AS action_detail,
                last_login AS action_time,
                'Profile' AS action_area,
                ? AS action_href
            FROM admin_users
            WHERE last_login IS NOT NULL
        ) AS recent_activity
        WHERE action_time IS NOT NULL
        ORDER BY action_time DESC
        LIMIT 12
    ";

    $activityStmt = $pdo->prepare($activitySql);
    $activityStmt->execute([
        ADMIN_URL . 'pages/pages-list.php',
        ADMIN_URL . 'pages/settings.php',
        ADMIN_URL . 'pages/rooms/list.php',
        ADMIN_URL . 'pages/media.php',
        ADMIN_URL . 'pages/profile.php',
    ]);
    $recentActivity = $activityStmt->fetchAll();
} catch (PDOException $e) {
    error_log("Dashboard error: " . $e->getMessage());
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-intro">
  <h1>Dashboard</h1>
  <p>Quick access to content, media, settings, and recent site activity.</p>
</div>

<div class="dashboard-stats">
  <div class="dashboard-stat-card">
    <span class="dashboard-stat-card__label">Pages</span>
    <strong class="dashboard-stat-card__value"><?= number_format($stats['pages']) ?></strong>
    <span class="dashboard-stat-card__meta"><?= number_format($stats['sections']) ?> editable sections</span>
  </div>
  <div class="dashboard-stat-card">
    <span class="dashboard-stat-card__label">Rooms</span>
    <strong class="dashboard-stat-card__value"><?= number_format($stats['rooms']) ?></strong>
    <span class="dashboard-stat-card__meta"><?= number_format($stats['active_rooms']) ?> active</span>
  </div>
  <div class="dashboard-stat-card">
    <span class="dashboard-stat-card__label">Media Items</span>
    <strong class="dashboard-stat-card__value"><?= number_format($stats['media']) ?></strong>
    <span class="dashboard-stat-card__meta">Library uploads</span>
  </div>
  <div class="dashboard-stat-card">
    <span class="dashboard-stat-card__label">Admins</span>
    <strong class="dashboard-stat-card__value"><?= number_format($stats['admins']) ?></strong>
    <span class="dashboard-stat-card__meta">Active accounts</span>
  </div>
</div>

<div class="dashboard-quick-actions">
  <a class="dashboard-quick-action" href="<?= ADMIN_URL ?>pages/pages-list.php">
    <span class="dashboard-quick-action__icon"><i class="fas fa-file-alt"></i></span>
    <span class="dashboard-quick-action__title">Edit Pages</span>
    <span class="dashboard-quick-action__desc">Open the page editor list.</span>
  </a>
  <a class="dashboard-quick-action" href="<?= ADMIN_URL ?>pages/rooms/list.php">
    <span class="dashboard-quick-action__icon"><i class="fas fa-bed"></i></span>
    <span class="dashboard-quick-action__title">Manage Rooms</span>
    <span class="dashboard-quick-action__desc">Update room content, order, and status.</span>
  </a>
  <a class="dashboard-quick-action" href="<?= ADMIN_URL ?>pages/media.php">
    <span class="dashboard-quick-action__icon"><i class="fas fa-folder"></i></span>
    <span class="dashboard-quick-action__title">Media Library</span>
    <span class="dashboard-quick-action__desc">Upload and reuse images across the site.</span>
  </a>
  <a class="dashboard-quick-action" href="<?= ADMIN_URL ?>pages/settings.php">
    <span class="dashboard-quick-action__icon"><i class="fas fa-cog"></i></span>
    <span class="dashboard-quick-action__title">Settings</span>
    <span class="dashboard-quick-action__desc">Manage branding, theme, nav, and contact details.</span>
  </a>
  <a class="dashboard-quick-action" href="<?= ADMIN_URL ?>pages/profile.php">
    <span class="dashboard-quick-action__icon"><i class="fas fa-user"></i></span>
    <span class="dashboard-quick-action__title">Admin Profile</span>
    <span class="dashboard-quick-action__desc">Update your email address and password.</span>
  </a>
  <a class="dashboard-quick-action" href="<?= SITE_URL ?>" target="_blank" rel="noopener noreferrer">
    <span class="dashboard-quick-action__icon"><i class="fas fa-external-link-alt"></i></span>
    <span class="dashboard-quick-action__title">View Site</span>
    <span class="dashboard-quick-action__desc">Open the public website in a new tab.</span>
  </a>
</div>

<div class="card">
  <div class="card-header card-header--split">
    <div>
      <h2>Recent Activity</h2>
      <p class="text-muted">Last 12 changes derived from content, settings, rooms, media, and login activity.</p>
    </div>
  </div>
  <div class="table-wrapper">
    <table class="table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Details</th>
          <th>Area</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($recentActivity)): ?>
          <?php foreach ($recentActivity as $row): ?>
            <tr>
              <td><strong><?= sanitize((string) $row['action_label']) ?></strong></td>
              <td>
                <?php if (!empty($row['action_href'])): ?>
                  <a href="<?= sanitize((string) $row['action_href']) ?>"><?= sanitize((string) $row['action_detail']) ?></a>
                <?php else: ?>
                  <?= sanitize((string) $row['action_detail']) ?>
                <?php endif; ?>
              </td>
              <td><?= sanitize((string) $row['action_area']) ?></td>
              <td><?= sanitize(date('M j, Y g:i A', strtotime((string) $row['action_time']))) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-muted">No recent activity found yet.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

