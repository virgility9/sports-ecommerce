<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params(['httponly' => true, 'secure' => $secure, 'samesite' => 'Lax']);
    session_start();
}
// Base path: the URL folder this app lives in ('' at a domain root,
// '/sports-ecommerce' under localhost/sports-ecommerce/). All links,
// assets and redirects go through app_url() so the site works both ways.
$appRootFs = str_replace('\\', '/', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
$docRootFs = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']) ?: $_SERVER['DOCUMENT_ROOT']) : $appRootFs;
$basePath = '';
if ($docRootFs !== '' && str_starts_with($appRootFs, $docRootFs)) { $basePath = substr($appRootFs, strlen($docRootFs)); }
if ($basePath === '' || $basePath === '/') {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    if (str_ends_with($scriptDir, '/admin')) { $scriptDir = substr($scriptDir, 0, -6); }
    if ($scriptDir !== '/' && $scriptDir !== '.' && $scriptDir !== '') { $basePath = $scriptDir; }
}
$basePath = rtrim($basePath, '/');
function app_url(string $path): string { global $basePath; $path = '/' . ltrim($path, '/'); return ($basePath === '' ? '' : $basePath) . $path; }
// Where the visitor just came from, for "stay on this page" redirects.
// Only same-site referrers are trusted (prevents open redirects); anything
// else falls back to the home page.
function back_url(): string {
    $ref = $_SERVER['HTTP_REFERER'] ?? '';
    if ($ref !== '') {
        $parts = parse_url($ref);
        $host = $parts['host'] ?? '';
        if ($host === '' || $host === ($_SERVER['HTTP_HOST'] ?? '')) {
            $path = $parts['path'] ?? '/';
            $query = isset($parts['query']) ? '?' . $parts['query'] : '';
            return $path . $query;
        }
    }
    return app_url('/');
}
require_once __DIR__ . '/database.php';

function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function money(float|string $amount): string { global $config; return '$' . number_format((float) $amount, 2); }
// Sale discount percent (0 when not on sale). compare_price is the optional
// "was" price; NULL/absent means full price. Safe before the column exists.
function sale_pct(array $product): int { $compare = (float) ($product['compare_price'] ?? 0); $price = (float) $product['price']; return ($compare > $price && $compare > 0) ? (int) round((1 - $price / $compare) * 100) : 0; }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        // AJAX callers (add to cart) get JSON so the script can show the
        // message instead of falling back to a full-page reload.
        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
            http_response_code(403); header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Session expired — refresh the page and try again.']);
            exit;
        }
        http_response_code(403); exit('Invalid request token. Go back and try again.');
    }
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!current_user()) { $_SESSION['flash'] = 'Please sign in to continue.'; header('Location: ' . app_url('/login.php')); exit; } }
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
