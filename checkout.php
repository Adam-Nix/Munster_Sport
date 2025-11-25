<?php
session_start();
require 'cart_functions.php';

$cart_items = get_cart_items();
$cart_total = get_cart_total();
$cart_count = get_cart_count();

// Redirect if cart is empty
if (empty($cart_items)) {
    header('Location: cart.php');
    exit;
}

$errors = array();
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $customer_name = sanitize_input($_POST['customer_name'] ?? '');
    $customer_email = sanitize_input($_POST['customer_email'] ?? '');
    $customer_phone = sanitize_input($_POST['customer_phone'] ?? '');
    $customer_address = sanitize_input($_POST['customer_address'] ?? '');
    $payment_method = sanitize_input($_POST['payment_method'] ?? '');
    $notes = sanitize_input($_POST['notes'] ?? '');
    
    // Validation
    if (empty($customer_name)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($customer_email) || !filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email address is required";
    }
    
    if (empty($customer_phone)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($customer_address)) {
        $errors[] = "Delivery address is required";
    }
    
    if (empty($payment_method)) {
        $errors[] = "Payment method is required";
    }
    
    // Verify cart items are still available
    foreach ($cart_items as $item) {
        $product_query = "SELECT stock FROM products WHERE id = '{$item['product_id']}'";
        $product_result = mysqli_query($conn, $product_query);
        
        if (mysqli_num_rows($product_result) > 0) {
            $product = mysqli_fetch_assoc($product_result);
            if ($product['stock'] < $item['quantity']) {
                $errors[] = "Sorry, {$item['name']} only has {$product['stock']} items in stock";
            }
        } else {
            $errors[] = "Product {$item['name']} is no longer available";
        }
    }
    
    if (empty($errors)) {
        // Generate order number
        $order_number = 'MS' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Check if order number exists
        $check_order = mysqli_query($conn, "SELECT id FROM orders WHERE order_number = '$order_number'");
        while (mysqli_num_rows($check_order) > 0) {
            $order_number = 'MS' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $check_order = mysqli_query($conn, "SELECT id FROM orders WHERE order_number = '$order_number'");
        }
        
        // Start transaction
        mysqli_begin_transaction($conn);
        
        try {
            // Insert order
            $order_query = "INSERT INTO orders (order_number, customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, notes) 
                           VALUES ('$order_number', '$customer_name', '$customer_email', '$customer_phone', '$customer_address', '$cart_total', '$payment_method', '$notes')";
            
            if (!mysqli_query($conn, $order_query)) {
                throw new Exception("Failed to create order");
            }
            
            $order_id = mysqli_insert_id($conn);
            
            // Insert order items and update stock
            foreach ($cart_items as $item) {
                $item_total = $item['quantity'] * $item['price'];
                
                // Insert order item
                $item_query = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, total) 
                              VALUES ('$order_id', '{$item['product_id']}', '{$item['name']}', '{$item['quantity']}', '{$item['price']}', '$item_total')";
                
                if (!mysqli_query($conn, $item_query)) {
                    throw new Exception("Failed to add order item");
                }
                
                // Update product stock
                $update_stock = "UPDATE products SET stock = stock - {$item['quantity']} WHERE id = '{$item['product_id']}'";
                if (!mysqli_query($conn, $update_stock)) {
                    throw new Exception("Failed to update stock");
                }
            }
            
            // Clear cart
            clear_cart();
            
            // Commit transaction
            mysqli_commit($conn);
            
            // Redirect to confirmation page
            header("Location: order_confirmation.php?order=" . $order_number);
            exit;
            
        } catch (Exception $e) {
            // Rollback transaction
            mysqli_rollback($conn);
            $errors[] = "Order processing failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Munster Sports</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .checkout-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }
        
        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
        }
        
        .checkout-form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .order-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            height: fit-content;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-group textarea {
            height: 80px;
            resize: vertical;
        }
        
        .payment-methods {
            display: grid;
            gap: 10px;
        }
        
        .payment-option {
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-option:hover {
            border-color: #1655c2;
        }
        
        .payment-option.selected {
            border-color: #1655c2;
            background: #f0f4ff;
        }
        
        .payment-option input[type="radio"] {
            margin-right: 10px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
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
        
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-top: 2px solid #1655c2;
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        
        .submit-btn {
            background: #28a745;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        
        .submit-btn:hover {
            background: #218838;
        }
        
        .error-list {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .error-list ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                <img src="images/logo.png" alt="Munster Sport" style="height: 60px; margin-right: 20px;">
                <div style="flex-grow: 1;">
                    <h1 style="margin: 0;">Munster Sports</h1>
                    <p style="margin: 5px 0 0 0;">Quality Sports Equipment</p>
                </div>
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <a href="contact.php">Contact</a>
                    <a href="about.php">About Us</a>
                    <a href="cart.php">Cart (<?= $cart_count ?>)</a>
                </div>
            </div>
        </header>

        <div class="checkout-container">
            <div class="checkout-form">
                <h2>Checkout</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="error-list">
                        <strong>Please fix the following errors:</strong>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <h3>Delivery Information</h3>
                    
                    <div class="form-group">
                        <label for="customer_name">Full Name *</label>
                        <input type="text" id="customer_name" name="customer_name" required 
                               value="<?= htmlspecialchars($_POST['customer_name'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_email">Email Address *</label>
                        <input type="email" id="customer_email" name="customer_email" required 
                               value="<?= htmlspecialchars($_POST['customer_email'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_phone">Phone Number *</label>
                        <input type="tel" id="customer_phone" name="customer_phone" required 
                               value="<?= htmlspecialchars($_POST['customer_phone'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_address">Delivery Address *</label>
                        <textarea id="customer_address" name="customer_address" required 
                                  placeholder="Please include full address with postal code"><?= htmlspecialchars($_POST['customer_address'] ?? '') ?></textarea>
                    </div>
                    
                    <h3>Payment Method</h3>
                    
                    <div class="payment-methods">
                        <div class="payment-option" onclick="selectPayment('paypal')">
                            <input type="radio" id="paypal" name="payment_method" value="paypal" 
                                   <?= (($_POST['payment_method'] ?? 'paypal') === 'paypal') ? 'checked' : '' ?>>
                            <label for="paypal">
                                <strong>PayPal</strong><br>
                                <small>Pay securely with your PayPal account</small>
                            </label>
                        </div>
                        
                        <div class="payment-option" onclick="selectPayment('card')">
                            <input type="radio" id="card" name="payment_method" value="card"
                                   <?= (($_POST['payment_method'] ?? '') === 'card') ? 'checked' : '' ?>>
                            <label for="card">
                                <strong>Pay by Card</strong><br>
                                <small>Visa, Mastercard, or Debit Card</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Order Notes (Optional)</label>
                        <textarea id="notes" name="notes" 
                                  placeholder="Any special instructions for your order"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Place Order</button>
                </form>
            </div>
            
            <div class="order-summary">
                <h3>Order Summary</h3>
                
                <?php foreach ($cart_items as $item): ?>
                    <div class="order-item">
                        <div class="item-details">
                            <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                            <div class="item-qty">Qty: <?= $item['quantity'] ?> × €<?= number_format($item['price'], 2) ?></div>
                        </div>
                        <div class="item-price">€<?= number_format($item['quantity'] * $item['price'], 2) ?></div>
                    </div>
                <?php endforeach; ?>
                
                <div class="total-row">
                    <span>Total:</span>
                    <span>€<?= number_format($cart_total, 2) ?></span>
                </div>
                
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
                    <h4>Delivery Information</h4>
                    <p><strong>Delivery:</strong> 3-5 business days</p>
                    <p><strong>Delivery Cost:</strong> Free for orders over €50</p>
                    <p><strong>Return Policy:</strong> 30 days return window</p>
                </div>
            </div>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 30px; background: white; border-top: 3px solid #1655c2; margin-top: 50px;">
        <p>&copy; <?= date('Y') ?> Munster Sports. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="about.php">About</a></p>
    </footer>

    <script>
        function selectPayment(method) {
            // Remove selected class from all options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');
            
            // Select the radio button
            document.getElementById(method).checked = true;
        }
        
        // Initialize selected payment method
        document.addEventListener('DOMContentLoaded', function() {
            const selectedRadio = document.querySelector('input[name="payment_method"]:checked');
            if (selectedRadio) {
                selectedRadio.closest('.payment-option').classList.add('selected');
            }
        });
    </script>
</body>
</html>