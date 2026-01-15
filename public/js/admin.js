// resources/js/admin.js
document.addEventListener('DOMContentLoaded', () => {
  // Password toggle (supports multiple inputs)
  document.querySelectorAll('[data-toggle="password"]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const selector = btn.getAttribute('data-target');
      const input = selector ? document.querySelector(selector) : null;
      if (!input) return;

      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';

      // Accessibility
      btn.setAttribute('aria-pressed', String(isHidden));
      btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');

      // Icon swap
      btn.textContent = isHidden ? '🙈' : '👁️';
    });
  });

  // Sidebar toggle (mobile)
  const burger = document.getElementById('admin-burger');
  const sidebar = document.getElementById('admin-sidebar');
  const overlay = document.getElementById('admin-overlay');
  const closeBtn = document.getElementById('admin-close');

  const openSidebar = () => {
    if (!sidebar) return;
    sidebar.classList.add('is-open');
    overlay?.classList.add('is-visible');
    document.body.classList.add('no-scroll');
  });

  /* === Address list helpers (copy + toast) === */
  (function(){
    const copyHandler = (e) => {
      const btn = e.target.closest && e.target.closest('.js-copy-map');
      if (!btn) return;
      const url = btn.getAttribute('data-map-url') || '';
      if (!url) return showToast('No location to copy');
      if (!navigator.clipboard) {
        // fallback
        const ta = document.createElement('textarea');
        ta.value = url; document.body.appendChild(ta); ta.select();
        try { document.execCommand('copy'); showToast('Copied'); } catch (err) { showToast('Copy failed'); }
        ta.remove();
        return;
      }
      navigator.clipboard.writeText(url).then(() => showToast('Copied'), () => showToast('Copy failed'));
    };

    const showToast = (text = 'Copied') => {
      let container = document.getElementById('admin-toast-container');
      if (!container) {
        container = document.createElement('div');
        container.id = 'admin-toast-container';
        container.style.position = 'fixed';
        container.style.zIndex = 99999;
        container.style.right = '1rem';
        container.style.bottom = '1rem';
        container.style.pointerEvents = 'none';
        document.body.appendChild(container);
      }

      const el = document.createElement('div');
      el.className = 'admin-toast-card';
      el.textContent = text;
      el.style.background = 'var(--panel)';
      el.style.color = 'var(--text)';
      el.style.border = '1px solid var(--card-soft-border)';
      el.style.padding = '.6rem .9rem';
      el.style.marginTop = '.5rem';
      el.style.borderRadius = '10px';
      el.style.boxShadow = 'var(--shadow-soft)';
      el.style.pointerEvents = 'auto';

      container.appendChild(el);
      setTimeout(() => { el.style.transition = 'opacity .25s ease, transform .25s ease'; el.style.opacity = '0'; el.style.transform = 'translateY(6px)'; }, 1600);
      setTimeout(() => el.remove(), 2000);
    };

    document.body.addEventListener('click', copyHandler);
  })();

  ```
    if (!sidebar) return;
    sidebar.classList.remove('is-open');
    overlay?.classList.remove('is-visible');
    document.body.classList.remove('no-scroll');
  };

  burger?.addEventListener('click', openSidebar);
  closeBtn?.addEventListener('click', closeSidebar);
  overlay?.addEventListener('click', closeSidebar);

  // ESC closes sidebar
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeSidebar();
  });

  // Global admin modal (uses markup included in admin layout)
  const globalModal = document.getElementById('admin-global-modal');
  if (globalModal) {
    const modalBackdrop = globalModal.querySelector('[data-modal-close]') || globalModal.querySelector('.admin-modal-backdrop');
    const modalCard = globalModal.querySelector('.admin-modal-card');
    const modalTitle = document.getElementById('admin-modal-title');
    const modalText = document.getElementById('admin-modal-text');
    const btnCancel = globalModal.querySelector('[data-modal-cancel]');
    const btnConfirm = globalModal.querySelector('[data-modal-confirm]');
    let onConfirm = null;

    const openAdminModal = (title = '', text = '', cb = null) => {
      if (modalTitle) modalTitle.textContent = title || '';
      if (modalText) modalText.textContent = text || '';
      globalModal.setAttribute('aria-hidden', 'false');
      onConfirm = typeof cb === 'function' ? cb : null;
      if (btnConfirm) btnConfirm.focus();
      document.addEventListener('keydown', escHandler);
    };

    const closeAdminModal = () => {
      globalModal.setAttribute('aria-hidden', 'true');
      onConfirm = null;
      document.removeEventListener('keydown', escHandler);
    };

    const escHandler = (e) => { if (e.key === 'Escape') closeAdminModal(); };

    btnCancel && btnCancel.addEventListener('click', closeAdminModal);
    modalBackdrop && modalBackdrop.addEventListener('click', closeAdminModal);
    btnConfirm && btnConfirm.addEventListener('click', () => { if (onConfirm) onConfirm(); closeAdminModal(); });

    // Delegate clicks for status toggles and generic confirms
    document.body.addEventListener('click', (e) => {
      const btn = e.target.closest && (e.target.closest('.js-status-toggle') || e.target.closest('.js-confirm'));
      if (!btn) return;
      e.preventDefault();

      // Status toggle button
      if (btn.classList.contains('js-status-toggle')) {
        const confirmEnabled = btn.getAttribute('data-confirm-enabled') !== '0';
        const title = btn.getAttribute('data-confirm-title') || '';
        const text = btn.getAttribute('data-confirm-text') || '';
        const targetSelector = btn.getAttribute('data-toggle-target');
        const formEl = targetSelector ? document.querySelector(targetSelector) : btn.closest('form');
        const submitFn = () => { if (formEl) formEl.submit(); };
        if (confirmEnabled) openAdminModal(title, text, submitFn); else submitFn();
        return;
      }

      // Generic .js-confirm
      if (btn.classList.contains('js-confirm')) {
        const msg = btn.getAttribute('data-confirm') || 'Are you sure?';
        const title = btn.getAttribute('data-confirm-title') || '';
        const formEl = btn.closest('form');
        const submitFn = () => { if (formEl) formEl.submit(); };
        openAdminModal(title, msg, submitFn);
      }
    });
  }

  /* Upload preview: delegated handlers
     - trigger file input via [data-file-trigger]
     - update previews on input[data-upload-preview] change
     - clear via [data-upload-clear]
  */
  document.body.addEventListener('click', (e) => {
    const trigger = e.target.closest && e.target.closest('[data-file-trigger]');
    if (trigger) {
      const selector = trigger.getAttribute('data-file-trigger');
      if (!selector) return;
      const input = document.querySelector(selector);
      if (input) input.click();
      return;
    }

    const clearBtn = e.target.closest && e.target.closest('[data-upload-clear]');
    if (clearBtn) {
      e.preventDefault();
      const target = clearBtn.getAttribute('data-target-input');
      const input = target ? document.querySelector(target) : null;
      if (input) {
        input.value = '';
        input.dispatchEvent(new Event('change', { bubbles: true }));
        // restore focus to trigger for accessibility
        clearBtn.focus();
      }
    }
  });

  document.body.addEventListener('change', (e) => {
    const input = e.target.closest && e.target.closest('input[data-upload-preview]');
    if (!input) return;

    const previewSelector = input.dataset.previewTarget;
    const filenameSelector = input.dataset.filenameTarget;
    const initialUrl = input.dataset.initialUrl || '';
    const previewEl = previewSelector ? document.querySelector(previewSelector) : null;
    const filenameEl = filenameSelector ? document.querySelector(filenameSelector) : null;
    const file = input.files && input.files[0];

    const setPlaceholder = () => {
      if (!previewEl) return;
      const placeholder = document.createElement('div');
      placeholder.id = previewEl.id;
      placeholder.className = 'upload-preview upload-placeholder';
      placeholder.innerHTML = '<div class="placeholder-icon">📄</div><div class="placeholder-text">'+(window?.ADMIN_I18N?.choose_file || 'Choose file')+'</div>';
      previewEl.replaceWith(placeholder);
    };

    if (!file) {
      // reset to initial url or placeholder
      if (initialUrl) {
        if (previewEl && previewEl.tagName === 'IMG') {
          previewEl.src = initialUrl;
        } else if (previewEl) {
          const img = document.createElement('img');
          img.id = previewEl.id;
          img.className = 'upload-preview';
          img.src = initialUrl;
          img.alt = input.name + ' preview';
          previewEl.replaceWith(img);
        }
      } else {
        setPlaceholder();
      }
      if (filenameEl) filenameEl.textContent = '';
      return;
    }

    const type = file.type || '';
    if (type.startsWith('image/')) {
      const url = URL.createObjectURL(file);
      if (previewEl) {
        if (previewEl.tagName === 'IMG') {
          previewEl.src = url;
        } else {
          const img = document.createElement('img');
          img.id = previewEl.id;
          img.className = 'upload-preview';
          img.src = url;
          img.alt = input.name + ' preview';
          previewEl.replaceWith(img);
        }
      }
      if (filenameEl) filenameEl.textContent = file.name;
    } else {
      // non-image: show doc placeholder with filename
      if (previewEl) {
        const div = document.createElement('div');
        div.id = previewEl.id;
        div.className = 'upload-preview upload-placeholder upload-doc';
        div.innerHTML = '<div class="doc-icon">📄</div><div class="doc-name">'+file.name+'</div>';
        previewEl.replaceWith(div);
      }
      if (filenameEl) filenameEl.textContent = file.name;
    }
  });

  // Live preview renderer for legal pages editor
  const renderMarkdownLite = (text) => {
    if (!text) return '';
    const lines = text.split(/\r?\n/);
    let out = '';
    let inList = false;
    lines.forEach((raw) => {
      const line = raw.trim();
      if (line === '') {
        if (inList) { out += '</ul>'; inList = false; }
        out += '<p></p>';
        return;
      }
      if (line.startsWith('- ')) {
        if (!inList) { inList = true; out += '<ul>'; }
        out += '<li>' + line.substring(2) + '</li>';
        return;
      }
      if (inList) { out += '</ul>'; inList = false; }
      // headings: lines starting with '## ' render as bold heading
      if (line.startsWith('## ')) {
        out += '<p style="font-weight:700;margin-bottom:.6rem">' + line.substring(3) + '</p>';
        return;
      }
      out += '<p>' + line + '</p>';
    });
    if (inList) out += '</ul>';
    return out;
  };

  document.body.addEventListener('input', (e) => {
    const ta = e.target.closest && e.target.closest('textarea[data-live-preview-target]');
    if (!ta) return;
    const lang = ta.getAttribute('data-live-preview-target');
    const preview = document.querySelector('[data-live-preview="' + lang + '"]');
    if (!preview) return;
    preview.innerHTML = renderMarkdownLite(ta.value || '');
  });

  // Tabs for switching preview language
  document.body.addEventListener('click', (e) => {
    const tab = e.target.closest && e.target.closest('[data-preview-tab]');
    if (!tab) return;
    const which = tab.getAttribute('data-preview-tab');
    const container = tab.closest('.preview-column');
    if (!container) return;
    container.querySelectorAll('.tab').forEach((b) => b.classList.remove('active'));
    tab.classList.add('active');
    container.querySelectorAll('[data-live-preview]').forEach((el) => {
      el.style.display = el.getAttribute('data-live-preview') === which ? '' : 'none';
    });
  });

  /* === Profile Card Copy Handler (appended) === */
  document.body.addEventListener('click', (e) => {
    const btn = e.target.closest && e.target.closest('.ps-copy-btn');
    if (!btn) return;
    const text = btn.getAttribute('data-copy') || '';
    if (!text) return;
    
    const copyText = () => {
      if (!navigator.clipboard) {
        // Fallback
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'absolute';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try {
          document.execCommand('copy');
          showPSToast('Copied!');
        } catch (err) {
          showPSToast('Copy failed');
        }
        ta.remove();
        return;
      }
      navigator.clipboard.writeText(text).then(() => showPSToast('Copied!'), () => showPSToast('Copy failed'));
    };

    copyText();
  });

  const showPSToast = (text = 'Copied') => {
    let container = document.getElementById('ps-toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'ps-toast-container';
      container.style.cssText = 'position:fixed;z-index:99999;right:1rem;bottom:1rem;pointer-events:none;';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = 'ps-toast';
    toast.textContent = text;
    toast.style.cssText = 'background:var(--panel);color:var(--text);border:1px solid var(--card-soft-border);padding:.6rem .9rem;margin-top:.5rem;border-radius:10px;box-shadow:var(--shadow-soft);pointer-events:auto;font-weight:600;';
    
    container.appendChild(toast);
    setTimeout(() => {
      toast.style.transition = 'opacity .25s ease, transform .25s ease';
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(6px)';
    }, 1600);
    setTimeout(() => toast.remove(), 2000);
  };

