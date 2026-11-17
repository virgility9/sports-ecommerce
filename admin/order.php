<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
$statuses = ['placed', 'processing', 'shipped', 'delivered', 'cancelled'];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
    $status = $_POST['status'] ?? '';
    if ($id && in_array($status, $statuses, true)) {
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?'); $stmt->execute([$status, $id]);
        flash('Order #' . $id . ' is now ' . $status . '.');
    }
    header('Location: ' . app_url('/admin/order.php?id=' . $id)); exit;
}
$stmt = $pdo->prepare('SELECT o.*, u.name AS customer_name, u.email AS customer_email FROM orders o LEFT JOIN users u ON u.id = o.user_id WHERE o.id = ?');
$stmt->execute([$id]); $order = $stmt->fetch();
if (!$order) { flash('That order was not found.'); header('Location: ' . app_url('/admin/orders.php')); exit; }
$lineStmt = $pdo->prepare('SELECT product_name, unit_price, quantity FROM order_items WHERE order_id = ?'); $lineStmt->execute([$order['id']]); $lines = $lineStmt->fetchAll();
$title = 'Order #' . (int) $order['id'] . ' — Rally admin'; require __DIR__ . '/../includes/header.php';
?>
<section class="section-wrap admin-page">
  <div class="page-heading"><span class="eyebrow">RALLY ADMIN · ORDER #<?= (int) $order['id'] ?></span><h1><?= money($order['total']) ?> · <?= e(ucfirst($order['status'])) ?></h1><p>Placed <?= e(date('M j, Y g:i A', strtotime($order['created_at']))) ?> · <?= e($order['payment_method']) ?></p></div>
  <?php require __DIR__ . '/admin-nav.php'; ?>
  <div class="dash-grid">
    <div class="dash-panel">
      <h2>Items</h2>
      <div class="table-scroll"><table class="admin-table">
        <thead><tr><th>Product</th><th>Qty</th><th>Each</th><th>Line total</th></tr></thead>
        <tbody><?php foreach ($lines as $line): ?><tr>
          <td><?= e($line['product_name']) ?></td>
          <td><?= (int) $line['quantity'] ?></td>
          <td><?= money($line['unit_price']) ?></td>
          <td><?= money((float) $line['unit_price'] * (int) $line['quantity']) ?></td>
        </tr><?php endforeach; ?></tbody>
        <tfoot><tr><td colspan="3"><strong>Total</strong></td><td><strong><?= money($order['total']) ?></strong></td></tr></tfoot>
      </table></div>
    </div>
    <div class="dash-side">
      <div class="dash-panel">
        <h2>Customer</h2>
        <p><strong><?= e($order['customer_name'] ?? $order['recipient_name']) ?></strong><br>
        <?= e($order['customer_email'] ?? '') ?><br>
        <?= e($order['recipient_name']) ?><br>
        <?= e($order['address']) ?><br>
        <?= e($order['city']) ?> <?= e($order['postal_code']) ?></p>
      </div>
      <div class="dash-panel">
        <h2>Update status</h2>
        <form method="post" class="form-stack">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="id" value="<?= (int) $order['id'] ?>">
          <label>Status
            <select name="status"><?php foreach ($statuses as $status): ?><option value="<?= e($status) ?>" <?= $order['status'] === $status ? 'selected' : '' ?>><?= ucfirst($status) ?></option><?php endforeach; ?></select>
          </label>
          <button class="button button-dark" type="submit">Update status</button>
        </form>
        <p class="muted" style="margin-top:12px">Pay on delivery — mark delivered once the rider confirms payment.</p>
      </div>
    </div>
  </div>
  <p class="panel-link"><a href="<?= e(app_url('receipt.php?id=' . (int) $order['id'])) ?>">View receipt ↗</a> · <a href="<?= e(app_url('admin/orders.php')) ?>">← Back to all orders</a></p>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
