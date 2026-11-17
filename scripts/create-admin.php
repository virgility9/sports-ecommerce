<?php
require_once __DIR__ . '/../includes/bootstrap.php';
if (PHP_SAPI !== 'cli') { http_response_code(403); exit("CLI only.\n"); }
if ($argc !== 3 || !filter_var($argv[1], FILTER_VALIDATE_EMAIL) || strlen($argv[2]) < 12) { fwrite(STDERR, "Usage: php scripts/create-admin.php email@example.com 'password-at-least-12-chars'\n"); exit(1); }
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?'); $stmt->execute([strtolower($argv[1])]);
if ($stmt->fetch()) { fwrite(STDERR, "That email already exists; no changes made.\n"); exit(1); }
$stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'admin')");
$stmt->execute(['Rally Admin', strtolower($argv[1]), password_hash($argv[2], PASSWORD_DEFAULT)]);
echo "Administrator created for {$argv[1]}\n";
