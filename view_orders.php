<?php
session_start();
require 'db.php';

check_login();

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role === 'seller') {
    // Get orders for seller's products
    $result = mysqli_query($conn, "
        SELECT o.id AS order_id, o.created_at, o.total_amount, o.status,
               u.username, p.name, oi.quantity, oi.price
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        JOIN products p ON oi.product_id = p.id
        JOIN users u ON o.customer_id = u.id
        WHERE oi.seller_id = '$user_id'
        ORDER BY o.created_at DESC
    ");
} else {
    // Get orders for customer
    $result = mysqli_query($conn, "
        SELECT o.id AS order_id, o.created_at, o.total_amount, o.status,
               p.name, oi.quantity, oi.price
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.customer_id = '$user_id'
        ORDER BY o.created_at DESC
    ");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $role === 'seller' ? 'Sales' : 'My Orders' ?> - E-Commerce Platform</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📦 <?= $role === 'seller' ? 'Your Sales' : 'My Orders' ?></h1>
            <p><?= $role === 'seller' ? 'Track your product sales' : 'View your order history' ?></p>
            <div class="nav-links">
                <a href="<?= $role === 'seller' ? 'seller_dashboard.php' : 'customer_shop.php' ?>">
                    <?= $role === 'seller' ? '🏠 Dashboard' : '🏪 Shop' ?>
                </a>
                <?php if ($role === 'customer'): ?>
                    <a href="cart.php">🛒 Cart</a>
                <?php endif; ?>
                <a href="index.php">🏠 Home</a>
                <a href="logout.php">🚪 Logout</a>
            </div>
        </header>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <?php if ($role === 'seller'): ?>
                            <th>Customer</th>
                        <?php endif; ?>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($o = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong>#<?= $o['order_id'] ?></strong></td>
                            <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
                            <?php if ($role === 'seller'): ?>
                                <td><?= htmlspecialchars($o['username']) ?></td>
                            <?php endif; ?>
                            <td><?= htmlspecialchars($o['name']) ?></td>
                            <td><?= $o['quantity'] ?></td>
                            <td>$<?= number_format($o['price'], 2) ?></td>
                            <td><strong style="color: #667eea;">$<?= number_format($o['price'] * $o['quantity'], 2) ?></strong></td>
                            <td>
                                <span style="padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: 600;
                                    background: <?= $o['status'] === 'completed' ? '#d1fae5' : ($o['status'] === 'pending' ? '#fef3c7' : '#dbeafe') ?>;
                                    color: <?= $o['status'] === 'completed' ? '#065f46' : ($o['status'] === 'pending' ? '#92400e' : '#1e40af') ?>;">
                                    <?= ucfirst($o['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <h3>No orders yet</h3>
                <p><?= $role === 'seller' ? 'Sales will appear here once customers purchase your products.' : 'Start shopping to see your orders here!' ?></p>
                <a href="<?= $role === 'seller' ? 'seller_dashboard.php' : 'customer_shop.php' ?>" class="btn btn-primary">
                    <?= $role === 'seller' ? 'Go to Dashboard' : 'Start Shopping' ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
