<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (current_user()) { header('Location: ' . app_url('/')); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf(); $email = strtolower(trim($_POST['email'] ?? '')); $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ?'); $stmt->execute([$email]); $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) { session_regenerate_id(true); unset($user['password_hash']); $_SESSION['user'] = $user; flash('Good to have you back.'); header('Location: ' . app_url('/')); exit; }
    flash('Email or password was not recognized.');
}
$title = 'Sign in — Rally'; require __DIR__ . '/includes/header.php';
?>
<section class="auth-section"><div class="auth-card"><span class="eyebrow">WELCOME BACK</span><h1>Good to see you.</h1><p>Sign in to check your orders and keep moving.</p><form method="post" class="form-stack"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Email address<input name="email" type="email" autocomplete="email" required value="<?= e($_POST['email'] ?? '') ?>"></label><label>Password<input name="password" type="password" autocomplete="current-password" required></label><button class="button button-dark" type="submit">Sign in <span>↗</span></button></form><p class="auth-switch">New to Rally? <a href="<?= e(app_url('register.php')) ?>">Create an account</a></p></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
