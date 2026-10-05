
document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.querySelector('.sidebar');
  if (!sidebar) return;
  const button = document.createElement('button');
  button.type = 'button';
  button.className = 'sidebar-toggle';
  button.setAttribute('aria-label', 'Abrir menú');
  button.setAttribute('aria-expanded', 'false');
  button.textContent = '☰';
  const topbar = document.querySelector('.topbar');
  if (topbar) topbar.prepend(button);
  const backdrop = document.createElement('div');
  backdrop.className = 'sidebar-backdrop';
  backdrop.setAttribute('aria-hidden', 'true');
  document.body.appendChild(backdrop);
  const close = () => {
    document.body.classList.remove('sidebar-open');
    button.setAttribute('aria-expanded', 'false');
  };
  button.addEventListener('click', () => {
    const open = document.body.classList.toggle('sidebar-open');
    button.setAttribute('aria-expanded', String(open));
  });
  backdrop.addEventListener('click', close);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
  window.addEventListener('resize', () => { if (window.innerWidth > 760) close(); });
});
