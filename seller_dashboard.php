<?php
session_start();
require 'db.php';

check_login('seller');

$seller_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM products WHERE seller_id = '$seller_id' ORDER BY created_at DESC");

// Get total sales
$sales_query = mysqli_query($conn, "
    SELECT COUNT(DISTINCT oi.order_id) as total_orders, 
           COALESCE(SUM(oi.price * oi.quantity), 0) as total_sales
    FROM order_items oi
    WHERE oi.seller_id = '$seller_id'
");
$sales_data = mysqli_fetch_assoc($sales_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                <img src="logo.png" alt="Munster Sport" style="height: 50px;">
                <div>
                    <h1 style="margin: 0;">Seller Dashboard</h1>
                    <p style="margin: 5px 0 0 0;">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
                </div>
            </div>
            <div class="nav-links">
                <a href="add_product.php">Add Product</a>
                <a href="view_orders.php">View Orders</a>
                <a href="seller_dashboard.php">Dashboard</a>
                <a href="shop.php">View Shop</a>
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

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
            <div style="background: #007bff; color: white; padding: 15px; border-radius: 5px;">
                <h3 style="margin-bottom: 10px; font-size: 14px;">Total Products</h3>
                <p style="font-size: 28px; font-weight: bold;"><?= mysqli_num_rows($result) ?></p>
            </div>
            <div style="background: #28a745; color: white; padding: 15px; border-radius: 5px;">
                <h3 style="margin-bottom: 10px; font-size: 14px;">Total Orders</h3>
                <p style="font-size: 28px; font-weight: bold;"><?= $sales_data['total_orders'] ?></p>
            </div>
            <div style="background: #ffc107; color: white; padding: 15px; border-radius: 5px;">
                <h3 style="margin-bottom: 10px; font-size: 14px;">Total Sales</h3>
                <p style="font-size: 28px; font-weight: bold;">$<?= number_format($sales_data['total_sales'], 2) ?></p>
            </div>
        </div>

        <h2>Your Products</h2>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                            <td><?= htmlspecialchars(substr($p['description'], 0, 50)) ?>...</td>
                            <td><strong style="color: #667eea;">$<?= number_format($p['price'], 2) ?></strong></td>
                            <td><?= $p['stock'] ?></td>
                            <td>
                                <a href="delete_product.php?id=<?= $p['id'] ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Delete this product?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <h3>No products yet</h3>
                <p>Start by adding your first product!</p>
                <a href="add_product.php" class="btn btn-primary">Add Your First Product</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
