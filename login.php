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
<section class="auth-section"><div class="auth-card"><span class="eyebrow">WELCOME BACK</span><h1>Good to see you.</h1><p>Sign in to check your orders and keep moving.</p><form method="post" class="form-stack"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Email address<input name="email" type="email" autocomplete="email" required value="<?= e($_POST['email'] ?? '') ?>"></label><label>Password<span class="password-field"><input name="password" type="password" autocomplete="current-password" required><button type="button" class="password-toggle" aria-label="Show password"><svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg><svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></button></span></label><button class="button button-dark" type="submit">Sign in <span>↗</span></button></form><p class="auth-switch">New to Rally? <a href="<?= e(app_url('register.php')) ?>">Create an account</a></p></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
