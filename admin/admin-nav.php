<?php $adminCurrent = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php'); ?>
<nav class="admin-nav" aria-label="Admin sections">
  <a href="<?= e(app_url('admin/')) ?>" class="<?= $adminCurrent === 'index.php' ? 'active' : '' ?>">Dashboard</a>
  <a href="<?= e(app_url('admin/products.php')) ?>" class="<?= $adminCurrent === 'products.php' ? 'active' : '' ?>">Products</a>
  <a href="<?= e(app_url('admin/orders.php')) ?>" class="<?= in_array($adminCurrent, ['orders.php', 'order.php'], true) ? 'active' : '' ?>">Orders</a>
  <a href="<?= e(app_url('admin/users.php')) ?>" class="<?= $adminCurrent === 'users.php' ? 'active' : '' ?>">Users</a>
  <a class="admin-nav-shop" href="<?= e(app_url('')) ?>">View shop ↗</a>
</nav>
