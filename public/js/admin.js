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
});
