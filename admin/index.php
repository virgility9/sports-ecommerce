<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
$revenue = (float) $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status <> 'cancelled'")->fetchColumn();
$orderCount = (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$productCount = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$lowStockCount = (int) $pdo->query('SELECT COUNT(*) FROM products WHERE stock <= 5')->fetchColumn();
$recentOrders = $pdo->query('SELECT o.*, u.name AS customer_name FROM orders o LEFT JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC, o.id DESC LIMIT 6')->fetchAll();
$lowStock = $pdo->query('SELECT * FROM products WHERE stock <= 5 ORDER BY stock ASC, name ASC LIMIT 6')->fetchAll();
$topSellers = $pdo->query('SELECT product_name, SUM(quantity) AS sold, SUM(unit_price * quantity) AS revenue FROM order_items GROUP BY product_name ORDER BY sold DESC LIMIT 5')->fetchAll();
$title = 'Admin dashboard — Rally'; require __DIR__ . '/../includes/header.php';
?>
<section class="section-wrap admin-page">
  <div class="page-heading"><span class="eyebrow">RALLY ADMIN</span><h1>Dashboard.</h1><p>Sales, orders and stock at a glance.</p></div>
  <?php require __DIR__ . '/admin-nav.php'; ?>
  <div class="stat-grid">
    <div class="stat-card"><small>Revenue</small><strong><?= money($revenue) ?></strong><span>Excluding cancelled orders</span></div>
    <div class="stat-card"><small>Orders</small><strong><?= $orderCount ?></strong><span>All time</span></div>
    <div class="stat-card"><small>Products</small><strong><?= $productCount ?></strong><span>In the collection</span></div>
    <div class="stat-card"><small>Low stock</small><strong><?= $lowStockCount ?></strong><span>5 or fewer left</span></div>
  </div>
  <div class="dash-grid">
    <div class="dash-panel">
      <h2>Recent orders</h2>
      <?php if (!$recentOrders): ?><p class="muted">No orders yet. New orders will show up here.</p>
      <?php else: ?>
      <div class="table-scroll"><table class="admin-table">
        <thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr></thead>
        <tbody><?php foreach ($recentOrders as $order): ?><tr>
          <td><strong>#<?= (int) $order['id'] ?></strong></td>
          <td><?= e($order['customer_name'] ?? $order['recipient_name']) ?></td>
          <td><?= e(date('M j, Y', strtotime($order['created_at']))) ?></td>
          <td><?= money($order['total']) ?></td>
          <td><span class="status-pill <?= e($order['status']) ?>"><?= e(ucfirst($order['status'])) ?></span></td>
          <td><a href="<?= e(app_url('admin/order.php?id=' . (int) $order['id'])) ?>">View</a></td>
        </tr><?php endforeach; ?></tbody>
      </table></div>
      <p class="panel-link"><a href="<?= e(app_url('admin/orders.php')) ?>">View all orders →</a></p>
      <?php endif; ?>
    </div>
    <div class="dash-side">
      <div class="dash-panel">
        <h2>Low stock</h2>
        <?php if (!$lowStock): ?><p class="muted">Nothing running low. All products have more than 5 in stock.</p>
        <?php else: ?><ul class="dash-list"><?php foreach ($lowStock as $product): ?><li>
          <span><?= e($product['name']) ?></span>
          <strong class="<?= (int) $product['stock'] === 0 ? 'stock-out' : 'stock-low' ?>"><?= (int) $product['stock'] ?> left</strong>
        </li><?php endforeach; ?></ul>
        <p class="panel-link"><a href="<?= e(app_url('admin/products.php')) ?>">Manage products →</a></p>
        <?php endif; ?>
      </div>
      <div class="dash-panel">
        <h2>Top sellers</h2>
        <?php if (!$topSellers): ?><p class="muted">No sales yet. Best sellers will show up here.</p>
        <?php else: ?><ol class="dash-list numbered"><?php foreach ($topSellers as $top): ?><li>
          <span><?= e($top['product_name']) ?></span>
          <strong><?= (int) $top['sold'] ?> sold · <?= money($top['revenue']) ?></strong>
        </li><?php endforeach; ?></ol>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
