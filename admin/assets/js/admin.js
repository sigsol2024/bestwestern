/**
 * Admin Panel JavaScript
 */

function adminApiBase() {
  const base = typeof ADMIN_URL !== 'undefined' ? ADMIN_URL : (window.ADMIN_URL || '');
  return String(base).replace(/\/?$/, '/');
}

function parseAdminJsonResponse(response) {
  return response.text().then(function (text) {
    let data;
    try {
      data = JSON.parse(text);
    } catch (e) {
      throw new Error('Server returned invalid JSON (HTTP ' + response.status + '). Try logging in again.');
    }
    if (!data.success) {
      throw new Error(data.message || ('Request failed (HTTP ' + response.status + ')'));
    }
    return data;
  });
}

/**
 * Infer page_sections content_type from field name (with optional per-form overrides).
 */
function inferPageSectionContentType(key) {
  if (key === 'hero_bg_slides') return 'json';
  if (key.endsWith('_html')) return 'html';
  if (key.endsWith('_json')) return 'json';
  if (key.endsWith('_bg') || key.endsWith('_image') || key.indexOf('_img') !== -1) return 'image';
  return 'text';
}

/**
 * POST one section to pages API; rejects with Error(message) on failure.
 */
function savePageSection(payload) {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  if (!csrf) {
    return Promise.reject(new Error('Security token missing. Refresh the page and try again.'));
  }
  const url = adminApiBase() + 'api/pages.php';
  // multipart/form-data: CSRF in $_POST (works when proxies strip custom headers or block JSON bodies with HTML)
  const fd = new FormData();
  fd.append('csrf_token', csrf);
  fd.append('page', String(payload.page ?? ''));
  fd.append('section_key', String(payload.section_key ?? ''));
  fd.append('content_type', String(payload.content_type ?? 'text'));
  fd.append('content', String(payload.content ?? ''));
  return fetch(url, {
    method: 'POST',
    body: fd,
    credentials: 'same-origin'
  }).then(parseAdminJsonResponse);
}

function saveSettingsPayload(payload) {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  if (!csrf) {
    return Promise.reject(new Error('Security token missing. Refresh the page and try again.'));
  }
  const url = adminApiBase() + 'api/settings.php';
  const body = Object.assign({}, payload, { csrf_token: csrf });
  return fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
    credentials: 'same-origin',
    body: JSON.stringify(body)
  }).then(parseAdminJsonResponse);
}

/**
 * @param {HTMLButtonElement|HTMLInputElement|null} btn
 * @param {boolean} on
 */
function setSaveButtonSavingState(btn, on) {
  if (!btn) return;
  const isInput = btn.tagName === 'INPUT';
  if (on) {
    if (!btn.dataset.siteSaveHtml) {
      btn.dataset.siteSaveHtml = isInput ? String(btn.value) : btn.innerHTML;
    }
    btn.disabled = true;
    btn.setAttribute('aria-busy', 'true');
    btn.classList.add('is-saving');
    if (isInput) {
      btn.value = 'Saving…';
    } else {
      btn.innerHTML =
        '<span class="admin-btn-spinner" aria-hidden="true"></span><span>Saving…</span>';
    }
  } else {
    btn.disabled = false;
    btn.removeAttribute('aria-busy');
    if (btn.dataset.siteSaveHtml != null) {
      if (isInput) {
        btn.value = btn.dataset.siteSaveHtml;
      } else {
        btn.innerHTML = btn.dataset.siteSaveHtml;
      }
      delete btn.dataset.siteSaveHtml;
    }
    btn.classList.remove('is-saving');
  }
}

/**
 * Save all named fields in a form as page_sections rows. Keys come from FormData (no hardcoded list).
 * @param {HTMLFormElement} formEl
 * @param {string} pageSlug
 * @param {Object<string,string>} [typeOverrides] section_key -> content_type
 * @param {{ submitButton?: HTMLButtonElement|HTMLInputElement }} [options]
 */
function savePageForm(formEl, pageSlug, typeOverrides, options) {
  typeOverrides = typeOverrides || {};
  options = options || {};
  const submitBtn =
    options.submitButton ||
    formEl.querySelector('button[type="submit"], input[type="submit"]');
  setSaveButtonSavingState(submitBtn, true);

  const formData = new FormData(formEl);
  const keys = [];
  formData.forEach(function (_, k) {
    if (String(k).indexOf('__') === 0) return;
    if (keys.indexOf(k) === -1) keys.push(k);
  });

  let promise = Promise.all(
    keys.map(function (key) {
      const ct =
        Object.prototype.hasOwnProperty.call(typeOverrides, key) && typeOverrides[key]
          ? typeOverrides[key]
          : inferPageSectionContentType(key);
      let val = formData.get(key);
      if (val === null || val === undefined) val = '';
      return savePageSection({
        page: pageSlug,
        section_key: key,
        content_type: ct,
        content: String(val)
      });
    })
  );

  if (options.pageActiveSettingKey) {
    const pageActiveFieldName = options.pageActiveFieldName || '__page_active';
    const pageActiveValue = formData.get(pageActiveFieldName) ? '1' : '0';
    promise = promise.then(function () {
      return saveSettingsPayload({
        [options.pageActiveSettingKey]: pageActiveValue
      });
    });
  }

  return promise.finally(function () {
    setSaveButtonSavingState(submitBtn, false);
  });
}

function showToast(message, type = 'info') {
  const container = document.getElementById('toastContainer');
  if (!container) return;

  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.style.cssText = `
    background: ${type === 'success' ? '#46b450' : type === 'error' ? '#dc3232' : type === 'warning' ? '#ffb900' : '#0073aa'};
    color: white;
    padding: 12px 20px;
    border-radius: 4px;
    margin-bottom: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: 300px;
    max-width: 500px;
  `;

  const messageText = document.createElement('span');
  messageText.textContent = message;
  toast.appendChild(messageText);

  const closeBtn = document.createElement('button');
  closeBtn.innerHTML = '&times;';
  closeBtn.style.cssText = 'background:none;border:none;color:white;font-size:20px;cursor:pointer;margin-left:15px;';
  closeBtn.onclick = () => toast.remove();
  toast.appendChild(closeBtn);

  container.appendChild(toast);
  setTimeout(() => toast.remove(), 5000);
}

document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const mobileMenuToggle = document.getElementById('mobileMenuToggle');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('open');
      } else {
        sidebar.classList.toggle('collapsed');
      }
    });
  }

  if (mobileMenuToggle && sidebar) {
    mobileMenuToggle.addEventListener('click', function () {
      sidebar.classList.toggle('open');
    });
  }

  document.addEventListener('click', function (e) {
    if (window.innerWidth <= 768 && sidebar && sidebar.classList.contains('open')) {
      if (!sidebar.contains(e.target) && !mobileMenuToggle?.contains(e.target)) {
        sidebar.classList.remove('open');
      }
    }
  });
});

