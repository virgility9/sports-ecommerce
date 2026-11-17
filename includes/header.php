<?php
require_once __DIR__ . '/bootstrap.php';
$title = $title ?? 'Rally — Made to Move';
$notice = flash();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f6f5f0"><title><?= e($title) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="announcement">FREE SHIPPING ON ORDERS OVER $100 <span>—</span> BUILT FOR YOUR NEXT MOVE</div>
<header class="site-header"><a class="brand" href="/">RALLY<span>®</span></a>
  <nav class="main-nav"><a href="/#shop">Shop</a><a href="/?category=Running#shop">Running</a><a href="/?category=Training#shop">Training</a><a href="/?category=Outerwear#shop">Outerwear</a></nav>
  <div class="header-actions">
    <?php if (current_user()): ?><a class="account-link" href="/orders.php">Hi, <?= e(explode(' ', current_user()['name'])[0]) ?></a><?php else: ?><a class="account-link" href="/login.php">Sign in</a><?php endif; ?>
    <a class="cart-link" href="/cart.php">Bag <span><?= cart_count() ?></span></a>
    <?php if (current_user()): ?><form action="/logout.php" method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><button class="text-button" type="submit">Log out</button></form><?php endif; ?>
  </div>
</header>
<?php if ($notice): ?><div class="flash" role="status"><?= e($notice) ?></div><?php endif; ?>
<main>
