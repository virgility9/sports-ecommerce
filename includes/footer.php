</main>
<footer class="site-footer">
  <div class="footer-top">
    <div>
      <a class="brand" href="<?= e(app_url('')) ?>"><img class="brand-mark" src="<?= e(app_url('assets/img/logo-mark.svg')) ?>" alt="" width="34" height="34">RALLY<span>®</span></a>
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
        <li><a href="<?= e(app_url('cart.php')) ?>">My bag</a></li>
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
  <div class="footer-bottom"><div class="footer-bottom-inner"><span>© <?= date('Y') ?> Rally Sportswear — Move your own way.</span><span>Free shipping over $100 · 30-day returns · Pay on delivery</span></div></div>
</footer>
<div class="toast" id="toast" role="status" aria-live="polite"></div>
<script src="<?= e(app_url('assets/js/app.js')) ?>" defer></script>
</body></html>
