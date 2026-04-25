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
    <div class="card-header"><h2>Page title</h2></div>
    <div style="padding:20px;">
      <div class="form-group">
        <label for="page_title">Browser tab title</label>
        <input type="text" id="page_title" name="page_title" value="<?= sanitize($sections['page_title'] ?? 'Hotel Amenities') ?>">
        <p class="form-help">This only updates the browser/page title (SEO/meta). The visible hero heading is edited in <strong>Main content sections → Hero Section → Title (HTML)</strong>.</p>
      </div>
    </div>
  </div>

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

          <div class="form-group">
            <label>Primary image</label>
            <div style="display:flex; gap: 10px; align-items:center; flex-wrap:wrap;">
              <button type="button" class="btn btn-outline js-pick-bg">Select from media</button>
              <input type="text" id="<?= sanitize($bgId) ?>" class="form-control js-bg" value="<?= $bg ?>" placeholder="/assets/uploads/... or https://...">
            </div>
            <div id="<?= sanitize($bgPrevId) ?>" class="image-preview" style="display:none;margin-top:10px;"></div>
          </div>

          <div class="form-group" style="margin-top:10px;">
            <label>Extra images (optional)</label>
            <div style="display:flex; gap: 10px; align-items:center; flex-wrap:wrap;">
              <button type="button" class="btn btn-outline js-pick-gallery">Select images</button>
              <button type="button" class="btn btn-outline btn-sm js-clear-gallery">Clear</button>
            </div>
            <input type="hidden" id="<?= sanitize($galId) ?>" class="js-gallery" value="<?= $galleryJson ?>">
            <div id="<?= sanitize($galPrevId) ?>" class="image-preview" style="display:block;margin-top:10px;"></div>
            <p class="form-help" style="margin-top:8px;">Not used by the new public layout unless you switch back to a gallery-driven design; kept for data compatibility.</p>
          </div>
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
    : '<span style="color: var(--text-muted); font-size: 12px;">No extra images selected.</span>';
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

function amenitiesSyncHiddenJson() {
  var items = amenitiesGetItemsFromDom();
  var hidden = document.getElementById('sections_json');
  if (hidden) hidden.value = JSON.stringify(items);
  amenitiesSyncServicesJson();
  var adv = document.getElementById('sections_json_advanced');
  if (adv) adv.value = JSON.stringify(items, null, 2);
}

document.getElementById('amenitiesPageForm').addEventListener('submit', function (e) {
  e.preventDefault();
  amenitiesSyncHiddenJson();
  savePageForm(this, 'amenities', { sections_json: 'json', services_items_json: 'json' }, { pageActiveSettingKey: 'page_active_amenities' })
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
