<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
$statuses = ['placed', 'processing', 'shipped', 'delivered', 'cancelled'];
$filter = $_GET['status'] ?? '';
if ($filter !== '' && !in_array($filter, $statuses, true)) { $filter = ''; }
$sql = 'SELECT o.*, u.name AS customer_name, (SELECT COALESCE(SUM(quantity), 0) FROM order_items WHERE order_id = o.id) AS items FROM orders o LEFT JOIN users u ON u.id = o.user_id';
$params = [];
if ($filter !== '') { $sql .= ' WHERE o.status = ?'; $params[] = $filter; }
$sql .= ' ORDER BY o.created_at DESC, o.id DESC';
$stmt = $pdo->prepare($sql); $stmt->execute($params); $orders = $stmt->fetchAll();
$title = 'Orders — Rally admin'; require __DIR__ . '/../includes/header.php';
?>
<section class="section-wrap admin-page">
  <div class="page-heading"><span class="eyebrow">RALLY ADMIN</span><h1>Orders.</h1><p><?= count($orders) ?> order<?= count($orders) === 1 ? '' : 's' ?><?= $filter !== '' ? ' · ' . ucfirst($filter) : '' ?>. Pay on delivery — update the status as each order moves.</p></div>
  <?php require __DIR__ . '/admin-nav.php'; ?>
  <div class="filter-pills">
    <a href="<?= e(app_url('admin/orders.php')) ?>" class="<?= $filter === '' ? 'active' : '' ?>">All</a>
    <?php foreach ($statuses as $status): ?><a href="<?= e(app_url('admin/orders.php?status=' . $status)) ?>" class="<?= $filter === $status ? 'active' : '' ?>"><?= ucfirst($status) ?></a><?php endforeach; ?>
  </div>
  <?php if (!$orders): ?><div class="empty-state"><p>No orders<?= $filter !== '' ? ' with this status' : '' ?> yet.</p></div>
  <?php else: ?>
  <div class="table-scroll"><table class="admin-table">
    <thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr></thead>
    <tbody><?php foreach ($orders as $order): ?><tr>
      <td><strong>#<?= (int) $order['id'] ?></strong></td>
      <td><?= e($order['customer_name'] ?? $order['recipient_name']) ?><br><small class="muted"><?= e($order['city']) ?></small></td>
      <td><?= e(date('M j, Y g:i A', strtotime($order['created_at']))) ?></td>
      <td><?= (int) $order['items'] ?></td>
      <td><?= money($order['total']) ?></td>
      <td><span class="status-pill <?= e($order['status']) ?>"><?= e(ucfirst($order['status'])) ?></span></td>
      <td><a href="<?= e(app_url('admin/order.php?id=' . (int) $order['id'])) ?>">View</a></td>
    </tr><?php endforeach; ?></tbody>
  </table></div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
