<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . app_url('/admin/')); exit; }
verify_csrf(); $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); if ($id) { $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?'); $stmt->execute([$id]); flash('Product removed. Past order records are preserved.'); }
header('Location: ' . app_url('/admin/')); exit;
