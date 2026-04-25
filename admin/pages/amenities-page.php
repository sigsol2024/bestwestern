<?php
$pageTitle = 'Amenities Page';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/header.php';

$sections = [];
try {
    $stmt = $pdo->prepare("SELECT section_key, content FROM page_sections WHERE page = 'amenities'");
    $stmt->execute();
    foreach ($stmt->fetchAll() as $row) {
        $sections[$row['section_key']] = $row['content'];
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
}

$cmsDefaults = require __DIR__ . '/../../includes/cms-defaults.php';
$defaultJson = json_encode($cmsDefaults['amenities_sections'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$raw = $sections['sections_json'] ?? '';
if (trim($raw) === '') {
    $raw = $defaultJson;
}

$decoded = json_decode($raw, true);
if (!is_array($decoded)) {
    $decoded = $cmsDefaults['amenities_sections'];
}
$amenitiesItems = [];
for ($i = 0; $i < 7; $i++) {
    $amenitiesItems[$i] = isset($decoded[$i]) && is_array($decoded[$i]) ? $decoded[$i] : [];
}
$servicesRaw = $sections['services_items_json'] ?? '';
$servicesDecoded = json_decode((string)$servicesRaw, true);
if (!is_array($servicesDecoded) || $servicesDecoded === []) {
    $servicesDecoded = [
        ['title' => '24h Concierge', 'subtitle' => 'Dedicated to your every whim'],
        ['title' => 'Airport Transfer', 'subtitle' => 'Luxury chauffeur fleet'],
        ['title' => 'Laundry & Press', 'subtitle' => 'Same-day valet service'],
        ['title' => 'High-Speed WiFi', 'subtitle' => 'Gigabit fiber throughout'],
        ['title' => 'Secure Parking', 'subtitle' => '24/7 guarded premises'],
        ['title' => 'Room Service', 'subtitle' => 'Global dining 24/7'],
    ];
}
$chambersRaw = $sections['business_chambers_json'] ?? '';
$chambersDecoded = json_decode((string)$chambersRaw, true);
if (!is_array($chambersDecoded) || $chambersDecoded === []) {
    $chambersDecoded = [
        ['title' => 'Nun Chamber', 'body' => 'Ideal for high-level board meetings and strategic workshops. Ergonomic executive seating and absolute privacy.', 'badge' => '50 Guest Capacity'],
        ['title' => 'Epele Chamber', 'body' => 'A quiet and focused space for breakout sessions, smaller seminars, and intimate corporate presentations.', 'badge' => '30 Guest Capacity'],
        ['title' => 'Business Center', 'body' => 'Full administrative support with high-speed workstations, scanning, and printing services available 24/7.', 'badge' => 'Resident Concierge'],
    ];
}

$amenitiesSlotMeta = [
    ['label' => 'Hero Section', 'help' => 'Top full-screen hero on /amenities (kicker, title HTML, body, hero image).'],
    ['label' => 'Dining Row 1', 'help' => 'First dining row: large image left, copy right.'],
    ['label' => 'Dining Row 2', 'help' => 'Second dining row: copy left, wide image right.'],
    ['label' => 'Wellness Row 1', 'help' => 'Wellness intro and first row content.'],
    ['label' => 'Wellness Row 2', 'help' => 'Second wellness row content.'],
    ['label' => 'Wellness Row 3', 'help' => 'Third wellness row content.'],
    ['label' => 'Business & Events', 'help' => 'Meetings/events band with headline, copy, and wide image.'],
];

$pageActiveSettingKey = 'page_active_amenities';
$pageIsActive = ((string) getSetting($pageActiveSettingKey, cms_default_setting($pageActiveSettingKey, '1'))) === '1';
?>

<form id="amenitiesPageForm">
  <div class="card">
    <div class="card-header"><h2>Main content sections (frontend order)</h2></div>
    <div style="padding:20px;">
      <p class="form-help" style="margin-top:0;">
        The order below matches the live amenities page from top to bottom.
      </p>
      <textarea id="sections_json" name="sections_json" style="display:none;"><?= htmlspecialchars($raw, ENT_QUOTES, 'UTF-8') ?></textarea>

      <?php for ($i = 0; $i < 7; $i++):
          $slot = $amenitiesItems[$i];
          $bg = sanitize((string)($slot['bg'] ?? ''));
          $kicker = sanitize((string)($slot['kicker'] ?? ''));
          $titleHtml = (string)($slot['title_html'] ?? '');
          $body = (string)($slot['body'] ?? '');
          $gradient = sanitize((string)($slot['gradient'] ?? ''));
          $icon = sanitize((string)($slot['icon'] ?? ''));
          $layout = sanitize((string)($slot['layout'] ?? 'bottom'));
          $btn = sanitize((string)($slot['btn'] ?? ''));
          $btnHref = sanitize((string)($slot['btn_href'] ?? ''));
          $gallery = $slot['gallery'] ?? ($slot['gallery_images'] ?? []);
          if (!is_array($gallery)) {
              $gallery = [];
          }
          $galleryJson = htmlspecialchars(json_encode($gallery, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
          $slotMeta = $amenitiesSlotMeta[$i] ?? ['label' => 'Module ' . ($i + 1), 'help' => ''];
          $slotLabel = (string)($slotMeta['label'] ?? ('Module ' . ($i + 1)));
          $help = (string)($slotMeta['help'] ?? '');
          $bgId = 'amenity_slot_' . $i . '_bg';
          $bgPrevId = 'amenity_slot_' . $i . '_bg_preview';
          $galId = 'amenity_slot_' . $i . '_gallery_images';
          $galPrevId = 'amenity_slot_' . $i . '_gallery_preview';
      ?>
      <div class="card card--nested js-amenity-slot" data-slot="<?= (int)$i ?>" style="margin-bottom: 14px;">
        <div class="card-header">
          <h3 style="margin:0;"><?= sanitize($slotLabel) ?></h3>
        </div>
        <div class="card-body card-body--stack">
          <?php if ($help !== ''): ?>
            <p class="form-help" style="margin-top:0;"><?= sanitize($help) ?></p>
          <?php endif; ?>

          <input type="hidden" class="js-meta-gradient" value="<?= $gradient ?>">
          <input type="hidden" class="js-meta-icon" value="<?= $icon ?>">
          <input type="hidden" class="js-meta-layout" value="<?= $layout ?>">
          <input type="hidden" class="js-meta-btn" value="<?= $btn ?>">
          <input type="hidden" class="js-meta-btn-href" value="<?= $btnHref ?>">

          <div class="form-row">
            <div class="form-group" style="flex:1;">
              <label for="amenity_slot_<?= (int)$i ?>_kicker">Kicker</label>
              <input type="text" id="amenity_slot_<?= (int)$i ?>_kicker" class="form-control js-kicker" value="<?= $kicker ?>">
            </div>
          </div>

          <div class="form-group">
            <label for="amenity_slot_<?= (int)$i ?>_title_html">Title (HTML)</label>
            <textarea id="amenity_slot_<?= (int)$i ?>_title_html" class="form-control js-title-html mono" rows="3"><?= htmlspecialchars($titleHtml, ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>

          <div class="form-group">
            <label for="amenity_slot_<?= (int)$i ?>_body">Body</label>
            <textarea id="amenity_slot_<?= (int)$i ?>_body" class="form-control js-body" rows="4"><?= htmlspecialchars($body, ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>

          <?php if ($i === 1): ?>
          <div class="form-row">
            <div class="form-group">
              <label for="dining1_breakfast_label_inline">Breakfast label</label>
              <input type="text" id="dining1_breakfast_label_inline" name="dining1_breakfast_label" value="<?= sanitize($sections['dining1_breakfast_label'] ?? 'Breakfast') ?>">
            </div>
            <div class="form-group">
              <label for="dining1_breakfast_time_inline">Breakfast time</label>
              <input type="text" id="dining1_breakfast_time_inline" name="dining1_breakfast_time" value="<?= sanitize($sections['dining1_breakfast_time'] ?? '06:30 - 10:30') ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="dining1_dinner_label_inline">Dinner label</label>
              <input type="text" id="dining1_dinner_label_inline" name="dining1_dinner_label" value="<?= sanitize($sections['dining1_dinner_label'] ?? 'Dinner') ?>">
            </div>
            <div class="form-group">
              <label for="dining1_dinner_time_inline">Dinner time</label>
              <input type="text" id="dining1_dinner_time_inline" name="dining1_dinner_time" value="<?= sanitize($sections['dining1_dinner_time'] ?? '18:00 - 22:00') ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="dining1_menu_label_inline">Menu link label</label>
              <input type="text" id="dining1_menu_label_inline" name="dining1_menu_label" value="<?= sanitize($sections['dining1_menu_label'] ?? 'View Full Menu') ?>">
            </div>
            <div class="form-group">
              <label for="dining1_menu_href_inline">Menu link URL</label>
              <input type="text" id="dining1_menu_href_inline" name="dining1_menu_href" value="<?= sanitize($sections['dining1_menu_href'] ?? '#') ?>">
            </div>
          </div>
          <?php endif; ?>

          <?php if ($i === 2): ?>
          <div class="form-row">
            <div class="form-group">
              <label for="dining2_service_note_inline">Service note</label>
              <input type="text" id="dining2_service_note_inline" name="dining2_service_note" value="<?= sanitize($sections['dining2_service_note'] ?? 'Evening Service Only') ?>">
            </div>
            <div class="form-group">
              <label for="dining2_hours_inline">Service hours</label>
              <input type="text" id="dining2_hours_inline" name="dining2_hours" value="<?= sanitize($sections['dining2_hours'] ?? '18:00 - 23:00') ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="dining2_cta_label_inline">Button label</label>
              <input type="text" id="dining2_cta_label_inline" name="dining2_cta_label" value="<?= sanitize($sections['dining2_cta_label'] ?? 'Book Table') ?>">
            </div>
            <div class="form-group">
              <label for="dining2_cta_href_inline">Button URL</label>
              <input type="text" id="dining2_cta_href_inline" name="dining2_cta_href" value="<?= sanitize($sections['dining2_cta_href'] ?? '#') ?>">
            </div>
          </div>
          <?php endif; ?>

          <?php if ($i === 3): ?>
          <div class="form-row">
            <div class="form-group">
              <label for="wellness1_left_label_inline">Meta left label</label>
              <input type="text" id="wellness1_left_label_inline" name="wellness1_left_label" value="<?= sanitize($sections['wellness1_left_label'] ?? 'Hours') ?>">
            </div>
            <div class="form-group">
              <label for="wellness1_left_value_inline">Meta left value</label>
              <input type="text" id="wellness1_left_value_inline" name="wellness1_left_value" value="<?= sanitize($sections['wellness1_left_value'] ?? '06:00 - 22:00') ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="wellness1_right_label_inline">Meta right label</label>
              <input type="text" id="wellness1_right_label_inline" name="wellness1_right_label" value="<?= sanitize($sections['wellness1_right_label'] ?? 'Amenities') ?>">
            </div>
            <div class="form-group">
              <label for="wellness1_right_value_inline">Meta right value</label>
              <input type="text" id="wellness1_right_value_inline" name="wellness1_right_value" value="<?= sanitize($sections['wellness1_right_value'] ?? 'Poolside Service') ?>">
            </div>
          </div>
          <?php endif; ?>

          <?php if ($i === 4): ?>
          <div class="form-group">
            <label for="wellness2_badge_text_inline">Badge text</label>
            <input type="text" id="wellness2_badge_text_inline" name="wellness2_badge_text" value="<?= sanitize($sections['wellness2_badge_text'] ?? '24 / 7 Access for Residents') ?>">
          </div>
          <?php endif; ?>

          <?php if ($i === 5): ?>
          <div class="form-row">
            <div class="form-group">
              <label for="wellness3_footer_note_inline">Footer note</label>
              <input type="text" id="wellness3_footer_note_inline" name="wellness3_footer_note" value="<?= sanitize($sections['wellness3_footer_note'] ?? 'Daily 09:00 - 20:00') ?>">
            </div>
            <div class="form-group">
              <label for="wellness3_link_label_inline">Footer link label</label>
              <input type="text" id="wellness3_link_label_inline" name="wellness3_link_label" value="<?= sanitize($sections['wellness3_link_label'] ?? 'Treatments Menu') ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="wellness3_link_href_inline">Footer link URL</label>
            <input type="text" id="wellness3_link_href_inline" name="wellness3_link_href" value="<?= sanitize($sections['wellness3_link_href'] ?? '#') ?>">
          </div>
          <?php endif; ?>

          <?php if ($i === 6): ?>
          <div class="form-group">
            <label for="business_akassa_title_inline">Featured venue title</label>
            <input type="text" id="business_akassa_title_inline" name="business_akassa_title" value="<?= sanitize($sections['business_akassa_title'] ?? 'Akassa Conference Hall') ?>">
          </div>
          <div class="form-group">
            <label for="business_akassa_body_inline">Featured venue description</label>
            <textarea id="business_akassa_body_inline" name="business_akassa_body" rows="3"><?= htmlspecialchars($sections['business_akassa_body'] ?? 'Our premier venue for large-scale summits, product launches, and social galas. Features fully integrated AV systems and cinematic lighting.', ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="business_akassa_capacity_value_inline">Capacity value</label>
              <input type="text" id="business_akassa_capacity_value_inline" name="business_akassa_capacity_value" value="<?= sanitize($sections['business_akassa_capacity_value'] ?? '500') ?>">
            </div>
            <div class="form-group">
              <label for="business_akassa_capacity_label_inline">Capacity label</label>
              <input type="text" id="business_akassa_capacity_label_inline" name="business_akassa_capacity_label" value="<?= sanitize($sections['business_akassa_capacity_label'] ?? 'Guest Capacity') ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="business_akassa_cta_label_inline">Button label</label>
              <input type="text" id="business_akassa_cta_label_inline" name="business_akassa_cta_label" value="<?= sanitize($sections['business_akassa_cta_label'] ?? 'Request Inquiry') ?>">
            </div>
            <div class="form-group">
              <label for="business_akassa_cta_href_inline">Button URL</label>
              <input type="text" id="business_akassa_cta_href_inline" name="business_akassa_cta_href" value="<?= sanitize($sections['business_akassa_cta_href'] ?? '#') ?>">
            </div>
          </div>
          <?php endif; ?>

          <div class="form-group">
            <label>Primary image</label>
            <div style="display:flex; gap: 10px; align-items:center; flex-wrap:wrap;">
              <button type="button" class="btn btn-outline js-pick-bg">Select from media</button>
              <input type="text" id="<?= sanitize($bgId) ?>" class="form-control js-bg" value="<?= $bg ?>" placeholder="/assets/uploads/... or https://...">
            </div>
            <div id="<?= sanitize($bgPrevId) ?>" class="image-preview" style="display:none;margin-top:10px;"></div>
          </div>

          <input type="hidden" id="<?= sanitize($galId) ?>" class="js-gallery" value="<?= $galleryJson ?>">
        </div>
      </div>
      <?php endfor; ?>

      <details style="margin-top:14px;">
        <summary style="cursor:pointer; color: var(--text-muted);">Advanced JSON (optional)</summary>
        <textarea id="sections_json_advanced" rows="18" class="mono" style="margin-top:10px;"></textarea>
        <div style="margin-top:10px;">
          <button type="button" class="btn btn-outline btn-sm" id="amenitiesApplyJsonBtn">Apply JSON</button>
        </div>
      </details>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Dining Row 1 Details</h2></div>
    <div style="padding:20px;">
      <div class="form-row">
        <div class="form-group">
          <label for="dining1_breakfast_label">Breakfast label</label>
          <input type="text" id="dining1_breakfast_label" name="dining1_breakfast_label" value="<?= sanitize($sections['dining1_breakfast_label'] ?? 'Breakfast') ?>">
        </div>
        <div class="form-group">
          <label for="dining1_breakfast_time">Breakfast time</label>
          <input type="text" id="dining1_breakfast_time" name="dining1_breakfast_time" value="<?= sanitize($sections['dining1_breakfast_time'] ?? '06:30 - 10:30') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="dining1_dinner_label">Dinner label</label>
          <input type="text" id="dining1_dinner_label" name="dining1_dinner_label" value="<?= sanitize($sections['dining1_dinner_label'] ?? 'Dinner') ?>">
        </div>
        <div class="form-group">
          <label for="dining1_dinner_time">Dinner time</label>
          <input type="text" id="dining1_dinner_time" name="dining1_dinner_time" value="<?= sanitize($sections['dining1_dinner_time'] ?? '18:00 - 22:00') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="dining1_menu_label">Menu link label</label>
          <input type="text" id="dining1_menu_label" name="dining1_menu_label" value="<?= sanitize($sections['dining1_menu_label'] ?? 'View Full Menu') ?>">
        </div>
        <div class="form-group">
          <label for="dining1_menu_href">Menu link URL</label>
          <input type="text" id="dining1_menu_href" name="dining1_menu_href" value="<?= sanitize($sections['dining1_menu_href'] ?? '#') ?>">
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Dining Row 2 Details</h2></div>
    <div style="padding:20px;">
      <div class="form-row">
        <div class="form-group">
          <label for="dining2_service_note">Service note</label>
          <input type="text" id="dining2_service_note" name="dining2_service_note" value="<?= sanitize($sections['dining2_service_note'] ?? 'Evening Service Only') ?>">
        </div>
        <div class="form-group">
          <label for="dining2_hours">Service hours</label>
          <input type="text" id="dining2_hours" name="dining2_hours" value="<?= sanitize($sections['dining2_hours'] ?? '18:00 - 23:00') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="dining2_cta_label">Button label</label>
          <input type="text" id="dining2_cta_label" name="dining2_cta_label" value="<?= sanitize($sections['dining2_cta_label'] ?? 'Book Table') ?>">
        </div>
        <div class="form-group">
          <label for="dining2_cta_href">Button URL</label>
          <input type="text" id="dining2_cta_href" name="dining2_cta_href" value="<?= sanitize($sections['dining2_cta_href'] ?? '#') ?>">
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Lounge Section</h2></div>
    <div style="padding:20px;">
      <div class="form-row">
        <div class="form-group">
          <label for="lounge_kicker">Kicker</label>
          <input type="text" id="lounge_kicker" name="lounge_kicker" value="<?= sanitize($sections['lounge_kicker'] ?? 'Evening Ambience') ?>">
        </div>
        <div class="form-group">
          <label for="lounge_hours_label">Hours label</label>
          <input type="text" id="lounge_hours_label" name="lounge_hours_label" value="<?= sanitize($sections['lounge_hours_label'] ?? 'Operating Hours') ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="lounge_title_html">Title (HTML)</label>
        <textarea id="lounge_title_html" name="lounge_title_html" rows="2" class="mono"><?= htmlspecialchars($sections['lounge_title_html'] ?? 'The Lounge &amp; Bar', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-group">
        <label for="lounge_body">Body</label>
        <textarea id="lounge_body" name="lounge_body" rows="4"><?= htmlspecialchars($sections['lounge_body'] ?? 'Premium spirits, signature cocktails, and live jazz sessions. The perfect venue for winding down or meeting colleagues in a refined atmosphere.', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-group">
        <label for="lounge_hours">Hours text</label>
        <input type="text" id="lounge_hours" name="lounge_hours" value="<?= sanitize($sections['lounge_hours'] ?? '12:00 - Midnight Daily') ?>">
      </div>
      <div class="form-group">
        <label>Lounge image</label>
        <div style="margin-bottom:10px;">
          <button type="button" class="btn btn-outline" onclick="openMediaModal('lounge_image','lounge_image_preview')">Select image</button>
        </div>
        <input type="hidden" id="lounge_image" name="lounge_image" value="<?= sanitize($sections['lounge_image'] ?? '') ?>">
        <div id="lounge_image_preview" class="image-preview" style="<?= !empty($sections['lounge_image']) ? 'display:block;' : 'display:none;' ?>">
          <?php if (!empty($sections['lounge_image'])): ?>
            <img src="<?= SITE_URL . ltrim($sections['lounge_image'], '/') ?>" style="max-width:500px;max-height:280px;" alt="">
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Wellness Row Details</h2></div>
    <div style="padding:20px;">
      <h3 style="margin:0 0 12px 0;font-size:0.98rem;">Wellness Row 1 (Pool-style meta)</h3>
      <div class="form-row">
        <div class="form-group">
          <label for="wellness1_left_label">Left label</label>
          <input type="text" id="wellness1_left_label" name="wellness1_left_label" value="<?= sanitize($sections['wellness1_left_label'] ?? 'Hours') ?>">
        </div>
        <div class="form-group">
          <label for="wellness1_left_value">Left value</label>
          <input type="text" id="wellness1_left_value" name="wellness1_left_value" value="<?= sanitize($sections['wellness1_left_value'] ?? '06:00 - 22:00') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="wellness1_right_label">Right label</label>
          <input type="text" id="wellness1_right_label" name="wellness1_right_label" value="<?= sanitize($sections['wellness1_right_label'] ?? 'Amenities') ?>">
        </div>
        <div class="form-group">
          <label for="wellness1_right_value">Right value</label>
          <input type="text" id="wellness1_right_value" name="wellness1_right_value" value="<?= sanitize($sections['wellness1_right_value'] ?? 'Poolside Service') ?>">
        </div>
      </div>

      <h3 style="margin:14px 0 12px 0;font-size:0.98rem;">Wellness Row 2 (Gym badge)</h3>
      <div class="form-group">
        <label for="wellness2_badge_text">Badge text</label>
        <input type="text" id="wellness2_badge_text" name="wellness2_badge_text" value="<?= sanitize($sections['wellness2_badge_text'] ?? '24 / 7 Access for Residents') ?>">
      </div>

      <h3 style="margin:14px 0 12px 0;font-size:0.98rem;">Wellness Row 3 (Spa footer)</h3>
      <div class="form-row">
        <div class="form-group">
          <label for="wellness3_footer_note">Footer note</label>
          <input type="text" id="wellness3_footer_note" name="wellness3_footer_note" value="<?= sanitize($sections['wellness3_footer_note'] ?? 'Daily 09:00 - 20:00') ?>">
        </div>
        <div class="form-group">
          <label for="wellness3_link_label">Footer link label</label>
          <input type="text" id="wellness3_link_label" name="wellness3_link_label" value="<?= sanitize($sections['wellness3_link_label'] ?? 'Treatments Menu') ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="wellness3_link_href">Footer link URL</label>
        <input type="text" id="wellness3_link_href" name="wellness3_link_href" value="<?= sanitize($sections['wellness3_link_href'] ?? '#') ?>">
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Business Section Details</h2></div>
    <div style="padding:20px;">
      <h3 style="margin:0 0 12px 0;font-size:0.98rem;">Featured Venue (Akassa block)</h3>
      <div class="form-group">
        <label for="business_akassa_title">Venue title</label>
        <input type="text" id="business_akassa_title" name="business_akassa_title" value="<?= sanitize($sections['business_akassa_title'] ?? 'Akassa Conference Hall') ?>">
      </div>
      <div class="form-group">
        <label for="business_akassa_body">Venue description</label>
        <textarea id="business_akassa_body" name="business_akassa_body" rows="4"><?= htmlspecialchars($sections['business_akassa_body'] ?? 'Our premier venue for large-scale summits, product launches, and social galas. Features fully integrated AV systems and cinematic lighting.', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="business_akassa_capacity_value">Capacity value</label>
          <input type="text" id="business_akassa_capacity_value" name="business_akassa_capacity_value" value="<?= sanitize($sections['business_akassa_capacity_value'] ?? '500') ?>">
        </div>
        <div class="form-group">
          <label for="business_akassa_capacity_label">Capacity label</label>
          <input type="text" id="business_akassa_capacity_label" name="business_akassa_capacity_label" value="<?= sanitize($sections['business_akassa_capacity_label'] ?? 'Guest Capacity') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="business_akassa_cta_label">Button label</label>
          <input type="text" id="business_akassa_cta_label" name="business_akassa_cta_label" value="<?= sanitize($sections['business_akassa_cta_label'] ?? 'Request Inquiry') ?>">
        </div>
        <div class="form-group">
          <label for="business_akassa_cta_href">Button URL</label>
          <input type="text" id="business_akassa_cta_href" name="business_akassa_cta_href" value="<?= sanitize($sections['business_akassa_cta_href'] ?? '#') ?>">
        </div>
      </div>

      <h3 style="margin:18px 0 12px 0;font-size:0.98rem;">Other Chambers</h3>
      <textarea id="business_chambers_json" name="business_chambers_json" style="display:none;"><?= htmlspecialchars(json_encode($chambersDecoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?></textarea>
      <div id="businessChambersWrap">
        <?php foreach ($chambersDecoded as $ch):
            $chTitle = sanitize((string)($ch['title'] ?? ''));
            $chBody = sanitize((string)($ch['body'] ?? ''));
            $chBadge = sanitize((string)($ch['badge'] ?? ''));
        ?>
        <div class="card card--nested js-business-chamber" style="margin-bottom:10px;">
          <div class="card-body" style="padding:12px 14px;">
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control js-chamber-title" value="<?= $chTitle ?>">
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control js-chamber-body" rows="3"><?= htmlspecialchars($chBody, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="form-group" style="margin-bottom:10px;">
              <label>Badge text</label>
              <input type="text" class="form-control js-chamber-badge" value="<?= $chBadge ?>">
            </div>
            <div style="display:flex;justify-content:flex-end;">
              <button type="button" class="btn btn-outline btn-sm js-remove-chamber">Remove</button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="button" class="btn btn-outline btn-sm" id="addBusinessChamberBtn">Add chamber</button>
      <template id="businessChamberTemplate">
        <div class="card card--nested js-business-chamber" style="margin-bottom:10px;">
          <div class="card-body" style="padding:12px 14px;">
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control js-chamber-title" value="">
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control js-chamber-body" rows="3"></textarea>
            </div>
            <div class="form-group" style="margin-bottom:10px;">
              <label>Badge text</label>
              <input type="text" class="form-control js-chamber-badge" value="">
            </div>
            <div style="display:flex;justify-content:flex-end;">
              <button type="button" class="btn btn-outline btn-sm js-remove-chamber">Remove</button>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Services Section</h2></div>
    <div style="padding:20px;">
      <div class="form-row">
        <div class="form-group" style="flex:1;">
          <label for="services_kicker">Services kicker</label>
          <input type="text" id="services_kicker" name="services_kicker" value="<?= sanitize($sections['services_kicker'] ?? 'Impeccable Care') ?>">
        </div>
        <div class="form-group" style="flex:1;">
          <label for="services_title">Services title</label>
          <input type="text" id="services_title" name="services_title" value="<?= sanitize($sections['services_title'] ?? 'Signature Guest Services') ?>">
        </div>
      </div>
      <p class="form-help">Service cards are now editable as normal fields. Use "Add service item" for more rows.</p>
      <textarea id="services_items_json" name="services_items_json" style="display:none;"><?= htmlspecialchars(json_encode($servicesDecoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?></textarea>
      <div id="servicesItemsWrap">
        <?php foreach ($servicesDecoded as $si => $srv):
            $srvTitle = sanitize((string)($srv['title'] ?? ''));
            $srvSubtitle = sanitize((string)($srv['subtitle'] ?? ($srv['body'] ?? '')));
        ?>
        <div class="card card--nested js-service-item" style="margin-bottom:10px;">
          <div class="card-body" style="padding:12px 14px;">
            <div class="form-row">
              <div class="form-group" style="flex:1;">
                <label>Service title</label>
                <input type="text" class="form-control js-service-title" value="<?= $srvTitle ?>">
              </div>
              <div class="form-group" style="flex:1;">
                <label>Service subtitle</label>
                <input type="text" class="form-control js-service-subtitle" value="<?= $srvSubtitle ?>">
              </div>
            </div>
            <div style="display:flex;justify-content:flex-end;">
              <button type="button" class="btn btn-outline btn-sm js-remove-service">Remove</button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="button" class="btn btn-outline btn-sm" id="addServiceItemBtn">Add service item</button>
      <template id="serviceItemTemplate">
        <div class="card card--nested js-service-item" style="margin-bottom:10px;">
          <div class="card-body" style="padding:12px 14px;">
            <div class="form-row">
              <div class="form-group" style="flex:1;">
                <label>Service title</label>
                <input type="text" class="form-control js-service-title" value="">
              </div>
              <div class="form-group" style="flex:1;">
                <label>Service subtitle</label>
                <input type="text" class="form-control js-service-subtitle" value="">
              </div>
            </div>
            <div style="display:flex;justify-content:flex-end;">
              <button type="button" class="btn btn-outline btn-sm js-remove-service">Remove</button>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Bottom CTA</h2></div>
    <div style="padding:20px;">
      <p class="form-help" style="margin-top:0;">This is the final block on the live amenities page.</p>
      <div class="form-group">
        <label for="cta_title">CTA title</label>
        <input type="text" id="cta_title" name="cta_title" value="<?= sanitize($sections['cta_title'] ?? 'Ready to Experience Our Facilities?') ?>">
      </div>
      <div class="form-row">
        <div class="form-group" style="flex:1;">
          <label for="cta_btn_label">Button label</label>
          <input type="text" id="cta_btn_label" name="cta_btn_label" value="<?= sanitize($sections['cta_btn_label'] ?? 'Book Your Stay') ?>">
        </div>
        <div class="form-group" style="flex:1;">
          <label for="cta_btn_href">Button URL</label>
          <input type="text" id="cta_btn_href" name="cta_btn_href" value="<?= sanitize($sections['cta_btn_href'] ?? '/contact') ?>">
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
var AMENITIES_SLOT_COUNT = 7;

function amenitiesSafeParseJson(text, fallback) {
  try { return JSON.parse(text || ''); } catch (e) { return fallback; }
}
function amenitiesEscHtml(s) {
  return String(s || '').replace(/[&<>"']/g, function (m) {
    return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m];
  });
}
function amenitiesNormalizeImgUrl(val) {
  var v = String(val || '').trim();
  if (!v) return '';
  if (v.indexOf('http') === 0) return v;
  return '<?= SITE_URL ?>' + v.replace(/^\/+/, '');
}

function amenitiesParseGalleryHidden(input) {
  var raw = (input && input.value ? input.value : '').trim();
  if (!raw) return [];
  try {
    var v = JSON.parse(raw);
    if (Array.isArray(v)) return v.map(function (x) { return String(x || '').trim(); }).filter(Boolean);
    if (typeof v === 'string' && v) return [v];
  } catch (e) {
    return [raw];
  }
  return [];
}

function amenitiesRenderBgPreview(wrap) {
  var inp = wrap.querySelector('.js-bg');
  var prev = wrap.querySelector('[id$="_bg_preview"]');
  if (!inp || !prev) return;
  var u = amenitiesNormalizeImgUrl(inp.value);
  if (!u) {
    prev.style.display = 'none';
    prev.innerHTML = '';
    return;
  }
  prev.style.display = 'block';
  prev.innerHTML = '<img src="' + amenitiesEscHtml(u) + '" style="max-width:420px;max-height:240px;border-radius:6px;">';
}

function amenitiesRenderGalleryPreview(wrap) {
  var inp = wrap.querySelector('.js-gallery');
  var prev = wrap.querySelector('[id$="_gallery_preview"]');
  if (!inp || !prev) return;
  var items = amenitiesParseGalleryHidden(inp);
  prev.innerHTML = items.length
    ? items.map(function (p) {
        var u = amenitiesNormalizeImgUrl(p);
        return u ? ('<img src="' + amenitiesEscHtml(u) + '" style="max-width:120px;max-height:90px;display:inline-block;margin:5px;object-fit:cover;border-radius:6px;">') : '';
      }).join('')
    : '';
}

function amenitiesGetItemsFromDom() {
  var existing = amenitiesSafeParseJson(document.getElementById('sections_json')?.value || '[]', []);
  if (!Array.isArray(existing)) existing = [];
  var tail = existing.length > AMENITIES_SLOT_COUNT ? existing.slice(AMENITIES_SLOT_COUNT) : [];

  var out = [];
  document.querySelectorAll('#amenitiesPageForm .js-amenity-slot').forEach(function (wrap) {
    var bg = (wrap.querySelector('.js-bg')?.value || '').trim();
    var kicker = (wrap.querySelector('.js-kicker')?.value || '').trim();
    var title_html = (wrap.querySelector('.js-title-html')?.value || '').trim();
    var body = (wrap.querySelector('.js-body')?.value || '').trim();
    var gradient = (wrap.querySelector('.js-meta-gradient')?.value || '').trim();
    var icon = (wrap.querySelector('.js-meta-icon')?.value || '').trim();
    var layout = (wrap.querySelector('.js-meta-layout')?.value || '').trim() || 'bottom';
    var btn = (wrap.querySelector('.js-meta-btn')?.value || '').trim();
    var btn_href = (wrap.querySelector('.js-meta-btn-href')?.value || '').trim();
    var gallery = amenitiesParseGalleryHidden(wrap.querySelector('.js-gallery'));
    out.push({
      bg: bg,
      gradient: gradient,
      kicker: kicker,
      icon: icon,
      title_html: title_html,
      body: body,
      btn: btn,
      btn_href: btn_href,
      gallery: gallery,
      layout: layout
    });
  });

  while (out.length < AMENITIES_SLOT_COUNT) {
    out.push({ bg: '', gradient: '', kicker: '', icon: '', title_html: '', body: '', btn: '', btn_href: '', gallery: [], layout: 'bottom' });
  }
  return out.slice(0, AMENITIES_SLOT_COUNT).concat(tail);
}

function amenitiesGetServicesFromDom() {
  var items = [];
  document.querySelectorAll('#servicesItemsWrap .js-service-item').forEach(function (wrap) {
    var title = (wrap.querySelector('.js-service-title')?.value || '').trim();
    var subtitle = (wrap.querySelector('.js-service-subtitle')?.value || '').trim();
    if (!title) return;
    items.push({ title: title, subtitle: subtitle });
  });
  return items;
}

function amenitiesSyncServicesJson() {
  var hidden = document.getElementById('services_items_json');
  if (!hidden) return;
  hidden.value = JSON.stringify(amenitiesGetServicesFromDom());
}

function amenitiesGetBusinessChambersFromDom() {
  var items = [];
  document.querySelectorAll('#businessChambersWrap .js-business-chamber').forEach(function (wrap) {
    var title = (wrap.querySelector('.js-chamber-title')?.value || '').trim();
    var body = (wrap.querySelector('.js-chamber-body')?.value || '').trim();
    var badge = (wrap.querySelector('.js-chamber-badge')?.value || '').trim();
    if (!title) return;
    items.push({ title: title, body: body, badge: badge });
  });
  return items;
}

function amenitiesSyncBusinessChambersJson() {
  var hidden = document.getElementById('business_chambers_json');
  if (!hidden) return;
  hidden.value = JSON.stringify(amenitiesGetBusinessChambersFromDom());
}

function amenitiesSyncHiddenJson() {
  var items = amenitiesGetItemsFromDom();
  var hidden = document.getElementById('sections_json');
  if (hidden) hidden.value = JSON.stringify(items);
  amenitiesSyncServicesJson();
  amenitiesSyncBusinessChambersJson();
  var adv = document.getElementById('sections_json_advanced');
  if (adv) adv.value = JSON.stringify(items, null, 2);
}

document.getElementById('amenitiesPageForm').addEventListener('submit', function (e) {
  e.preventDefault();
  amenitiesSyncHiddenJson();
  savePageForm(this, 'amenities', { sections_json: 'json', services_items_json: 'json', business_chambers_json: 'json' }, { pageActiveSettingKey: 'page_active_amenities' })
    .then(function () { showToast('Saved', 'success'); })
    .catch(function (err) { showToast(err.message || 'Save failed', 'error'); });
});

// Render multi-image previews when selecting gallery images (dynamic ids)
(function () {
  var prevInsert = window.insertSelectedMediaOverride;
  window.insertSelectedMediaOverride = function () {
    var selected = mediaModalState.allowMultiple ? mediaModalState.selectedMediaMultiple : (mediaModalState.selectedMedia ? [mediaModalState.selectedMedia] : []);
    if (!selected.length) return false;
    var tid = mediaModalState.targetInputId || '';

    if (tid.indexOf('amenity_slot_') === 0 && tid.indexOf('_gallery_images') !== -1) {
      var paths = selected.map(function (s) { return s.path; });
      var input = document.getElementById(tid);
      if (input) input.value = JSON.stringify(paths);
      var prev = mediaModalState.targetPreviewId ? document.getElementById(mediaModalState.targetPreviewId) : null;
      if (prev) {
        prev.style.display = 'block';
        prev.innerHTML = paths.map(function (p) {
          return '<img src="<?= SITE_URL ?>' + String(p).replace(/^\/+/, '') + '" style="max-width:120px;max-height:90px;display:inline-block;margin:5px;object-fit:cover;border-radius:6px;">';
        }).join('');
      }
      closeMediaModal();
      if (typeof showToast === 'function') showToast(paths.length + ' images selected', 'success');
      if (typeof amenitiesSyncHiddenJson === 'function') amenitiesSyncHiddenJson();
      return true;
    }

    if (tid.indexOf('amenity_section_') === 0 && tid.indexOf('_gallery_images') !== -1) {
      var paths2 = selected.map(function (s) { return s.path; });
      var input2 = document.getElementById(tid);
      if (input2) input2.value = JSON.stringify(paths2);
      var prev2 = mediaModalState.targetPreviewId ? document.getElementById(mediaModalState.targetPreviewId) : null;
      if (prev2) {
        prev2.style.display = 'block';
        prev2.innerHTML = paths2.map(function (p) {
          return '<img src="<?= SITE_URL ?>' + String(p).replace(/^\/+/, '') + '" style="max-width:120px;max-height:90px;display:inline-block;margin:5px;object-fit:cover;border-radius:6px;">';
        }).join('');
      }
      closeMediaModal();
      if (typeof showToast === 'function') showToast(paths2.length + ' images selected', 'success');
      if (typeof amenitiesSyncHiddenJson === 'function') amenitiesSyncHiddenJson();
      return true;
    }

    if (typeof prevInsert === 'function') {
      return prevInsert();
    }
    return false;
  };
})();

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('#amenitiesPageForm .js-amenity-slot').forEach(function (wrap) {
    var bgBtn = wrap.querySelector('.js-pick-bg');
    var galBtn = wrap.querySelector('.js-pick-gallery');
    var galClr = wrap.querySelector('.js-clear-gallery');
    var bgInput = wrap.querySelector('.js-bg');
    var galInput = wrap.querySelector('.js-gallery');

    var bgId = bgInput ? bgInput.id : '';
    var bgPrevId = wrap.querySelector('[id$="_bg_preview"]') ? wrap.querySelector('[id$="_bg_preview"]').id : '';
    var galId = galInput ? galInput.id : '';
    var galPrevId = wrap.querySelector('[id$="_gallery_preview"]') ? wrap.querySelector('[id$="_gallery_preview"]').id : '';

    if (bgBtn && bgId && bgPrevId) {
      bgBtn.addEventListener('click', function () {
        openMediaModal(bgId, bgPrevId, false);
      });
    }
    if (galBtn && galId && galPrevId) {
      galBtn.addEventListener('click', function () {
        openMediaModal(galId, galPrevId, true);
      });
    }
    if (galClr && galInput) {
      galClr.addEventListener('click', function () {
        galInput.value = '[]';
        amenitiesRenderGalleryPreview(wrap);
        amenitiesSyncHiddenJson();
      });
    }
    if (bgInput) {
      bgInput.addEventListener('input', function () {
        amenitiesRenderBgPreview(wrap);
        amenitiesSyncHiddenJson();
      });
    }
    if (galInput) {
      galInput.addEventListener('input', function () {
        amenitiesRenderGalleryPreview(wrap);
        amenitiesSyncHiddenJson();
      });
    }

    amenitiesRenderBgPreview(wrap);
    amenitiesRenderGalleryPreview(wrap);

    wrap.addEventListener('input', function () { amenitiesSyncHiddenJson(); });
    wrap.addEventListener('change', function () { amenitiesSyncHiddenJson(); });
  });

  amenitiesSyncHiddenJson();

  var servicesWrap = document.getElementById('servicesItemsWrap');
  var addServiceBtn = document.getElementById('addServiceItemBtn');
  var serviceTemplate = document.getElementById('serviceItemTemplate');
  var chambersWrap = document.getElementById('businessChambersWrap');
  var addChamberBtn = document.getElementById('addBusinessChamberBtn');
  var chamberTemplate = document.getElementById('businessChamberTemplate');

  function wireServiceRow(row) {
    if (!row) return;
    var removeBtn = row.querySelector('.js-remove-service');
    if (removeBtn) {
      removeBtn.addEventListener('click', function () {
        row.remove();
        amenitiesSyncServicesJson();
      });
    }
    row.addEventListener('input', amenitiesSyncServicesJson);
    row.addEventListener('change', amenitiesSyncServicesJson);
  }

  if (servicesWrap) {
    servicesWrap.querySelectorAll('.js-service-item').forEach(wireServiceRow);
  }
  if (addServiceBtn && servicesWrap && serviceTemplate) {
    addServiceBtn.addEventListener('click', function () {
      var fragment = serviceTemplate.content.cloneNode(true);
      var row = fragment.querySelector('.js-service-item');
      servicesWrap.appendChild(fragment);
      wireServiceRow(servicesWrap.lastElementChild);
      amenitiesSyncServicesJson();
    });
  }
  amenitiesSyncServicesJson();

  function wireBusinessChamberRow(row) {
    if (!row) return;
    var removeBtn = row.querySelector('.js-remove-chamber');
    if (removeBtn) {
      removeBtn.addEventListener('click', function () {
        row.remove();
        amenitiesSyncBusinessChambersJson();
      });
    }
    row.addEventListener('input', amenitiesSyncBusinessChambersJson);
    row.addEventListener('change', amenitiesSyncBusinessChambersJson);
  }

  if (chambersWrap) {
    chambersWrap.querySelectorAll('.js-business-chamber').forEach(wireBusinessChamberRow);
  }
  if (addChamberBtn && chambersWrap && chamberTemplate) {
    addChamberBtn.addEventListener('click', function () {
      var fragment = chamberTemplate.content.cloneNode(true);
      chambersWrap.appendChild(fragment);
      wireBusinessChamberRow(chambersWrap.lastElementChild);
      amenitiesSyncBusinessChambersJson();
    });
  }
  amenitiesSyncBusinessChambersJson();

  var applyBtn = document.getElementById('amenitiesApplyJsonBtn');
  if (applyBtn) applyBtn.addEventListener('click', function () {
    var t = document.getElementById('sections_json_advanced')?.value || '';
    var v = amenitiesSafeParseJson(t, null);
    if (!Array.isArray(v)) {
      showToast('Sections JSON must be an array', 'error');
      return;
    }
    var hidden = document.getElementById('sections_json');
    if (hidden) hidden.value = JSON.stringify(v);
    window.location.reload();
  });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
