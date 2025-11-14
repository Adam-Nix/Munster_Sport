<?php
session_start();
require 'db.php';

// Get all products
$result = mysqli_query($conn, "SELECT p.*, u.username as seller_name 
                                FROM products p 
                                JOIN users u ON p.seller_id = u.id 
                                WHERE p.stock > 0
                                ORDER BY p.created_at DESC");

// Get cart count if logged in
$cart_count = 0;
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer') {
    $customer_id = $_SESSION['user_id'];
    $cart_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM cart WHERE customer_id = '$customer_id'");
    $cart_count = mysqli_fetch_assoc($cart_count_query)['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Munster Sport Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                <img src="logo.png" alt="Munster Sport" style="height: 60px; margin-right: 20px;">
                <div style="flex-grow: 1;">
                    <h1 style="margin: 0;">Munster Sport</h1>
                </div>
                <div class="nav-links">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'seller'): ?>
                            <a href="seller_dashboard.php">Dashboard</a>
                        <?php else: ?>
                            <a href="cart.php">Cart (<?= $cart_count ?>)</a>
                            <a href="view_orders.php">My Orders</a>
                        <?php endif; ?>
                        <span style="color: white;">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Customer Login</a>
                        <a href="login.php?type=employee">Employee Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="alert alert-info">
                Browse our products below. <a href="login.php" style="font-weight: bold;">Login</a> to add items to your cart and checkout.
            </div>
        <?php endif; ?>

        <h2>Available Products</h2>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="product-grid">
                <?php while ($p = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card">
                        <?php if ($p['image']): ?>
                            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-image">
                        <?php else: ?>
                            <div class="product-image" style="display: flex; align-items: center; justify-content: center; font-size: 40px;">📦</div>
                        <?php endif; ?>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($p['name']) ?></h3>
                            <p><?= htmlspecialchars(substr($p['description'], 0, 60)) ?>...</p>
                            <p style="color: #666; font-size: 12px;">By: <?= htmlspecialchars($p['seller_name']) ?></p>
                            <p style="color: #666; font-size: 12px;">Stock: <?= $p['stock'] ?></p>
                            <div class="product-price">$<?= number_format($p['price'], 2) ?></div>
                            
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer'): ?>
                                <a href="add_to_cart.php?id=<?= $p['id'] ?>" class="btn btn-primary" style="width: 100%;">Add to Cart</a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-secondary" style="width: 100%;">Login to Purchase</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>No products available</h3>
                <p>Check back later!</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>