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
  };

  const closeSidebar = () => {
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

});
