<?php
require_once __DIR__ . '/includes/bootstrap.php'; require_login();
$items = cart_products();
if (!$items) { flash('Your bag is empty. Add something before checking out.'); header('Location: ' . app_url('/cart.php')); exit; }
$subtotal = cart_total($items); $shipping = $subtotal >= 100 ? 0 : 7; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf(); $recipient = trim($_POST['recipient_name'] ?? ''); $address = trim($_POST['address'] ?? ''); $city = trim($_POST['city'] ?? ''); $postal = trim($_POST['postal_code'] ?? '');
    if (strlen($recipient) < 2 || strlen($address) < 5 || strlen($city) < 2 || strlen($postal) < 3) $error = 'Please fill in all delivery details.';
    else {
        try {
            $pdo->beginTransaction(); $total = 0; $locked = [];
            $lock = $pdo->prepare('SELECT id, name, price, stock FROM products WHERE id = ? FOR UPDATE');
            foreach ($items as $item) { $lock->execute([$item['id']]); $fresh = $lock->fetch(); if (!$fresh || $fresh['stock'] < $item['quantity']) throw new RuntimeException('An item in your bag no longer has enough stock. Please review your bag.'); $fresh['quantity'] = $item['quantity']; $total += (float) $fresh['price'] * $fresh['quantity']; $locked[] = $fresh; }
            $total += ($total >= 100 ? 0 : 7);
            $stmt = $pdo->prepare('INSERT INTO orders (user_id, total, recipient_name, address, city, postal_code) VALUES (?, ?, ?, ?, ?, ?)'); $stmt->execute([current_user()['id'], $total, $recipient, $address, $city, $postal]); $orderId = (int) $pdo->lastInsertId();
            $insert = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity) VALUES (?, ?, ?, ?, ?)'); $update = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
            foreach ($locked as $line) { $insert->execute([$orderId, $line['id'], $line['name'], $line['price'], $line['quantity']]); $update->execute([$line['quantity'], $line['id']]); }
            $pdo->commit(); $_SESSION['cart'] = []; header('Location: ' . app_url('/orders.php?placed=' . $orderId)); exit;
        } catch (RuntimeException $exception) { if ($pdo->inTransaction()) $pdo->rollBack(); $error = $exception->getMessage(); }
        catch (Throwable $exception) { if ($pdo->inTransaction()) $pdo->rollBack(); throw $exception; }
    }
}
$title = 'Checkout — Rally'; require __DIR__ . '/includes/header.php';
?>
<section class="section-wrap checkout-page"><div class="page-heading"><span class="eyebrow">ONE LAST STEP</span><h1>Let’s get it to you.</h1></div><?php if ($error): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?><div class="cart-layout"><form method="post" class="checkout-form"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><h2>Delivery details</h2><label>Full name<input name="recipient_name" autocomplete="name" required value="<?= e($_POST['recipient_name'] ?? current_user()['name']) ?>"></label><label>Street address<input name="address" autocomplete="street-address" required value="<?= e($_POST['address'] ?? '') ?>"></label><div class="form-columns"><label>City<input name="city" autocomplete="address-level2" required value="<?= e($_POST['city'] ?? '') ?>"></label><label>Postal code<input name="postal_code" autocomplete="postal-code" required value="<?= e($_POST['postal_code'] ?? '') ?>"></label></div><h2 class="payment-heading">Payment</h2><div class="payment-option"><span>◉</span><div><strong>Pay on delivery</strong><small>Payment is collected when your order arrives.</small></div></div><button class="button button-dark full-button" type="submit">Place order <span>↗</span></button><p class="fine-print">By placing your order, you agree to our store policies.</p></form><aside class="order-summary"><h2>Your order</h2><?php foreach ($items as $item): ?><div><span><?= e($item['name']) ?> × <?= (int) $item['quantity'] ?></span><span><?= money($item['line_total']) ?></span></div><?php endforeach; ?><div><span>Shipping</span><span><?= $shipping ? money($shipping) : 'Free' ?></span></div><div class="summary-total"><span>Total</span><strong><?= money($subtotal + $shipping) ?></strong></div></aside></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
