<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /admin/'); exit; }
verify_csrf(); $name = trim($_POST['name'] ?? ''); $category = trim($_POST['category'] ?? ''); $description = trim($_POST['description'] ?? ''); $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT); $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT); $image = trim($_POST['image_url'] ?? '');
if (strlen($name) < 2 || $category === '' || $description === '' || !$price || $price <= 0 || $stock === false || $stock < 0 || ($image !== '' && !filter_var($image, FILTER_VALIDATE_URL))) { flash('Please check the product details and try again.'); header('Location: /admin/'); exit; }
$stmt = $pdo->prepare('INSERT INTO products (name, category, description, price, image_url, stock, featured) VALUES (?, ?, ?, ?, ?, ?, ?)'); $stmt->execute([$name, $category, $description, $price, $image, $stock, isset($_POST['featured']) ? 1 : 0]); flash('Product added to the collection.'); header('Location: /admin/'); exit;
