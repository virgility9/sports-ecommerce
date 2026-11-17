<?php
require_once __DIR__ . '/includes/bootstrap.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?'); $stmt->execute([$id ?: 0]); $product = $stmt->fetch();
if (!$product) { http_response_code(404); $title = 'Product not found'; require __DIR__ . '/includes/header.php'; echo '<section class="section-wrap"><div class="empty-state"><p>That piece is not here.</p><a class="button button-dark" href="' . e(app_url('/')) . '">Back to the collection</a></div></section>'; require __DIR__ . '/includes/footer.php'; exit; }
$relStmt = $pdo->prepare('SELECT * FROM products WHERE category = ? AND id != ? AND stock > 0 ORDER BY featured DESC LIMIT 4'); $relStmt->execute([$product['category'], $product['id']]); $related = $relStmt->fetchAll();
$lowStock = (int) $product['stock'] < 5;
$title = $product['name'] . ' — Rally'; require __DIR__ . '/includes/header.php';
?>
<section class="product-detail section-wrap">
  <div class="detail-image"><img src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>"></div>
  <div class="detail-copy">
    <a class="back-link" href="<?= e(app_url('#shop')) ?>">← Back to collection</a>
    <span class="eyebrow"><?= e(strtoupper($product['category'])) ?> / RALLY ESSENTIALS</span>
    <h1><?= e($product['name']) ?></h1>
    <div class="detail-rating"><b>★ 4.9</b> · 128 reviews · <span style="color:var(--green);font-weight:700">● In stock</span></div>
    <div class="detail-price"><?= money($product['price']) ?> <small>· or 4 payments of <?= money((float) $product['price'] / 4) ?></small></div>
    <p><?= e($product['description']) ?></p>
    <div class="stock-note <?= $lowStock ? 'low' : '' ?>"><span class="note-dot"></span><?= $lowStock ? 'Only a few left — order soon' : 'In stock and ready to move' ?> · <?= (int) $product['stock'] ?> available</div>

    <div class="size-label"><span>Select size</span><button type="button" data-size-guide>Size guide</button></div>
    <div class="size-row" role="group" aria-label="Size">
      <?php foreach (['XS','S','M','L','XL'] as $i => $size): ?><button type="button" class="size-btn <?= $i === 2 ? 'selected' : '' ?>" data-size="<?= $size ?>"><?= $size ?></button><?php endforeach; ?>
    </div>

    <form action="<?= e(app_url('cart-action.php')) ?>" method="post" class="detail-form">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
      <input type="hidden" name="action" value="add">
      <div class="qty-stepper" data-stepper><button type="button" data-step="-1" aria-label="Decrease quantity">−</button><input type="number" name="quantity" value="1" min="1" max="<?= max(1, (int) $product['stock']) ?>" aria-label="Quantity"><button type="button" data-step="1" aria-label="Increase quantity">+</button></div>
      <button class="button button-dark" type="submit" data-quick-add>Add to bag <span>↗</span></button>
    </form>
    <button class="button button-light full-button" type="button" data-wishlist="<?= (int) $product['id'] ?>" style="margin-top:12px">♡ Save to wishlist</button>

    <div class="detail-perks">
      <div>🚚 Free shipping on orders over $100</div>
      <div>↩️ Easy 30-day returns</div>
      <div>✳ Thoughtful performance — breathable, sweat-wicking fabric</div>
    </div>

    <div class="accordion">
      <details open><summary>Details &amp; fit <span>+</span></summary><p><?= e($product['description']) ?> Designed for a regular, athletic fit — true to size. Model is 6'0" and wears size M.</p></details>
      <details><summary>Shipping &amp; returns <span>+</span></summary><p>Orders ship in 1–2 business days. Free shipping over $100, otherwise $7. Returns are free within 30 days.</p></details>
      <details><summary>Care <span>+</span></summary><p>Machine wash cold, hang dry. Do not bleach or iron directly on prints.</p></details>
    </div>
  </div>
</section>

<?php if ($related): ?><section class="section-wrap related" style="padding-top:0">
  <div class="section-head-row"><div><span class="eyebrow">COMPLETE THE LOOK</span><h2>You may also like.</h2></div><a class="link-arrow" href="<?= e(app_url('?category=' . urlencode($product['category']) . '#shop')) ?>">More <?= e($product['category']) ?> →</a></div>
  <div class="product-grid">
  <?php foreach ($related as $i => $rel): ?><article class="product-card">
    <a class="product-image" href="<?= e(app_url('product.php?id=' . (int) $rel['id'])) ?>"><img src="<?= e($rel['image_url']) ?>" alt="<?= e($rel['name']) ?>" loading="lazy"><span class="product-tag"><?= e(strtoupper($rel['category'])) ?></span></a>
    <button class="wish-btn" type="button" aria-label="Save <?= e($rel['name']) ?> to wishlist" data-wishlist="<?= (int) $rel['id'] ?>">♡</button>
    <div class="product-info"><div><a class="product-name" href="<?= e(app_url('product.php?id=' . (int) $rel['id'])) ?>"><?= e($rel['name']) ?></a><span class="product-category"><?= e($rel['category']) ?></span><div class="product-rating"><b>★ 4.9</b> · <?= 24 + ($i * 11) % 90 ?> reviews</div></div><span class="price"><?= money($rel['price']) ?></span></div>
    <form action="<?= e(app_url('cart-action.php')) ?>" method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="product_id" value="<?= (int) $rel['id'] ?>"><input type="hidden" name="action" value="add"><button class="quick-add" type="submit" data-quick-add>Quick add <span>+</span></button></form>
  </article><?php endforeach; ?>
  </div>
</section><?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
