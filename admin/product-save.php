<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . app_url('/admin/products.php')); exit; }
verify_csrf(); $name = trim($_POST['name'] ?? ''); $category = trim($_POST['category'] ?? ''); $description = trim($_POST['description'] ?? ''); $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT); $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT); $image = trim($_POST['image_url'] ?? '');
$compare = filter_input(INPUT_POST, 'compare_price', FILTER_VALIDATE_FLOAT);
$compare = ($compare && $price && $compare > $price) ? $compare : null;
if (strlen($name) < 2 || $category === '' || $description === '' || !$price || $price <= 0 || $stock === false || $stock < 0 || ($image !== '' && !filter_var($image, FILTER_VALIDATE_URL))) { flash('Please check the product details and try again.'); header('Location: ' . app_url('/admin/products.php')); exit; }
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
$featured = isset($_POST['featured']) ? 1 : 0;
// Save with the sale price when the compare_price column exists (see
// database/add-compare-price.sql); if it hasn't been run yet, save the
// product anyway and remind the admin instead of failing.
$save = function (bool $withCompare) use ($pdo, $id, $name, $category, $description, $price, $image, $stock, $featured, $compare): void {
    if ($id) {
        if ($withCompare) { $stmt = $pdo->prepare('UPDATE products SET name = ?, category = ?, description = ?, price = ?, compare_price = ?, image_url = ?, stock = ?, featured = ? WHERE id = ?'); $stmt->execute([$name, $category, $description, $price, $compare, $image, $stock, $featured, $id]); }
        else { $stmt = $pdo->prepare('UPDATE products SET name = ?, category = ?, description = ?, price = ?, image_url = ?, stock = ?, featured = ? WHERE id = ?'); $stmt->execute([$name, $category, $description, $price, $image, $stock, $featured, $id]); }
    } else {
        if ($withCompare) { $stmt = $pdo->prepare('INSERT INTO products (name, category, description, price, compare_price, image_url, stock, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'); $stmt->execute([$name, $category, $description, $price, $compare, $image, $stock, $featured]); }
        else { $stmt = $pdo->prepare('INSERT INTO products (name, category, description, price, image_url, stock, featured) VALUES (?, ?, ?, ?, ?, ?, ?)'); $stmt->execute([$name, $category, $description, $price, $image, $stock, $featured]); }
    }
};
try { $save(true); }
catch (PDOException $exception) {
    if ($exception->getCode() === '23000') { flash('A product with that name already exists.'); header('Location: ' . app_url('/admin/products.php')); exit; }
    if ($exception->getCode() === '42S22') { $save(false); flash(($id ? 'Product updated. ' : 'Product added to the collection. ') . 'Run database/add-compare-price.sql to enable sale prices.'); header('Location: ' . app_url('/admin/products.php')); exit; }
    throw $exception;
}
flash($id ? 'Product updated.' : 'Product added to the collection.'); header('Location: ' . app_url('/admin/products.php')); exit;
