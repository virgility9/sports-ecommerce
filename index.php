<?php
require_once __DIR__ . '/includes/bootstrap.php';
$category = trim($_GET['category'] ?? '');
$search = trim($_GET['q'] ?? '');
$sort = $_GET['sort'] ?? 'featured';
$sortSql = match ($sort) {
    'price-asc' => 'price ASC',
    'price-desc' => 'price DESC',
    'discount' => '(CASE WHEN compare_price IS NOT NULL AND compare_price > price THEN (compare_price - price) / compare_price ELSE 0 END) DESC, featured DESC, created_at DESC',
    'newest' => 'created_at DESC',
    'name' => 'name ASC',
    default => 'featured DESC, created_at DESC',
};
$sql = 'SELECT * FROM products WHERE stock > 0'; $params = [];
if ($category !== '') { $sql .= ' AND category = ?'; $params[] = $category; }
if ($search !== '') { $sql .= ' AND (name LIKE ? OR description LIKE ?)'; $params[] = '%' . $search . '%'; $params[] = '%' . $search . '%'; }
$sql .= ' ORDER BY ' . $sortSql;
$stmt = $pdo->prepare($sql); $stmt->execute($params); $products = $stmt->fetchAll();
$featured = array_values(array_filter($products, fn($product) => (int) $product['featured'] === 1));
$title = 'Rally — Made to Move'; require __DIR__ . '/includes/header.php';
?>
<section class="ticker" aria-hidden="true"><div>MOVE WITH PURPOSE <span>✳</span> BUILT FOR EVERY BODY <span>✳</span> FIND YOUR PACE <span>✳</span> MOVE WITH PURPOSE <span>✳</span> BUILT FOR EVERY BODY <span>✳</span> FIND YOUR PACE <span>✳</span>&nbsp;</div><div>MOVE WITH PURPOSE <span>✳</span> BUILT FOR EVERY BODY <span>✳</span> FIND YOUR PACE <span>✳</span> MOVE WITH PURPOSE <span>✳</span> BUILT FOR EVERY BODY <span>✳</span> FIND YOUR PACE <span>✳</span>&nbsp;</div></section>

<section class="hero"><div class="hero-inner">
  <div class="hero-image-overlay"></div>
  <div class="hero-copy">
    <span class="hero-tag">NEW DROP — SPRING / SUMMER 26</span>
    <h1>BUILT TO<br>MOVE FASTER.</h1>
    <p>Performance essentials for the miles, reps, and moments that make you. Lightweight, breathable, built to last.</p>
    <div class="hero-cta">
      <a href="#shop" class="button button-accent">Shop the collection <span>↘</span></a>
      <a href="<?= e(app_url('?category=Running#shop')) ?>" class="button button-light">Shop running</a>
    </div>
    <div class="hero-stats">
      <div><strong><?= count($products) ?></strong><span>Products</span></div>
      <div><strong>4.9 ★</strong><span>Avg. rating</span></div>
      <div><strong>Free</strong><span>Shipping ₱6,300+</span></div>
    </div>
  </div>
</div></section>


<section class="perks">
  <div class="perk"><span class="perk-ic">🚚</span><div><b>Free shipping over ₱6,300</b><p>Fast delivery, tracked from our door to yours.</p></div></div>
  <div class="perk"><span class="perk-ic">↩️</span><div><b>30-day easy returns</b><p>Didn’t fit? Send it back, no questions asked.</p></div></div>
  <div class="perk"><span class="perk-ic">💳</span><div><b>Pay on delivery</b><p>Pay when your order arrives. Simple and safe.</p></div></div>
  <div class="perk"><span class="perk-ic">✳</span><div><b>Built to perform</b><p>Breathable, sweat-wicking fabrics that move with you.</p></div></div>
</section>

<section class="section-wrap" style="padding-bottom:0">
  <div class="section-head-row"><div><span class="eyebrow">SHOP BY SPORT</span><h2>Find your lane.</h2></div><a class="link-arrow" href="#shop">View all gear →</a></div>
  <div class="cat-grid">
    <a class="cat-card" href="<?= e(app_url('?category=Running#shop')) ?>"><img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=80" alt="Running gear" loading="lazy"><div><strong>Running</strong><span>Shorts, leggings &amp; tops</span></div><span class="cat-cta">↗</span></a>
    <a class="cat-card" href="<?= e(app_url('?category=Training#shop')) ?>"><img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80" alt="Training gear" loading="lazy"><div><strong>Training</strong><span>Tees, tanks &amp; bras</span></div><span class="cat-cta">↗</span></a>
    <a class="cat-card" href="<?= e(app_url('?category=Outerwear#shop')) ?>"><img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=900&q=80" alt="Outerwear" loading="lazy"><div><strong>Outerwear</strong><span>Hoodies &amp; jackets</span></div><span class="cat-cta">↗</span></a>
  </div>
</section>

