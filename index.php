<?php
session_start();
require 'db.php';

// Get all products with stock
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
    <title>Munster Sport - Quality Sports Equipment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                <img src="images/logo.png" alt="Munster Sport" style="height: 60px; margin-right: 20px;">
                <div style="flex-grow: 1;">
                    <h1 style="margin: 0;">Munster Sport</h1>
                </div>
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <a href="contact.php">Contact</a>
                    <a href="about.php">About Us</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="staff_area.php">Staff Area</a>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Staff Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <div style="text-align: center; padding: 60px 20px; background: white; border: 3px solid #1655c; margin-bottom: 40px;">
            <img src="images/logo.png" alt="Munster Sport" style="max-width: 300px; margin-bottom: 30px;">
            <h2 style="color: #1655c; font-size: 36px; margin-bottom: 20px;">Welcome to Munster Sport</h2>
            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">
                Sports Equipment for Ireland
            </p>
            <a href="contact.php" class="btn btn-primary">Contact Us</a>
        </div>

        <!-- All Products -->
        <h2 style="text-align: center; color: #1655c; margin-bottom: 30px;">Our Products</h2>
        
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
                            <p><?= htmlspecialchars(substr($p['description'], 0, 100)) ?><?= strlen($p['description']) > 100 ? '...' : '' ?></p>
                            <div style="margin-top: 10px;">
                                <p style="color: #666; font-size: 14px;"><strong>In Stock:</strong> <?= $p['stock'] ?> units</p>
                            </div>
                            <div class="product-price">€<?= number_format($p['price'], 2) ?></div>
                            
                            <!-- Simple product info display -->
                            <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #666;">Available in store</p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #666;">No products available at the moment.</p>
        <?php endif; ?>
    </div>
    
    <footer style="text-align: center; padding: 30px; background: white; border-top: 3px solid #1655c; margin-top: 50px;">
        <p>&copy; <?= date('Y') ?> Munster Sport. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="about.php">About</a></p>
    </footer>
</body>
</html>
