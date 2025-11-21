<?php
session_start();
require 'cart_functions.php';

$cart_count = get_cart_count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Munster Sport</title>
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
            <img src="images/logo.png" alt="Munster Sport" style="max-width: 300px; margin-bottom: 30px;">
            <h2 style="color: #1655c; font-size: 36px; margin-bottom: 20px;">About Munster Sport</h2>
        </div>

        <div style="max-width: 800px; margin: 40px auto;">
            <div style="background: white; padding: 30px; border: 3px solid #1655c; margin-bottom: 30px;">
                <h3 style="color: #1655c;">Company Information</h3>
                <div>
                    <p>Munster Sport is a sports equipment company based in Limerick, Ireland.</p>
                </div>
            </div>

            <div style="background: white; padding: 30px; border: 3px solid #1655c;">
                <h3 style="color: #1655c;">Products</h3>
                <div>
                    <p>We supply quality sports equipment and apparel including rugby gear, training equipment, team kits, and sports accessories.</p>
                </div>
            </div>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 30px; background: white; border-top: 3px solid #1655c; margin-top: 50px;">
        <p>&copy; <?= date('Y') ?> Munster Sport. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="about.php">About</a></p>
    </footer>
</body>
</html>
