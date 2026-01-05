// Simple admin JS: toggle password visibility and mobile sidebar
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.toggle-password').forEach(function(btn){
    btn.addEventListener('click', function(e){
      const input = document.querySelector(this.dataset.target);
      if (!input) return;
      if (input.type === 'password') { input.type = 'text'; this.textContent = '🙈'; }
      else { input.type = 'password'; this.textContent = '👁️'; }
    });
  });

  const burger = document.getElementById('admin-burger');
  if (burger) burger.addEventListener('click', ()=>{document.getElementById('admin-sidebar').classList.toggle('hidden')});
});
