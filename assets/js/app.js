document.addEventListener('DOMContentLoaded', () => {
  // Broken images: soften instead of a broken icon. Capture phase, so it
  // also covers images swapped in later by the AJAX shop grid.
  document.addEventListener('error', (e) => {
    if (e.target && e.target.tagName === 'IMG') e.target.style.opacity = '0.35';
  }, true);

  // Sticky header shadow.
  const header = document.getElementById('siteHeader');
  const onScroll = () => header && header.classList.toggle('scrolled', window.scrollY > 8);
  window.addEventListener('scroll', onScroll, { passive: true }); onScroll();

  // Toast helper (footer has #toast).
  const toast = document.getElementById('toast');
  let toastTimer;
  const showToast = (msg) => {
    if (!toast) return;
    toast.innerHTML = msg; toast.classList.add('show');
    clearTimeout(toastTimer); toastTimer = setTimeout(() => toast.classList.remove('show'), 2600);
  };

  // Add to cart without a page reload. Delegated on document (not bound
  // per form at load) so it survives grid swaps and no earlier error can
  // kill the binding. Only a true network failure falls back to a normal
  // submit; a bad response just shows the message instead of reloading.
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (!form || form.tagName !== 'FORM') return;
    const btn = form.querySelector('[data-quick-add]');
    if (!btn || (form.method || 'get').toUpperCase() !== 'POST') return;
    e.preventDefault();
    if (btn.disabled) return;
    btn.disabled = true;
    fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then((res) => res.json().catch(() => null).then((data) => ({ res, data })))
      .then(({ res, data }) => {
        btn.disabled = false;
        if (!data || !data.ok) { showToast((data && data.message) || 'Could not add that item.'); return; }
        const count = document.querySelector('.cart-count');
        if (count) count.textContent = data.count;
        const fabCount = document.querySelector('.cart-fab-menu a span');
        if (fabCount) fabCount.textContent = data.count + (data.count === 1 ? ' item' : ' items');
        showToast('<b>Added to your cart.</b>');
        btn.classList.add('added');
        setTimeout(() => btn.classList.remove('added'), 1800);
      })
      .catch(() => { form.submit(); });
  });

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

  // Floating cart button: tap toggles the menu (mobile); hover opens it
  // on desktop via CSS. Closes on outside tap, Escape, or picking a link.
  const cartFab = document.querySelector('[data-cart-fab]');
  const cartFabBtn = document.querySelector('[data-cart-fab-btn]');
  if (cartFab && cartFabBtn) {
    cartFabBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const open = cartFab.classList.toggle('open');
      cartFabBtn.setAttribute('aria-expanded', String(open));
    });
    document.addEventListener('click', (e) => {
      if (!cartFab.contains(e.target)) { cartFab.classList.remove('open'); cartFabBtn.setAttribute('aria-expanded', 'false'); }
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') { cartFab.classList.remove('open'); cartFabBtn.setAttribute('aria-expanded', 'false'); }
    });
    cartFab.querySelectorAll('.cart-fab-menu a').forEach((a) => a.addEventListener('click', () => {
      cartFab.classList.remove('open'); cartFabBtn.setAttribute('aria-expanded', 'false');
    }));
  }

  // Quick view modal: filled from the card's data attributes, always
  // reachable (unlike the preview's hover-only bar). Add to cart inside
  // uses the same no-reload handler as the grid buttons.
  const qvOverlay = document.querySelector('[data-qv-overlay]');
  if (qvOverlay) {
    const q = (sel) => qvOverlay.querySelector(sel);
    const qvForm = q('[data-qv-form]');
    // Prices in data attributes are USD; shown in ₱ at ₱63 per $1.
    const money = (n) => '₱' + (Number(n) * 63).toFixed(2);
    const openQv = (btn) => {
      const d = btn.dataset;
      const price = parseFloat(d.price || '0');
      const compare = parseFloat(d.compare || '0');
      const stock = parseInt(d.stock || '0', 10);
      q('[data-qv-img]').src = d.image; q('[data-qv-img]').alt = d.name;
      q('[data-qv-cat]').textContent = (d.category || '').toUpperCase();
      q('[data-qv-name]').textContent = d.name;
      q('[data-qv-reviews]').textContent = d.reviews || '';
      q('[data-qv-price]').textContent = money(price);
      q('[data-qv-install]').textContent = money(price / 4);
      const was = q('[data-qv-was]'), off = q('[data-qv-off]');
      if (compare > price) { was.textContent = money(compare); was.hidden = false; off.textContent = '−' + Math.round((1 - price / compare) * 100) + '%'; off.hidden = false; }
      else { was.hidden = true; off.hidden = true; }
      q('[data-qv-desc]').textContent = d.desc || '';
      q('[data-qv-stock]').textContent = (stock < 5 ? 'Only a few left — order soon' : 'In stock and ready to move') + ' · ' + stock + ' available';
      q('[data-qv-pid]').value = d.id;
      const qty = q('[data-qv-qty]'); qty.max = Math.max(1, stock); qty.value = '1';
      q('[data-qv-link]').href = d.url;
      qvOverlay.querySelectorAll('.size-btn').forEach((b) => b.classList.toggle('selected', b.textContent.trim() === 'M'));
      qvOverlay.hidden = false; document.body.style.overflow = 'hidden';
    };
    const closeQv = () => { qvOverlay.hidden = true; document.body.style.overflow = ''; };
    const closeBtn = q('[data-qv-close]');
    if (closeBtn) closeBtn.addEventListener('click', closeQv);
    qvOverlay.addEventListener('click', (e) => { if (e.target === qvOverlay) closeQv(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !qvOverlay.hidden) closeQv(); });
    qvForm?.addEventListener('submit', () => setTimeout(closeQv, 1200));
    document.addEventListener('click', (e) => {
      const btn = e.target.closest && e.target.closest('[data-quick-view]');
      if (btn) openQv(btn);
    });
  }

  // Wishlist: local-only heart toggle, persisted in localStorage.
  // The stored value is parsed safely — a corrupt entry used to kill the
  // whole script (and with it, the no-reload add to cart).
  let wished;
  try { wished = new Set(JSON.parse(localStorage.getItem('rally-wishlist') || '[]')); }
  catch (err) { wished = new Set(); }
  const paintWish = (btn) => {
    const id = btn.getAttribute('data-wishlist');
    const saved = wished.has(id);
    btn.classList.toggle('active', saved);
    if (btn.classList.contains('wish-btn')) { btn.textContent = saved ? '♥' : '♡'; }
    else { btn.textContent = btn.textContent.replace(/^[♥♡]/, saved ? '♥' : '♡'); }
  };
  document.querySelectorAll('[data-wishlist]').forEach(paintWish);
  document.addEventListener('click', (e) => {
    const btn = e.target.closest && e.target.closest('[data-wishlist]');
    if (!btn) return;
    const id = btn.getAttribute('data-wishlist');
    if (wished.has(id)) { wished.delete(id); showToast('Removed from wishlist'); }
    else { wished.add(id); showToast('<b>♥ Saved</b> to your wishlist'); }
    try { localStorage.setItem('rally-wishlist', JSON.stringify([...wished])); } catch (err) { /* storage unavailable — wishlist just won't persist */ }
    paintWish(btn);
  });

  // Show/hide password (delegated).
  document.addEventListener('click', (e) => {
    const btn = e.target.closest && e.target.closest('.password-toggle');
    if (!btn) return;
    const input = btn.parentElement.querySelector('input');
    if (!input) return;
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    btn.classList.toggle('is-visible', show);
    btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
  });

  // Size selector (visual only — size is not stored server-side yet)
  // and the size guide (delegated, so they work in the modal too).
  document.addEventListener('click', (e) => {
    const btn = e.target.closest && e.target.closest('.size-btn');
    if (btn) {
      btn.parentElement.querySelectorAll('.size-btn').forEach((b) => b.classList.remove('selected'));
      btn.classList.add('selected');
      return;
    }
    if (e.target.closest && e.target.closest('[data-size-guide]')) {
      showToast('Sizes run true to size — M fits most. Full guide coming soon.');
    }
  });

  // Quantity steppers (delegated).
  document.addEventListener('click', (e) => {
    const btn = e.target.closest && e.target.closest('[data-step]');
    if (!btn) return;
    const stepper = btn.closest('[data-stepper]');
    const input = stepper && stepper.querySelector('input[type=number]');
    if (!input) return;
    const min = parseInt(input.min || '1', 10), max = parseInt(input.max || '99', 10);
    input.value = Math.min(max, Math.max(min, (parseInt(input.value || '1', 10) + parseInt(btn.dataset.step, 10))));
  });

  // Newsletter (front-end only for now).
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (!form || !form.matches || !form.matches('[data-newsletter-form]')) return;
    e.preventDefault();
    showToast('<b>Welcome to Rally!</b> You’re on the list.');
    form.reset();
  });

  // Shop without a reload: category tabs, category cards, footer shop
  // links and the sort select fetch the new grid and swap it in place.
  // Only runs on the home page (where .shop-tools exists).
  const shopTools = document.querySelector('.shop-tools');
  const grid = document.querySelector('.product-grid');
  if (shopTools && grid) {
    let shopLoading = false;
    const loadShop = (url, { push = true, scroll = false } = {}) => {
      if (shopLoading) return;
      shopLoading = true;
      const clean = new URL(url, location.href); clean.hash = '';
      fetch(clean.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then((res) => res.text())
        .then((html) => {
          const doc = new DOMParser().parseFromString(html, 'text/html');
          const newGrid = doc.querySelector('.product-grid');
          if (!newGrid) { location.href = url; return; }
          grid.innerHTML = newGrid.innerHTML;
          const newTools = doc.querySelector('.shop-tools');
          if (newTools) shopTools.innerHTML = newTools.innerHTML;
          const newNote = doc.querySelector('[data-search-note]');
          const note = document.querySelector('[data-search-note]');
          if (newNote && note) note.outerHTML = newNote.outerHTML;
          else if (!newNote && note) note.remove();
          const heroCount = doc.querySelector('.hero-stats strong');
          const heroCountEl = document.querySelector('.hero-stats strong');
          if (heroCount && heroCountEl) heroCountEl.textContent = heroCount.textContent;
          const newFeatured = doc.querySelector('#featured-strip');
          const featured = document.querySelector('#featured-strip');
          if (newFeatured && featured) featured.outerHTML = newFeatured.outerHTML;
          else if (!newFeatured && featured) featured.remove();
          else if (newFeatured && !featured) document.getElementById('shop').insertAdjacentElement('afterend', newFeatured);
          grid.querySelectorAll('[data-wishlist]').forEach(paintWish);
          if (push) history.pushState(null, '', url);
          if (scroll) document.getElementById('shop').scrollIntoView();
        })
        .catch(() => { location.href = url; })
        .finally(() => { shopLoading = false; });
    };
    document.addEventListener('click', (e) => {
      if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      const a = e.target.closest && e.target.closest('a');
      if (!a) return;
      const href = a.getAttribute('href');
      if (!href) return;
      const u = new URL(href, location.href);
      if (u.origin !== location.origin || u.pathname !== location.pathname) return;
      if (!u.searchParams.has('category') && !u.searchParams.has('sort') && !u.searchParams.has('q')) return;
      if (!a.closest('.category-tabs') && !a.closest('.cat-grid') && !a.closest('.footer-top') && !a.closest('.hero-cta') && !a.closest('#featured-strip')) return;
      e.preventDefault();
      loadShop(u.toString(), { scroll: true });
    });
    document.addEventListener('change', (e) => {
      const sel = e.target.closest && e.target.closest('.shop-meta select');
      if (!sel) return;
      const form = sel.closest('form');
      if (!form) return;
      const u = new URL(form.action || location.href, location.href);
      new FormData(form).forEach((v, k) => u.searchParams.set(k, v));
      loadShop(u.toString());
    });
    shopTools.addEventListener('submit', (e) => {
      e.preventDefault();
      const u = new URL(shopTools.action || location.href, location.href);
      new FormData(shopTools).forEach((v, k) => u.searchParams.set(k, v));
      loadShop(u.toString());
    });
    window.addEventListener('popstate', () => loadShop(location.href, { push: false }));
  }
});
