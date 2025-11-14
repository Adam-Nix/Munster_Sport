<?php
session_start();
require 'db.php';

check_login('customer');

$result = mysqli_query($conn, "SELECT p.*, u.username as seller_name 
                                FROM products p 
                                JOIN users u ON p.seller_id = u.id 
                                WHERE p.stock > 0
                                ORDER BY p.created_at DESC");

// Get cart count
$customer_id = $_SESSION['user_id'];
$cart_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM cart WHERE customer_id = '$customer_id'");
$cart_count = mysqli_fetch_assoc($cart_count_query)['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Shop Now</h1>
            <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
            <div class="nav-links">
                <a href="customer_shop.php">Shop</a>
                <a href="cart.php">Cart (<?= $cart_count ?>)</a>
                <a href="view_orders.php">My Orders</a>
                <a href="index.php">Home</a>
                <a href="logout.php">Logout</a>
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

        <h2>Available Products</h2>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="product-grid">
                <?php while ($p = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card">
                        <?php if ($p['image']): ?>
                            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-image">
                        <?php else: ?>
                            <div class="product-image" style="display: flex; align-items: center; justify-content: center; font-size: 40px;">�</div>
                        <?php endif; ?>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($p['name']) ?></h3>
                            <p><?= htmlspecialchars(substr($p['description'], 0, 60)) ?>...</p>
                            <p style="color: #666; font-size: 12px;">By: <?= htmlspecialchars($p['seller_name']) ?></p>
                            <p style="color: #666; font-size: 12px;">Stock: <?= $p['stock'] ?></p>
                            <div class="product-price">$<?= number_format($p['price'], 2) ?></div>
                            <a href="add_to_cart.php?id=<?= $p['id'] ?>" class="btn btn-primary" style="width: 100%;">Add to Cart</a>
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
