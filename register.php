<?php
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? ''); $email = strtolower(trim($_POST['email'] ?? '')); $password = $_POST['password'] ?? '';
    if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) { flash('Enter your name, a valid email, and a password of at least 8 characters.'); header('Location: /register.php'); exit; }
    try { $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)'); $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]); session_regenerate_id(true); $_SESSION['user'] = ['id' => (int) $pdo->lastInsertId(), 'name' => $name, 'email' => $email, 'role' => 'customer']; flash('Welcome to Rally — your account is ready.'); header('Location: /'); exit; }
    catch (PDOException $exception) { if ($exception->getCode() === '23000') flash('An account with that email already exists.'); else throw $exception; }
}
$title = 'Create account — Rally'; require __DIR__ . '/includes/header.php';
?>
<section class="auth-section"><div class="auth-card"><span class="eyebrow">JOIN THE MOVEMENT</span><h1>Create your account.</h1><p>Keep track of your orders and get back to the good stuff faster.</p><form method="post" class="form-stack"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Full name<input name="name" autocomplete="name" required minlength="2" value="<?= e($_POST['name'] ?? '') ?>"></label><label>Email address<input name="email" type="email" autocomplete="email" required value="<?= e($_POST['email'] ?? '') ?>"></label><label>Password<input name="password" type="password" autocomplete="new-password" minlength="8" required></label><button class="button button-dark" type="submit">Create account <span>↗</span></button></form><p class="auth-switch">Already have an account? <a href="/login.php">Sign in</a></p></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
