<?php
session_start();
require 'db.php';

$order_number = sanitize_input($_GET['order'] ?? '');

if (empty($order_number)) {
    header('Location: index.php');
    exit;
}

// Get order details
$order_query = "SELECT * FROM orders WHERE order_number = '$order_number'";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    header('Location: index.php');
    exit;
}

$order = mysqli_fetch_assoc($order_result);

// Get order items
$items_query = "SELECT * FROM order_items WHERE order_id = '{$order['id']}'";
$items_result = mysqli_query($conn, $items_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Munster Sport</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .confirmation-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        
        .order-number {
            background: #1655c2;
            color: white;
            padding: 15px;
            border-radius: 8px;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .order-details {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 30px 0;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: bold;
            color: #333;
        }
        
        .detail-value {
            color: #666;
        }
        
        .order-items {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .item-qty {
            color: #666;
            font-size: 14px;
        }
        
        .item-price {
            font-weight: bold;
            color: #1655c2;
        }
        
        .total-amount {
            background: #1655c2;
            color: white;
            padding: 15px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        
        .next-steps {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        
        .next-steps h3 {
            color: #856404;
            margin-top: 0;
        }
        
        .next-steps ul {
            text-align: left;
            color: #856404;
        }
        
        .action-buttons {
            margin-top: 30px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        
        .btn-primary {
            background: #1655c2;
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
    </style>
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
                    <a href="index.php">Home</a>
                    <a href="contact.php">Contact</a>
                    <a href="about.php">About Us</a>
                </div>
            </div>
        </header>

        <div class="confirmation-container">
            <div class="success-icon">✅</div>
            
            <h1>Order Confirmed!</h1>
            <p>Thank you for your order. We have received your order and will process it shortly.</p>
            
            <div class="order-number">
                Order #<?= htmlspecialchars($order['order_number']) ?>
            </div>
            
            <div class="order-details">
                <h3>Order Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Order Date:</span>
                    <span class="detail-value"><?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Customer Name:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['customer_name']) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['customer_email']) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['customer_phone']) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Delivery Address:</span>
                    <span class="detail-value"><?= nl2br(htmlspecialchars($order['customer_address'])) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">
                        <?= $order['payment_method'] === 'cash_on_delivery' ? 'Cash on Delivery' : 'Bank Transfer' ?>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #ffc107; font-weight: bold;">
                        <?= ucfirst($order['status']) ?>
                    </span>
                </div>
                
                <?php if (!empty($order['notes'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Order Notes:</span>
                    <span class="detail-value"><?= nl2br(htmlspecialchars($order['notes'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="order-items">
                <h3>Items Ordered</h3>
                
                <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                    <div class="order-item">
                        <div class="item-details">
                            <div class="item-name"><?= htmlspecialchars($item['product_name']) ?></div>
                            <div class="item-qty">Quantity: <?= $item['quantity'] ?> × €<?= number_format($item['price'], 2) ?></div>
                        </div>
                        <div class="item-price">€<?= number_format($item['total'], 2) ?></div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <div class="total-amount">
                Total: €<?= number_format($order['total_amount'], 2) ?>
            </div>
            
            <div class="next-steps">
                <h3>What happens next?</h3>
                <ul>
                    <li><strong>Order Processing:</strong> We'll process your order within 1-2 business days</li>
                    <li><strong>Shipping:</strong> Your order will be shipped within 3-5 business days</li>
                    <li><strong>Delivery:</strong> You'll receive your order at the provided address</li>
                    <?php if ($order['payment_method'] === 'cash_on_delivery'): ?>
                        <li><strong>Payment:</strong> Pay cash to the delivery person when you receive your order</li>
                    <?php else: ?>
                        <li><strong>Payment:</strong> Please transfer €<?= number_format($order['total_amount'], 2) ?> to our bank account. We'll send you the details via email.</li>
                    <?php endif; ?>
                    <li><strong>Updates:</strong> We'll send email updates about your order status</li>
                </ul>
            </div>
            
            <div class="action-buttons">
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                <a href="contact.php" class="btn btn-secondary">Contact Us</a>
            </div>
            
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                <h4>Order Support</h4>
                <p>If you have any questions about your order, please contact us:</p>
                <p><strong>Email:</strong> info@munstersport.com<br>
                   <strong>Phone:</strong> +353 61 123 456</p>
                <p><strong>Order Reference:</strong> <?= htmlspecialchars($order['order_number']) ?></p>
            </div>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 30px; background: white; border-top: 3px solid #1655c2; margin-top: 50px;">
        <p>&copy; <?= date('Y') ?> Munster Sport. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="about.php">About</a></p>
    </footer>
</body>
</html>