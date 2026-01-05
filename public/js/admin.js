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
});
