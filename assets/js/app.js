document.addEventListener('DOMContentLoaded', () => {
  // Broken images: soften instead of showing a broken icon.
  document.querySelectorAll('img').forEach((image) => {
    image.addEventListener('error', () => { image.style.opacity = '0.35'; }, { once: true });
  });

  // Sticky header shadow.
  const header = document.getElementById('siteHeader');
  const onScroll = () => header && header.classList.toggle('scrolled', window.scrollY > 8);
  window.addEventListener('scroll', onScroll, { passive: true }); onScroll();

  // Mobile menu.
  const menuBtn = document.querySelector('[data-menu-btn]');
  const nav = document.querySelector('[data-main-nav]');
  if (menuBtn && nav) {
    menuBtn.addEventListener('click', () => {
      const open = nav.classList.toggle('open');
      menuBtn.setAttribute('aria-expanded', String(open));
      menuBtn.textContent = open ? '✕' : '☰';
    });
    nav.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => {
      nav.classList.remove('open'); menuBtn.textContent = '☰'; menuBtn.setAttribute('aria-expanded', 'false');
    }));
  }

  // Toast helper (footer has #toast).
  const toast = document.getElementById('toast');
  let toastTimer;
  const showToast = (msg) => {
    if (!toast) return;
    toast.innerHTML = msg; toast.classList.add('show');
    clearTimeout(toastTimer); toastTimer = setTimeout(() => toast.classList.remove('show'), 2600);
  };

  // Wishlist: local-only heart toggle, persisted in localStorage.
  const wished = new Set(JSON.parse(localStorage.getItem('rally-wishlist') || '[]'));
  document.querySelectorAll('[data-wishlist]').forEach((btn) => {
    const id = btn.getAttribute('data-wishlist');
    const paint = () => { btn.classList.toggle('active', wished.has(id)); btn.textContent = wished.has(id) ? '♥' : '♡'; };
    paint();
    btn.addEventListener('click', () => {
      if (wished.has(id)) { wished.delete(id); showToast('Removed from wishlist'); }
      else { wished.add(id); showToast('<b>♥ Saved</b> to your wishlist'); }
      localStorage.setItem('rally-wishlist', JSON.stringify([...wished])); paint();
    });
  });

  // Size selector (visual only — size is not stored server-side yet).
  document.querySelectorAll('.size-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      btn.parentElement.querySelectorAll('.size-btn').forEach((b) => b.classList.remove('selected'));
      btn.classList.add('selected');
    });
  });
  document.querySelector('[data-size-guide]')?.addEventListener('click', () => {
    showToast('Sizes run true to size — M fits most. Full guide coming soon.');
  });

  // Quantity steppers.
  document.querySelectorAll('[data-stepper]').forEach((stepper) => {
    const input = stepper.querySelector('input[type=number]');
    if (!input) return;
    stepper.querySelectorAll('[data-step]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const min = parseInt(input.min || '1', 10), max = parseInt(input.max || '99', 10);
        input.value = Math.min(max, Math.max(min, (parseInt(input.value || '1', 10) + parseInt(btn.dataset.step, 10))));
      });
    });
  });

  // Quick-add feedback (form still submits normally).
  document.querySelectorAll('[data-quick-add]').forEach((btn) => {
    btn.addEventListener('click', () => { btn.classList.add('added'); });
  });

  // Newsletter (front-end only for now).
  document.querySelector('[data-newsletter-form]')?.addEventListener('submit', (e) => {
    e.preventDefault();
    showToast('<b>Welcome to Rally!</b> You’re on the list.');
    e.target.reset();
  });
});
