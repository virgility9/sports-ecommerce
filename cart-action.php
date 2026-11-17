<?php
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf(); $action = $_POST['action'] ?? ''; $id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    if ($action === 'clear') { $_SESSION['cart'] = []; flash('Your bag is now empty.'); header('Location: /cart.php'); exit; }
    $stmt = $pdo->prepare('SELECT id, stock FROM products WHERE id = ?'); $stmt->execute([$id ?: 0]); $product = $stmt->fetch();
    if (!$product) { flash('That product is no longer available.'); header('Location: /cart.php'); exit; }
    $_SESSION['cart'] ??= [];
    if ($action === 'remove') unset($_SESSION['cart'][$product['id']]);
    elseif ($action === 'update') { $qty = (int) ($_POST['quantity'] ?? 1); if ($qty < 1) unset($_SESSION['cart'][$product['id']]); else $_SESSION['cart'][$product['id']] = min($qty, (int) $product['stock'], 99); }
    elseif ($action === 'add') { $qty = max(1, (int) ($_POST['quantity'] ?? 1)); $_SESSION['cart'][$product['id']] = min((int) $product['stock'], 99, (int) ($_SESSION['cart'][$product['id']] ?? 0) + $qty); flash('Added to your bag.'); }
    header('Location: ' . ($action === 'add' ? '/cart.php' : '/cart.php')); exit;
}
header('Location: /'); exit;
