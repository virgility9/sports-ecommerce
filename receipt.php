<?php
require_once __DIR__ . '/includes/bootstrap.php'; require_login();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$stmt = $pdo->prepare('SELECT o.*, u.name AS customer_name, u.email AS customer_email FROM orders o LEFT JOIN users u ON u.id = o.user_id WHERE o.id = ?');
$stmt->execute([$id]); $order = $stmt->fetch();
if (!$order) { flash('That order was not found.'); header('Location: ' . app_url('/orders.php')); exit; }
$isAdmin = current_user()['role'] === 'admin';
if (!$isAdmin && (int) $order['user_id'] !== (int) current_user()['id']) { http_response_code(403); exit('This receipt belongs to another account.'); }
$lineStmt = $pdo->prepare('SELECT product_name, unit_price, quantity FROM order_items WHERE order_id = ?'); $lineStmt->execute([$order['id']]); $lines = $lineStmt->fetchAll();
$subtotal = 0; foreach ($lines as $line) { $subtotal += (float) $line['unit_price'] * (int) $line['quantity']; }
$shipping = max(0, (float) $order['total'] - $subtotal);
$title = 'Receipt — Order #' . (int) $order['id'] . ' — Rally'; require __DIR__ . '/includes/header.php';
?>
<section class="section-wrap receipt-page">
  <div class="receipt-card">
    <div class="receipt-head">
      <div><span class="eyebrow">RALLY — RECEIPT</span><h1>Order #<?= (int) $order['id'] ?></h1><p>Placed <?= e(date('M j, Y g:i A', strtotime($order['created_at']))) ?> · <span class="status-pill <?= e($order['status']) ?>"><?= e(ucfirst($order['status'])) ?></span></p></div>
      <div class="receipt-brand">R.</div>
    </div>
    <div class="receipt-meta">
      <div><h2>Billed to</h2><p><strong><?= e($order['customer_name'] ?? $order['recipient_name']) ?></strong><?php if (!empty($order['customer_email'])): ?><br><?= e($order['customer_email']) ?><?php endif; ?></p></div>
      <div><h2>Deliver to</h2><p><?= e($order['recipient_name']) ?><br><?= e($order['address']) ?><br><?= e($order['city']) ?> <?= e($order['postal_code']) ?></p></div>
      <div><h2>Payment</h2><p><?= e($order['payment_method']) ?><br>Due on delivery: <strong><?= money($order['total']) ?></strong></p></div>
    </div>
    <table class="receipt-table">
      <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
      <tbody><?php foreach ($lines as $line): ?><tr>
        <td><?= e($line['product_name']) ?></td>
        <td><?= (int) $line['quantity'] ?></td>
        <td><?= money($line['unit_price']) ?></td>
        <td><?= money((float) $line['unit_price'] * (int) $line['quantity']) ?></td>
      </tr><?php endforeach; ?></tbody>
      <tfoot>
        <tr><td colspan="3">Subtotal</td><td><?= money($subtotal) ?></td></tr>
        <tr><td colspan="3">Shipping</td><td><?= $shipping > 0 ? money($shipping) : 'Free' ?></td></tr>
        <tr><td colspan="3"><strong>Total</strong></td><td><strong><?= money($order['total']) ?></strong></td></tr>
      </tfoot>
    </table>
    <p class="receipt-note">Thanks for rallying with us. Questions about this order? Quote your order number.</p>
  </div>
  <div class="receipt-actions no-print">
    <button class="button button-dark" type="button" onclick="window.print()">Print receipt <span>↗</span></button>
    <a class="button button-ghost" href="<?= e(app_url('/orders.php')) ?>">Back to orders</a>
    <?php if ($isAdmin): ?><a class="button button-ghost" href="<?= e(app_url('admin/order.php?id=' . (int) $order['id'])) ?>">Back to admin order</a><?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
