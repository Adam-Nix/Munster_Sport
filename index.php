<?php
session_start();
require 'cart_functions.php';

$cart_count = get_cart_count();

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
    <title>Munster Sports - Quality Sports Equipment in Munster</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                <img src="images/logo.png" alt="Munster Sports" style="height: 60px; margin-right: 20px;">
                <div style="flex-grow: 1;">
                    <h1 style="margin: 0;">Munster Sports</h1>
                    <p style="margin: 4px 0 0; color: #1655c; font-size: 14px; font-weight: 600;">Proudly supporting Munster Rugby</p>
                </div>
                <div class="nav-links">
                    <a href="index.php">Home</a>

                    <a href="contact.php">Contact</a>
                    <a href="about.php">About Us</a>
                    <a href="cart.php">Cart (<?= $cart_count ?>)</a>
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
            <img src="images/logo.png" alt="Munster Sports" style="max-width: 300px; margin-bottom: 30px;">
            <h2 style="color: #1655c; font-size: 36px; margin-bottom: 20px;">Welcome to Munster Sports</h2>
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
                            
                            <!-- Purchase Option -->
                            <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 4px;">
                                <?php if ($p['stock'] > 0): ?>
                                    <p style="margin: 0 0 10px 0; font-size: 14px; color: #28a745; font-weight: bold;">✓ Available in store (<?= $p['stock'] ?> left)</p>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="number" id="qty-<?= $p['id'] ?>" class="qty-input" value="1" min="1" max="<?= $p['stock'] ?>" style="width: 60px; padding: 6px; border: 1px solid #ddd; border-radius: 4px;">
                                        <button onclick="addToCart(<?= $p['id'] ?>)" style="flex: 1; background: #28a745; color: white; border: none; padding: 8px; border-radius: 4px; cursor: pointer; font-weight: bold;">Add to Cart</button>
                                    </div>
                                <?php else: ?>
                                    <p style="margin: 0; font-size: 14px; color: #dc3545; font-weight: bold;">✗ Out of Stock</p>
                                <?php endif; ?>
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
        <p>&copy; <?= date('Y') ?> Munster Sports. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="about.php">About</a></p>
    </footer>

    <style>
        .qty-input {
            width: 60px !important;
        }
    </style>

    <script>
        function addToCart(productId) {
            const quantity = document.getElementById('qty-' + productId).value;
            
            fetch('cart_functions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=add_to_cart&product_id=' + productId + '&quantity=' + quantity
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Item added to cart!');
                    // Reload to update cart count
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error adding to cart');
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