<section class="section-wrap" id="shop">
  <div class="section-heading"><div><span class="eyebrow">THE RALLY COLLECTION</span><h2>Find your <em>uniform.</em></h2></div><p>Considered essentials. No distractions.<br>Just you and the next thing.</p></div>
  <form class="shop-tools" action="<?= e(app_url('')) ?>" method="get">
    <?php if ($search !== ''): ?><input type="hidden" name="q" value="<?= e($search) ?>"><?php endif; ?>
    <?php if ($category !== ''): ?><input type="hidden" name="category" value="<?= e($category) ?>"><?php endif; ?>
    <div class="category-tabs">
      <a class="<?= $category === '' ? 'active' : '' ?>" href="<?= e(app_url('?sort=' . $sort . '#shop')) ?>">All gear</a>
      <a class="<?= $category === 'Running' ? 'active' : '' ?>" href="<?= e(app_url('?category=Running&sort=' . $sort . '#shop')) ?>">Running</a>
      <a class="<?= $category === 'Training' ? 'active' : '' ?>" href="<?= e(app_url('?category=Training&sort=' . $sort . '#shop')) ?>">Training</a>
      <a class="<?= $category === 'Outerwear' ? 'active' : '' ?>" href="<?= e(app_url('?category=Outerwear&sort=' . $sort . '#shop')) ?>">Outerwear</a>
    </div>
    <div class="shop-meta">
      <span><?= count($products) ?> item<?= count($products) === 1 ? '' : 's' ?></span>
      <select name="sort" aria-label="Sort products">
        <option value="featured" <?= $sort === 'featured' ? 'selected' : '' ?>>Featured</option>
        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
        <option value="discount" <?= $sort === 'discount' ? 'selected' : '' ?>>Biggest discount</option>
        <option value="price-asc" <?= $sort === 'price-asc' ? 'selected' : '' ?>>Price: low to high</option>
        <option value="price-desc" <?= $sort === 'price-desc' ? 'selected' : '' ?>>Price: high to low</option>
        <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name A–Z</option>
      </select>
    </div>
  </form>
  <?php if ($search !== ''): ?><p data-search-note style="margin:0 0 18px;color:var(--muted);font-size:13.5px">Results for “<strong style="color:var(--ink)"><?= e($search) ?></strong>” — <a href="<?= e(app_url('#shop')) ?>" style="text-decoration:underline">clear search</a></p><?php endif; ?>
  <div class="product-grid">
  <?php foreach ($products as $i => $product): ?><article class="product-card">
    <div class="product-image-wrap">
      <a class="product-image" href="<?= e(app_url('product.php?id=' . (int) $product['id'])) ?>">
        <img src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
        <span class="product-tag <?= (int) $product['featured'] ? 'hot' : '' ?>"><?= (int) $product['featured'] ? '★ BEST SELLER' : e(strtoupper($product['category'])) ?></span>
      </a>
      <button class="qv-btn" type="button" data-quick-view data-id="<?= (int) $product['id'] ?>" data-name="<?= e($product['name']) ?>" data-category="<?= e($product['category']) ?>" data-price="<?= (float) $product['price'] ?>" data-compare="<?= e(isset($product['compare_price']) && $product['compare_price'] !== null ? (string) (float) $product['compare_price'] : '') ?>" data-image="<?= e($product['image_url']) ?>" data-desc="<?= e($product['description']) ?>" data-stock="<?= (int) $product['stock'] ?>" data-url="<?= e(app_url('product.php?id=' . (int) $product['id'])) ?>" data-reviews="<?= 18 + ($i * 7) % 120 ?>">Quick view</button>
    </div>
    <button class="wish-btn" type="button" aria-label="Save <?= e($product['name']) ?> to wishlist" data-wishlist="<?= (int) $product['id'] ?>">♡</button>
    <div class="product-info"><span class="product-category"><?= e($product['category']) ?></span><a class="product-name" href="<?= e(app_url('product.php?id=' . (int) $product['id'])) ?>"><?= e($product['name']) ?></a><div class="product-rating"><span class="stars">★★★★★</span>4.9 (<?= 18 + ($i * 7) % 120 ?>)</div><span class="price"><?= money($product['price']) ?><?php if (sale_pct($product)): ?><span class="was-price"><?= money($product['compare_price']) ?></span><span class="off-badge">−<?= sale_pct($product) ?>%</span><?php endif; ?></span></div>
    <form action="<?= e(app_url('cart-action.php')) ?>" method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>"><input type="hidden" name="action" value="add"><button class="quick-add" type="submit" data-quick-add><span>🛒</span> Add to cart</button></form>
  </article><?php endforeach; ?>
  <?php if (!$products): ?><div class="empty-state"><p>No gear found.</p>Try another search or category.<br><a class="button button-dark" href="<?= e(app_url('#shop')) ?>">Browse all gear <span>↗</span></a></div><?php endif; ?>
  </div>
</section>

<?php if ($featured): ?><section class="section-wrap" id="featured-strip" style="padding-top:0">
  <div class="section-head-row"><div><span class="eyebrow">RALLY PICKS</span><h2>Most loved right now.</h2></div><a class="link-arrow" href="#shop">Shop all →</a></div>
  <div class="featured-strip">
    <a class="feature-card" href="<?= e(app_url('product.php?id=' . (int) $featured[0]['id'])) ?>"><img src="<?= e($featured[0]['image_url']) ?>" alt="<?= e($featured[0]['name']) ?>" loading="lazy"><div><span class="eyebrow" style="color:#fff">★ BESTSELLER</span><h3><?= e($featured[0]['name']) ?></h3><p><?= e($featured[0]['description']) ?></p><span class="button button-accent">Shop now — <?= money($featured[0]['price']) ?></span></div></a>
    <a class="feature-card" href="<?= e(app_url('?category=Outerwear#shop')) ?>"><img src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=1200&q=80" alt="Outerwear layering" loading="lazy"><div><span class="eyebrow" style="color:#fff">NEW SEASON</span><h3>Layer up. Stay moving.</h3><p>Hoodies and jackets for warm-ups, cool-downs and everything between.</p><span class="button button-light">Shop outerwear</span></div></a>
  </div>
</section><?php endif; ?>

<section class="manifesto"><div class="manifesto-mark">R.</div><div><span class="eyebrow" style="color:#c9cec9">WHY WE RALLY</span><h2>Progress isn’t<br>a straight line.</h2><p>It’s showing up, finding your rhythm, and moving in a way that feels like yours. We make the gear. You make it matter.</p><a href="#shop" class="arrow-link">Find your next move <span>↗</span></a></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
