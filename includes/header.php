<?php
require_once __DIR__ . '/bootstrap.php';
$title = $title ?? 'Rally — Made to Move';
$notice = flash();
$currentCategory = trim($_GET['category'] ?? '');
$currentSearch = trim($_GET['q'] ?? '');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#ffffff"><meta name="description" content="Rally — performance sportswear for running, training and outerwear.">
  <title><?= e($title) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" type="image/svg+xml" href="<?= e(app_url('assets/img/logo-mark.svg')) ?>">
  <link rel="stylesheet" href="<?= e(app_url('assets/css/style.css')) ?>">
</head>
<body>
<div class="announcement">FREE SHIPPING ON ORDERS OVER $100 <span>✳</span> 30-DAY EASY RETURNS <span>✳</span> <a href="<?= e(app_url('#shop')) ?>">SHOP NEW DROPS</a></div>
<header class="site-header" id="siteHeader">
  <button class="icon-btn menu-btn" type="button" aria-label="Open menu" aria-expanded="false" data-menu-btn>☰</button>
  <a class="brand" href="<?= e(app_url('')) ?>"><img class="brand-mark" src="<?= e(app_url('assets/img/logo-mark.svg')) ?>" alt="" width="34" height="34">RALLY<span>®</span></a>
  <nav class="main-nav" data-main-nav aria-label="Main navigation">
    <a href="<?= e(app_url('#shop')) ?>" class="<?= $currentCategory === '' && $currentSearch === '' ? 'active' : '' ?>">Shop all</a>
    <a href="<?= e(app_url('?category=Running#shop')) ?>" class="<?= $currentCategory === 'Running' ? 'active' : '' ?>">Running</a>
    <a href="<?= e(app_url('?category=Training#shop')) ?>" class="<?= $currentCategory === 'Training' ? 'active' : '' ?>">Training</a>
    <a href="<?= e(app_url('?category=Outerwear#shop')) ?>" class="<?= $currentCategory === 'Outerwear' ? 'active' : '' ?>">Outerwear</a>
    <?php if (current_user() && current_user()['role'] === 'admin'): ?><a class="nav-admin-link" href="<?= e(app_url('admin/')) ?>">Admin dashboard</a><?php endif; ?>
  </nav>
  <div class="header-search">
    <form action="<?= e(app_url('')) ?>" method="get" role="search">
      <input type="search" name="q" placeholder="Search shoes, tees, hoodies…" value="<?= e($currentSearch) ?>" aria-label="Search gear">
      <button type="submit" aria-label="Search">⌕</button>
    </form>
  </div>
  <div class="header-actions">
    <?php if (current_user()): ?><a class="account-link" href="<?= e(app_url('orders.php')) ?>">Hi, <?= e(explode(' ', current_user()['name'])[0]) ?></a><?php else: ?><a class="account-link" href="<?= e(app_url('login.php')) ?>">Sign in</a><?php endif; ?>
    <?php if (current_user() && current_user()['role'] === 'admin'): ?><a class="account-link" href="<?= e(app_url('admin/')) ?>">Admin</a><?php endif; ?>
    <div class="cart-menu" data-cart-menu>
      <button class="cart-menu-btn" type="button" aria-label="Cart menu" aria-expanded="false" aria-haspopup="true" data-cart-menu-btn><span class="cart-menu-ic">☰</span><span class="cart-menu-cart">🛒</span><span class="cart-count"><?= cart_count() ?></span></button>
      <div class="cart-dropdown" role="menu" aria-label="Cart menu">
        <a href="<?= e(app_url('cart.php')) ?>" role="menuitem">🛒 View cart <span><?= cart_count() ?> item<?= cart_count() === 1 ? '' : 's' ?></span></a>
        <a href="<?= e(app_url('orders.php')) ?>" role="menuitem">📋 Order history</a>
      </div>
    </div>
    <?php if (current_user()): ?><form action="<?= e(app_url('logout.php')) ?>" method="post" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><button class="text-button" type="submit">Log out</button></form><?php endif; ?>
  </div>
</header>
<?php if ($notice): ?><div class="flash" role="status"><?= e($notice) ?></div><?php endif; ?>
<main>
