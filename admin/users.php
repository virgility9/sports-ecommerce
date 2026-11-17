<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $userId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
    $role = $_POST['role'] ?? '';
    if ($userId && in_array($role, ['customer', 'admin'], true)) {
        if ($userId === (int) current_user()['id']) {
            flash('You cannot change your own role.');
        } elseif ($role === 'customer') {
            $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?'); $stmt->execute([$userId]); $target = $stmt->fetch();
            $adminCount = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
            if ($target && $target['role'] === 'admin' && $adminCount <= 1) {
                flash('This is the last admin account. Promote someone else first.');
            } else {
                $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?'); $stmt->execute([$role, $userId]);
                flash('Account updated.');
            }
        } else {
            $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?'); $stmt->execute([$role, $userId]);
            flash('Account updated.');
        }
    }
    header('Location: ' . app_url('/admin/users.php')); exit;
}
$users = $pdo->query("SELECT u.*, (SELECT COUNT(*) FROM orders WHERE user_id = u.id) AS orders, (SELECT COALESCE(SUM(total), 0) FROM orders WHERE user_id = u.id AND status <> 'cancelled') AS spent FROM users u ORDER BY u.created_at DESC")->fetchAll();
$title = 'Users — Rally admin'; require __DIR__ . '/../includes/header.php';
?>
<section class="section-wrap admin-page">
  <div class="page-heading"><span class="eyebrow">RALLY ADMIN</span><h1>Users.</h1><p><?= count($users) ?> account<?= count($users) === 1 ? '' : 's' ?>. Promote trusted accounts to admin — they will be able to manage products, orders and users.</p></div>
  <?php require __DIR__ . '/admin-nav.php'; ?>
  <?php if (!$users): ?><div class="empty-state"><p>No accounts yet.</p></div>
  <?php else: ?>
  <div class="table-scroll"><table class="admin-table">
    <thead><tr><th>Name</th><th>Email</th><th>Joined</th><th>Orders</th><th>Spent</th><th>Role</th><th></th></tr></thead>
    <tbody><?php foreach ($users as $user): ?><tr>
      <td><strong><?= e($user['name']) ?></strong><?= (int) $user['id'] === (int) current_user()['id'] ? ' <small class="muted">(you)</small>' : '' ?></td>
      <td><?= e($user['email']) ?></td>
      <td><?= e(date('M j, Y', strtotime($user['created_at']))) ?></td>
      <td><?= (int) $user['orders'] ?></td>
      <td><?= money($user['spent']) ?></td>
      <td><span class="status-pill <?= $user['role'] === 'admin' ? 'shipped' : '' ?>"><?= e(ucfirst($user['role'])) ?></span></td>
      <td><?php if ((int) $user['id'] !== (int) current_user()['id']): ?><form method="post" style="display:inline">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
        <input type="hidden" name="role" value="<?= $user['role'] === 'admin' ? 'customer' : 'admin' ?>">
        <button class="inline-button"><?= $user['role'] === 'admin' ? 'Remove admin' : 'Make admin' ?></button>
      </form><?php endif; ?></td>
    </tr><?php endforeach; ?></tbody>
  </table></div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
