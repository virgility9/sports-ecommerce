<?php
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf(); $action = $_POST['action'] ?? ''; $id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $ajax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    $json = function (array $data, int $code = 200): void { http_response_code($code); header('Content-Type: application/json'); echo json_encode($data); exit; };
    if ($action === 'clear') { $_SESSION['cart'] = []; flash('Your cart is now empty.'); header('Location: ' . app_url('/cart.php')); exit; }
    $stmt = $pdo->prepare('SELECT id, stock FROM products WHERE id = ?'); $stmt->execute([$id ?: 0]); $product = $stmt->fetch();
    if (!$product) { if ($ajax) $json(['ok' => false, 'message' => 'That product is no longer available.'], 404); flash('That product is no longer available.'); header('Location: ' . app_url('/cart.php')); exit; }
    $_SESSION['cart'] ??= [];
    if ($action === 'remove') unset($_SESSION['cart'][$product['id']]);
    elseif ($action === 'update') { $qty = (int) ($_POST['quantity'] ?? 1); if ($qty < 1) unset($_SESSION['cart'][$product['id']]); else $_SESSION['cart'][$product['id']] = min($qty, (int) $product['stock'], 99); }
    elseif ($action === 'add') {
        if ((int) $product['stock'] < 1) { if ($ajax) $json(['ok' => false, 'message' => 'Sorry, that item is out of stock.']); flash('Sorry, that item is out of stock.'); header('Location: ' . back_url()); exit; }
        $qty = max(1, (int) ($_POST['quantity'] ?? 1)); $_SESSION['cart'][$product['id']] = min((int) $product['stock'], 99, (int) ($_SESSION['cart'][$product['id']] ?? 0) + $qty);
        if ($ajax) $json(['ok' => true, 'count' => cart_count(), 'message' => 'Added to your cart.']);
        flash('Added to your cart.'); header('Location: ' . back_url()); exit;
    }
    header('Location: ' . app_url('/cart.php')); exit;
}
header('Location: ' . app_url('/')); exit;
