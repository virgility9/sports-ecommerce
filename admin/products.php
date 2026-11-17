<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?'); $stmt->execute([(int) $_GET['edit']]); $edit = $stmt->fetch();
}
$products = $pdo->query('SELECT * FROM products ORDER BY created_at DESC')->fetchAll();
$title = 'Products — Rally admin'; require __DIR__ . '/../includes/header.php';
?>
<section class="section-wrap admin-page">
  <div class="page-heading"><span class="eyebrow">RALLY ADMIN</span><h1>Products.</h1><p>Manage your collection and available stock.</p></div>
  <?php require __DIR__ . '/admin-nav.php'; ?>
  <form class="admin-form" action="<?= e(app_url('admin/product-save.php')) ?>" method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <?php if ($edit): ?><input type="hidden" name="id" value="<?= (int) $edit['id'] ?>"><?php endif; ?>
    <h2><?= $edit ? 'Edit product' : 'Add a product' ?></h2>
    <div class="form-columns">
      <label>Product name<input type="text" name="name" required maxlength="180" value="<?= e($edit['name'] ?? '') ?>"></label>
      <label>Category<input type="text" name="category" required placeholder="Training, Running, Outerwear" value="<?= e($edit['category'] ?? '') ?>"></label>
    </div>
    <label>Description<textarea name="description" required rows="3"><?= e($edit['description'] ?? '') ?></textarea></label>
    <div class="form-columns">
      <label>Price (USD)<input name="price" type="number" min="0.01" step="0.01" required value="<?= e(isset($edit['price']) ? (string) $edit['price'] : '') ?>"></label>
      <label>Stock<input name="stock" type="number" min="0" required value="<?= e(isset($edit['stock']) ? (string) $edit['stock'] : '') ?>"></label>
    </div>
    <div class="form-columns">
      <label>Was price (USD, optional)<input name="compare_price" type="number" min="0.01" step="0.01" placeholder="Leave empty for full price" value="<?= e(isset($edit['compare_price']) && $edit['compare_price'] !== null ? (string) $edit['compare_price'] : '') ?>"><small style="font-weight:400;color:var(--muted)">Higher than the price = sale badge with −% off on the card. Run database/add-compare-price.sql first.</small></label>
      <span></span>
    </div>
    <label>Image URL<input name="image_url" type="url" placeholder="https://..." value="<?= e($edit['image_url'] ?? '') ?>"></label>
    <label class="check-label"><input name="featured" type="checkbox" value="1" <?= !empty($edit['featured']) ? 'checked' : '' ?>> Feature this product</label>
    <div class="form-actions">
      <button class="button button-dark" type="submit"><?= $edit ? 'Save changes' : 'Add product' ?> <span>↗</span></button>
      <?php if ($edit): ?><a class="button button-ghost" href="<?= e(app_url('admin/products.php')) ?>">Cancel</a><?php endif; ?>
    </div>
  </form>
  <div class="admin-list"><h2>Current products (<?= count($products) ?>)</h2>
    <?php foreach ($products as $product): ?><article class="admin-product">
      <img src="<?= e($product['image_url']) ?>" alt="">
      <div><strong><?= e($product['name']) ?></strong><small><?= e($product['category']) ?> · <?= money($product['price']) ?><?= sale_pct($product) ? ' · was ' . money($product['compare_price']) . ' (−' . sale_pct($product) . '%)' : '' ?> · <?= (int) $product['stock'] ?> in stock<?= (int) $product['featured'] ? ' · ★ Rally Pick' : '' ?></small></div>
      <a class="inline-button" href="<?= e(app_url('admin/products.php?edit=' . (int) $product['id'])) ?>">Edit</a>
      <form action="<?= e(app_url('admin/product-delete.php')) ?>" method="post" onsubmit="return confirm('Delete this product?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $product['id'] ?>"><button class="inline-button muted">Delete</button></form>
    </article><?php endforeach; ?>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
