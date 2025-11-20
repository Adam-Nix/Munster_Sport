<?php
require 'db.php';

// Get all products
$result = mysqli_query($conn, "SELECT p.*, u.username as seller_name 
                                FROM products p 
                                JOIN users u ON p.seller_id = u.id 
                                WHERE p.stock > 0
                                ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Munster Sport</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                <img src="images/logo.png" alt="Munster Sport" style="height: 60px; margin-right: 20px;">
                <div style="flex-grow: 1;">
                    <h1 style="margin: 0;">Munster Sport</h1>
                    <p style="margin: 5px 0 0 0;">Quality Sports Equipment</p>
                </div>
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <a href="contact.php">Contact</a>
                    <a href="about.php">About Us</a>
                    <a href="cart.php">Cart</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="seller_dashboard.php">Dashboard</a>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php?role=staff">Staff Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <h2>Our Products</h2>
        <p style="text-align: center; margin-bottom: 30px;">Browse our complete range of Munster Sport products</p>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="product-grid">
                <?php while ($p = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card">
                        <?php if ($p['image']): ?>
                            <img src="images/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-image">
                        <?php else: ?>
                            <div class="product-image" style="display: flex; align-items: center; justify-content: center; font-size: 40px; background: #f5f5f5;">📦</div>
                        <?php endif; ?>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($p['name']) ?></h3>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                            <div style="margin-top: 10px;">
                                <p style="color: #666; font-size: 14px;"><strong>In Stock:</strong> <?= $p['stock'] ?> units</p>
                            </div>
                            <div class="product-price">€<?= number_format($p['price'], 2) ?></div>
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
    
    <footer style="text-align: center; padding: 30px; background: white; border-top: 3px solid #1655c; margin-top: 50px;">
        <p>&copy; <?= date('Y') ?> Munster Sport. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="about.php">About</a></p>
    </footer>
</body>
</html>
