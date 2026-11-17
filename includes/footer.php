</main>
<footer class="site-footer">
  <div class="footer-top">
    <div>
      <a class="brand" href="<?= e(app_url('')) ?>"><img class="brand-lockup" src="<?= e(app_url('assets/img/logo-light.svg')) ?>" alt="RALLY" width="151" height="34"></a>
      <p style="margin-top:14px">Made for movement. Designed for wherever you’re headed — the miles, the reps, and everything after.</p>
    </div>
    <div>
      <h4>Shop</h4>
      <ul>
        <li><a href="<?= e(app_url('#shop')) ?>">All gear</a></li>
        <li><a href="<?= e(app_url('?category=Running#shop')) ?>">Running</a></li>
        <li><a href="<?= e(app_url('?category=Training#shop')) ?>">Training</a></li>
        <li><a href="<?= e(app_url('?category=Outerwear#shop')) ?>">Outerwear</a></li>
      </ul>
    </div>
    <div>
      <h4>Help</h4>
      <ul>
        <li><a href="<?= e(app_url('orders.php')) ?>">Track my order</a></li>
        <li><a href="<?= e(app_url('cart.php')) ?>">My cart</a></li>
        <li><a href="<?= e(app_url('login.php')) ?>">Sign in</a></li>
        <li><a href="<?= e(app_url('register.php')) ?>">Create account</a></li>
      </ul>
    </div>
    <div class="newsletter">
      <h4>Get the drop first</h4>
      <p>New gear, restocks and members-only offers. No spam, just the good stuff.</p>
      <form action="<?= e(app_url('#shop')) ?>" method="get" data-newsletter-form>
        <input type="email" name="newsletter" placeholder="Email address" aria-label="Email address" required>
        <button type="submit">Join</button>
      </form>
    </div>
  </div>
  <div class="footer-bottom"><div class="footer-bottom-inner"><span>© <?= date('Y') ?> Rally Sportswear — Move your own way.</span><span>Free shipping over ₱6,300 · 30-day returns · Pay on delivery</span></div></div>
</footer>
<div class="cart-fab" data-cart-fab>
  <div class="cart-fab-menu" role="menu" aria-label="Cart menu">
    <a href="<?= e(app_url('cart.php')) ?>" role="menuitem">🛒 View cart <span><?= cart_count() ?> item<?= cart_count() === 1 ? '' : 's' ?></span></a>
    <a href="<?= e(app_url('orders.php')) ?>" role="menuitem">📋 Order history</a>
  </div>
  <button class="cart-fab-btn" type="button" aria-label="Cart menu" aria-expanded="false" aria-haspopup="true" data-cart-fab-btn>🛒<span class="cart-count"><?= cart_count() ?></span></button>
</div>
<div class="qv-overlay" data-qv-overlay hidden>
  <div class="qv-modal" role="dialog" aria-modal="true" aria-labelledby="qvName">
    <button class="qv-close" type="button" data-qv-close aria-label="Close quick view">✕</button>
    <div class="qv-media"><img data-qv-img src="" alt=""></div>
    <div class="qv-copy">
      <span class="eyebrow" data-qv-cat></span>
      <h2 id="qvName" data-qv-name></h2>
      <div class="detail-rating"><b>★ 4.9</b> · <span data-qv-reviews></span> reviews</div>
      <div class="detail-price"><span data-qv-price></span><span class="was-price" data-qv-was hidden></span><span class="off-badge" data-qv-off hidden></span> <small>· or 4 payments of <span data-qv-install></span></small></div>
      <p data-qv-desc></p>
      <div class="stock-note"><span class="note-dot"></span><span data-qv-stock></span></div>
      <div class="size-label"><span>Select size</span></div>
      <div class="size-row" role="group" aria-label="Size">
        <button type="button" class="size-btn">XS</button><button type="button" class="size-btn">S</button><button type="button" class="size-btn selected">M</button><button type="button" class="size-btn">L</button><button type="button" class="size-btn">XL</button>
      </div>
      <form action="<?= e(app_url('cart-action.php')) ?>" method="post" class="detail-form" data-qv-form>
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="product_id" value="" data-qv-pid>
        <input type="hidden" name="action" value="add">
        <div class="qty-stepper" data-stepper><button type="button" data-step="-1" aria-label="Decrease quantity">−</button><input type="number" name="quantity" value="1" min="1" max="99" aria-label="Quantity" data-qv-qty><button type="button" data-step="1" aria-label="Increase quantity">+</button></div>
        <button class="button button-dark" type="submit" data-quick-add>Add to cart <span>↗</span></button>
      </form>
      <a class="continue-link" href="#" data-qv-link>View full details →</a>
    </div>
  </div>
</div>
<div class="toast" id="toast" role="status" aria-live="polite"></div>
<script src="<?= e(app_url('assets/js/app.js')) ?>" defer></script>
</body></html>
