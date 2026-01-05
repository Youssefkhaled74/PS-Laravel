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

  // Custom confirm modal: delegate to buttons with .js-confirm
  const createModal = () => {
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    overlay.innerHTML = `
      <div class="modal-box" role="dialog" aria-modal="true">
        <div class="modal-title"></div>
        <div class="modal-body"></div>
        <div class="modal-actions">
          <button class="btn btn-cancel">{{ cancelLabel }}</button>
          <button class="btn btn-confirm">{{ okLabel }}</button>
        </div>
      </div>
    `;
    // we'll replace placeholders later
    document.body.appendChild(overlay);
    return overlay;
  };

  // localize default labels (attempt to pull from data attributes on body)
  const okLabel = document.body.getAttribute('data-confirm-ok') || 'OK';
  const cancelLabel = document.body.getAttribute('data-confirm-cancel') || 'Cancel';

  // Build modal and cache references
  const modalOverlay = createModal();
  const modalBox = modalOverlay.querySelector('.modal-box');
  const modalTitle = modalOverlay.querySelector('.modal-title');
  const modalBody = modalOverlay.querySelector('.modal-body');
  const btnCancel = modalOverlay.querySelector('.btn-cancel');
  const btnOk = modalOverlay.querySelector('.btn-confirm');

  // Inject localized labels
  btnOk.textContent = okLabel;
  btnCancel.textContent = cancelLabel;

  const showConfirm = (message, title = '') => new Promise((resolve) => {
    modalTitle.textContent = title;
    modalBody.textContent = message;
    modalOverlay.classList.add('is-visible');

    const clean = () => {
      modalOverlay.classList.remove('is-visible');
      btnOk.removeEventListener('click', onOk);
      btnCancel.removeEventListener('click', onCancel);
    };

    const onOk = () => { clean(); resolve(true); };
    const onCancel = () => { clean(); resolve(false); };

    btnOk.addEventListener('click', onOk);
    btnCancel.addEventListener('click', onCancel);
  });

  document.body.addEventListener('click', (e) => {
    const btn = e.target.closest && e.target.closest('.js-confirm');
    if (!btn) return;
    e.preventDefault();
    const message = btn.getAttribute('data-confirm') || 'Are you sure?';
    const title = btn.getAttribute('data-confirm-title') || '';

    // find enclosing form to submit on confirm
    const frm = btn.closest('form');

    showConfirm(message, title).then((ok) => {
      if (!ok) return;
      if (frm) frm.submit();
      else {
        // if not inside a form but has data-action-url, perform fetch
        const url = btn.getAttribute('data-action-url');
        const method = btn.getAttribute('data-action-method') || 'POST';
        if (url) {
          fetch(url, { method: method, headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' } })
            .then(() => window.location.reload());
        }
      }
    });
  });
});
