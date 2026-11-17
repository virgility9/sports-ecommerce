<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params(['httponly' => true, 'secure' => $secure, 'samesite' => 'Lax']);
    session_start();
}
require_once __DIR__ . '/database.php';

function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function money(float|string $amount): string { global $config; return '$' . number_format((float) $amount, 2); }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(403); exit('Invalid request token. Go back and try again.');
    }
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!current_user()) { $_SESSION['flash'] = 'Please sign in to continue.'; header('Location: /login.php'); exit; } }
function require_admin(): void { require_login(); if (current_user()['role'] !== 'admin') { http_response_code(403); exit('Administrator access required.'); } }
function flash(string $message = ''): ?string {
    if ($message !== '') { $_SESSION['flash'] = $message; return null; }
    $value = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $value;
}
function cart_count(): int { return array_sum(array_map('intval', $_SESSION['cart'] ?? [])); }
function cart_products(): array {
    global $pdo;
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) return [];
    $ids = array_values(array_filter(array_map('intval', array_keys($cart)), fn($id) => $id > 0));
    if (!$ids) return [];
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id IN (' . implode(',', array_fill(0, count($ids), '?')) . ')');
    $stmt->execute($ids);
    $rows = $stmt->fetchAll();
    foreach ($rows as &$row) { $row['quantity'] = min(99, max(1, (int) ($cart[$row['id']] ?? 1))); $row['line_total'] = (float) $row['price'] * $row['quantity']; }
    return $rows;
}
function cart_total(array $items): float { return array_sum(array_column($items, 'line_total')); }
