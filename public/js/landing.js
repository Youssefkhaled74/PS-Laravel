// Theme & mobile behaviors for landing page
(function(){
  const root = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  const hamburger = document.querySelector('.hamburger');
  const mobilePanel = document.querySelector('.mobile-panel');

  function applyTheme(t){
    if(t === 'dark') root.classList.add('theme-dark');
    else root.classList.remove('theme-dark');
    localStorage.setItem('ps_theme', t);
    if(themeToggle) themeToggle.textContent = t === 'dark' ? '☀️' : '🌙';
  }

  // load saved theme
  const saved = localStorage.getItem('ps_theme') || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  applyTheme(saved);

  themeToggle && themeToggle.addEventListener('click', ()=>{
    const isDark = document.documentElement.classList.contains('theme-dark');
    applyTheme(isDark ? 'light' : 'dark');
  });

  // hamburger
  if(hamburger && mobilePanel){
    hamburger.addEventListener('click', ()=>{
      const expanded = hamburger.getAttribute('aria-expanded') === 'true';
      hamburger.setAttribute('aria-expanded', String(!expanded));
      const showing = mobilePanel.getAttribute('aria-hidden') === 'false';
      mobilePanel.setAttribute('aria-hidden', String(!showing));
      mobilePanel.style.display = showing ? 'none' : 'block';
    });
  }

  // smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(a=>{
    a.addEventListener('click', (e)=>{
      const href = a.getAttribute('href');
      if(href.length>1){
        e.preventDefault();
        const el = document.querySelector(href);
        if(el) el.scrollIntoView({behavior:'smooth',block:'start'});
        // close mobile panel if open
        if(mobilePanel && mobilePanel.getAttribute('aria-hidden') === 'false'){
          mobilePanel.setAttribute('aria-hidden','true');
          mobilePanel.style.display = 'none';
          hamburger.setAttribute('aria-expanded','false');
        }
      }
    });
  });

  // set language switch localStorage hint
  const langSwitch = document.getElementById('langSwitch');
  if(langSwitch) langSwitch.addEventListener('click', ()=>{
    localStorage.setItem('ps_lang', langSwitch.textContent.trim());
  });
})();
