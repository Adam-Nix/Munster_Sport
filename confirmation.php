<?php
session_start();
require 'db.php';

check_login('customer');

$order_id = isset($_SESSION['order_id']) ? $_SESSION['order_id'] : null;

if ($order_id) {
    $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id'");
    $order = mysqli_fetch_assoc($order_query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - E-Commerce Platform</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 80px; margin-bottom: 20px;">✅</div>
            <h1 style="color: #10b981; margin-bottom: 20px;">Order Confirmed!</h1>

            <?php if ($order): ?>
                <div class="alert alert-success" style="max-width: 500px; margin: 0 auto 30px;">
                    <p><strong>Order ID:</strong> #<?= $order['id'] ?></p>
                    <p><strong>Total Amount:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
                    <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
                    <p><strong>Order Date:</strong> <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></p>
                </div>
            <?php endif; ?>

            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">
                Thank you for your purchase! Your order has been received and is being processed.
            </p>

            <div class="nav-links" style="justify-content: center;">
                <a href="customer_shop.php" class="btn btn-primary">Continue Shopping</a>
                <a href="view_orders.php" class="btn btn-secondary">View My Orders</a>
                <a href="index.php" class="btn btn-secondary">Go Home</a>
            </div>
        </div>
    </div>
</body>
</html>
