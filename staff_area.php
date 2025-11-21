<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Area - Munster Sport</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                <img src="images/logo.png" alt="Munster Sport" style="height: 60px; margin-right: 20px;">
                <div style="flex-grow: 1;">
                    <h1 style="margin: 0;">Munster Sport - Staff Area</h1>
                </div>
                <div class="nav-links">
                    <a href="index.php">Public Site</a>
                    <a href="staff_area.php">Staff Area</a>
                    <a href="stock_management.php">Stock Management</a>
                    <a href="order_management.php">Orders</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </header>

        <!-- Page Title Section -->
        <div style="text-align: center; padding: 60px 20px; background: white; border: 3px solid #1655c; margin-bottom: 40px;">
            <h2 style="color: #1655c; font-size: 36px; margin-bottom: 20px;">Staff Portal</h2>
            <p style="font-size: 18px; color: #666;">Welcome <?= htmlspecialchars($username) ?> - Secure Office Access</p>
        </div>

        <!-- Staff Resources Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
            
            <!-- Order Management -->
            <div style="background: white; padding: 30px; border: 3px solid #1655c; border-radius: 8px;">
                <h3 style="color: #1655c; margin-bottom: 20px;">📦 Order Management</h3>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 4px;">
                    <p style="margin-bottom: 15px;"><strong>Customer Orders:</strong></p>
                    <div style="text-align: center; padding: 20px;">
                        <a href="order_management.php" style="background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
                            View All Orders
                        </a>
                    </div>
                    <p style="font-size: 14px; color: #666; text-align: center;">
                        Manage customer orders, update status, and track deliveries
                    </p>
                </div>
            </div>

            <!-- Stock Management -->
            <div style="background: white; padding: 30px; border: 3px solid #1655c; border-radius: 8px;">
                <h3 style="color: #1655c; margin-bottom: 20px;">📊 Stock Management</h3>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 4px;">
                    <p style="margin-bottom: 15px;"><strong>Manage Inventory:</strong></p>
                    <div style="text-align: center; padding: 20px;">
                        <a href="stock_management.php" style="background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
                            Update Stock Levels
                        </a>
                    </div>
                    <p style="font-size: 14px; color: #666; text-align: center;">
                        View and update product inventory, monitor low stock alerts
                    </p>
                </div>
            </div>

        </div>


    </div>
</body>
</html>