<?php
session_start();
require 'db.php';

// Check if user is logged in as staff
check_login('seller');

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $order_id = sanitize_input($_POST['order_id']);
        $new_status = sanitize_input($_POST['status']);
        
        $update_query = "UPDATE orders SET status = '$new_status' WHERE id = '$order_id'";
        if (mysqli_query($conn, $update_query)) {
            $success_message = "Order status updated successfully";
        } else {
            $error_message = "Failed to update order status";
        }
    }
}

// Get all orders
$orders_query = "SELECT * FROM orders ORDER BY created_at DESC";
$orders_result = mysqli_query($conn, $orders_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - Munster Sport</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .orders-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .order-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .order-number {
            font-size: 18px;
            font-weight: bold;
            color: #1655c2;
        }
        
        .order-date {
            color: #666;
            font-size: 14px;
        }
        
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .order-details {
                grid-template-columns: 1fr;
            }
        }
        
        .detail-group h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .detail-group p {
            margin: 5px 0;
            color: #666;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered { background: #d1ecf1; color: #0c5460; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        
        .order-items {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .item-name {
            font-weight: bold;
        }
        
        .item-details {
            color: #666;
            font-size: 14px;
        }
        
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #1655c2;
            text-align: right;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #1655c2;
        }
        
        .status-form {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 15px;
        }
        
        .status-select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn-update {
            background: #1655c2;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-update:hover {
            background: #0d47a1;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
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
                    <a href="order_management.php" class="active">Orders</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </header>

        <div class="orders-container">
            <h2>Order Management</h2>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            
            <?php if (mysqli_num_rows($orders_result) > 0): ?>
                <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <div class="order-number">Order #<?= htmlspecialchars($order['order_number']) ?></div>
                                <div class="order-date"><?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></div>
                            </div>
                            <div>
                                <span class="status-badge status-<?= $order['status'] ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="order-details">
                            <div class="detail-group">
                                <h4>Customer Information</h4>
                                <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                                <p><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                                <p><strong>Payment:</strong> <?= $order['payment_method'] === 'cash_on_delivery' ? 'Cash on Delivery' : 'Bank Transfer' ?></p>
                            </div>
                            
                            <div class="detail-group">
                                <h4>Delivery Address</h4>
                                <p><?= nl2br(htmlspecialchars($order['customer_address'])) ?></p>
                                <?php if (!empty($order['notes'])): ?>
                                    <p><strong>Notes:</strong> <?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="order-items">
                            <h4>Order Items</h4>
                            <?php
                            $items_query = "SELECT * FROM order_items WHERE order_id = '{$order['id']}'";
                            $items_result = mysqli_query($conn, $items_query);
                            ?>
                            
                            <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                                <div class="order-item">
                                    <div>
                                        <div class="item-name"><?= htmlspecialchars($item['product_name']) ?></div>
                                        <div class="item-details">Qty: <?= $item['quantity'] ?> × €<?= number_format($item['price'], 2) ?></div>
                                    </div>
                                    <div>€<?= number_format($item['total'], 2) ?></div>
                                </div>
                            <?php endwhile; ?>
                            
                            <div class="total-amount">
                                Total: €<?= number_format($order['total_amount'], 2) ?>
                            </div>
                        </div>
                        
                        <form method="POST" class="status-form">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <label for="status-<?= $order['id'] ?>">Update Status:</label>
                            <select name="status" id="status-<?= $order['id'] ?>" class="status-select">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn-update">Update</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No orders found</h3>
                    <p>Orders will appear here once customers start placing them.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 30px; background: white; border-top: 3px solid #1655c2; margin-top: 50px;">
        <p>&copy; <?= date('Y') ?> Munster Sport. All rights reserved.</p>
    </footer>
</body>
</html>