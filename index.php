<?php
require_once __DIR__ . '/includes/bootstrap.php';
$category = trim($_GET['category'] ?? '');
$search = trim($_GET['q'] ?? '');
$sql = 'SELECT * FROM products WHERE stock > 0'; $params = [];
if ($category !== '') { $sql .= ' AND category = ?'; $params[] = $category; }
if ($search !== '') { $sql .= ' AND (name LIKE ? OR description LIKE ?)'; $params[] = '%' . $search . '%'; $params[] = '%' . $search . '%'; }
$sql .= ' ORDER BY featured DESC, created_at DESC';
$stmt = $pdo->prepare($sql); $stmt->execute($params); $products = $stmt->fetchAll();
$featured = array_filter($products, fn($product) => (int) $product['featured'] === 1);
$title = 'Rally — Made to Move'; require __DIR__ . '/includes/header.php';
?>
<section class="hero"><div class="hero-copy"><span class="eyebrow">GEAR UP. GET OUT THERE.</span><h1>Made to<br><em>move you.</em></h1><p>Performance essentials for the miles, reps, and moments that make you.</p><a href="#shop" class="button button-dark">Shop the collection <span>↘</span></a><div class="hero-note"><span class="note-dot"></span> PERFORMANCE, WITHOUT THE NOISE</div></div><div class="hero-image"><div class="hero-image-overlay"></div><span class="image-label">SPRING / SUMMER 26</span><div class="hero-sticker">FIND<br>YOUR<br>PACE<span>✳</span></div></div><div class="hero-index">01 — 03</div></section>
<section class="ticker"><div>MOVE WITH PURPOSE <span>✳</span> BUILT FOR EVERY BODY <span>✳</span> FIND YOUR PACE <span>✳</span> MOVE WITH PURPOSE <span>✳</span> BUILT FOR EVERY BODY <span>✳</span></div></section>
<section class="collection section-wrap" id="shop"><div class="section-heading"><div><span class="eyebrow">THE RALLY COLLECTION</span><h2>Find your <em>uniform.</em></h2></div><p>Considered essentials. No distractions.<br>Just you and the next thing.</p></div>
<form class="shop-tools" action="/" method="get"><div class="category-tabs"><a class="<?= $category === '' ? 'active' : '' ?>" href="/#shop">All gear</a><a class="<?= $category === 'Running' ? 'active' : '' ?>" href="/?category=Running#shop">Running</a><a class="<?= $category === 'Training' ? 'active' : '' ?>" href="/?category=Training#shop">Training</a><a class="<?= $category === 'Outerwear' ? 'active' : '' ?>" href="/?category=Outerwear#shop">Outerwear</a></div><div class="search-box"><input type="search" name="q" placeholder="Search gear" value="<?= e($search) ?>"><button aria-label="Search">⌕</button></div></form>
<div class="product-grid">
<?php foreach ($products as $i => $product): ?><article class="product-card"><a class="product-image" href="/product.php?id=<?= (int) $product['id'] ?>"><img src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>" loading="lazy"><span class="product-tag"><?= (int) $product['featured'] ? 'RALLY PICK' : e(strtoupper($product['category'])) ?></span></a><div class="product-info"><div><a class="product-name" href="/product.php?id=<?= (int) $product['id'] ?>"><?= e($product['name']) ?></a><span class="product-category"><?= e($product['category']) ?></span></div><span class="price"><?= money($product['price']) ?></span></div><form action="/cart-action.php" method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>"><input type="hidden" name="action" value="add"><button class="quick-add" type="submit">Quick add <span>+</span></button></form></article><?php endforeach; ?>
<?php if (!$products): ?><div class="empty-state">No gear found. Try another search or category.</div><?php endif; ?></div></section>
<section class="manifesto"><div class="manifesto-mark">R.</div><div><span class="eyebrow">WHY WE RALLY</span><h2>Progress isn’t<br>a straight line.</h2><p>It’s showing up, finding your rhythm, and moving in a way that feels like yours. We make the gear. You make it matter.</p><a href="#shop" class="arrow-link">Find your next move <span>↗</span></a></div><span class="manifesto-side">KEEP SHOWING UP · KEEP SHOWING UP ·</span></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
