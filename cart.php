<?php
session_start();
require 'cart_functions.php';

$cart_items = get_cart_items();
$cart_total = get_cart_total();
$cart_count = get_cart_count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Munster Sport</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .cart-container h2 {
            color: #1655c2;
            font-size: 32px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #1655c2;
        }
        
        .cart-wrapper {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .cart-wrapper {
                grid-template-columns: 1fr;
            }
        }
        
        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 20px;
            align-items: center;
            padding: 20px;
            border: 1px solid #e0e0e0;
            margin-bottom: 15px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            box-shadow: 0 4px 12px rgba(22, 85, 194, 0.1);
            border-color: #1655c2;
        }
        
        .cart-item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            background: #f5f5f5;
        }
        
        .cart-item-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100px;
        }
        
        .cart-item-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .cart-item-description {
            color: #888;
            font-size: 13px;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .stock-info {
            font-size: 12px;
            color: #666;
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 4px;
            width: fit-content;
            margin-top: 5px;
        }
        
        .cart-item-price {
            color: #1655c2;
            font-weight: 600;
            font-size: 15px;
        }
        
        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-self: center;
        }

        .cart-item-controls label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .quantity-input {
            width: 65px;
            padding: 8px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-weight: 600;
            transition: border-color 0.3s;
        }

        .quantity-input:focus {
            outline: none;
            border-color: #1655c2;
        }
        
        .btn-small {
            padding: 8px 12px;
            font-size: 13px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-remove {
            background: #ff4757;
            color: white;
        }
        
        .btn-remove:hover {
            background: #ff3838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 71, 87, 0.3);
        }

        .item-subtotal {
            text-align: right;
            min-width: 80px;
            font-weight: 700;
            color: #1655c2;
            font-size: 16px;
        }
        
        .cart-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 30px;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: sticky;
            top: 20px;
        }

        .cart-summary h3 {
            color: #1655c2;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            color: #666;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row.items-count {
            font-size: 13px;
            color: #999;
        }
        
        .total-amount {
            font-size: 28px;
            font-weight: 700;
            color: #1655c2;
            margin: 20px 0;
            padding: 15px 0;
            border-top: 2px solid #1655c2;
            border-bottom: 2px solid #1655c2;
        }
        
        .checkout-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 14px 24px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: block;
            width: 100%;
            text-align: center;
            transition: all 0.3s;
            margin-bottom: 12px;
        }
        
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.3);
        }
        
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-cart-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .empty-cart h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .empty-cart p {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        .continue-shopping {
            background: #1655c2;
            color: white;
            padding: 12px 28px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .continue-shopping:hover {
            background: #0d47a1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(22, 85, 194, 0.3);
        }
        
        .alert {
            padding: 14px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: none;
            font-weight: 500;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #ff4757;
        }

        @media (max-width: 768px) {
            .cart-item {
                grid-template-columns: 80px 1fr;
            }

            .cart-item-image {
                width: 80px;
                height: 80px;
            }

            .cart-item-controls {
                grid-column: 1 / -1;
                justify-self: flex-start;
            }

            .item-subtotal {
                grid-column: 1 / -1;
                margin-top: 10px;
                text-align: left;
            }

            .cart-summary {
                position: static;
            }
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

                    <a href="contact.php">Contact</a>
                    <a href="about.php">About Us</a>
                    <a href="cart.php" class="active">Cart (<?= $cart_count ?>)</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="seller_dashboard.php">Dashboard</a>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php?role=staff">Staff Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="cart-container">
            <h2>Shopping Cart</h2>
            
            <div id="cart-alerts"></div>
            
            <?php if (empty($cart_items)): ?>
                <div class="empty-cart">
                    <h3>Your cart is empty</h3>
                    <p>Browse our products and add items to your cart!</p>
                    <a href="index.php" class="continue-shopping">Continue Shopping</a>
                </div>
            <?php else: ?>
                <div class="cart-wrapper">
                    <div id="cart-items">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item" data-product-id="<?= $item['product_id'] ?>">
                                <?php if ($item['image']): ?>
                                    <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-image">
                                <?php else: ?>
                                    <div class="cart-item-image" style="display: flex; align-items: center; justify-content: center; font-size: 24px; background: #f5f5f5;">📦</div>
                                <?php endif; ?>
                                
                                <div class="cart-item-details">
                                    <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="cart-item-description"><?= htmlspecialchars($item['description']) ?></div>
                                    <div class="cart-item-price">€<?= number_format($item['price'], 2) ?> each</div>
                                    <div class="stock-info">
                                        Stock: <?= $item['stock'] ?> available
                                    </div>
                                </div>
                                
                                <div class="cart-item-controls">
                                    <label for="qty-<?= $item['product_id'] ?>">Qty:</label>
                                    <input type="number" 
                                           id="qty-<?= $item['product_id'] ?>" 
                                           class="quantity-input" 
                                           value="<?= $item['quantity'] ?>" 
                                           min="1" 
                                           max="<?= $item['stock'] ?>"
                                           onchange="updateQuantity(<?= $item['product_id'] ?>, this.value)">
                                    
                                    <button class="btn-small btn-remove" onclick="removeFromCart(<?= $item['product_id'] ?>)">
                                        Remove
                                    </button>
                                </div>
                                
                                <div class="item-subtotal">
                                    €<?= number_format($item['quantity'] * $item['price'], 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="cart-summary">
                        <h3>Order Summary</h3>
                        <div class="total-amount">
                            €<span id="cart-total"><?= number_format($cart_total, 2) ?></span>
                        </div>
                        <div class="summary-row items-count">
                            <strong><?= $cart_count ?></strong> item(s)
                        </div>
                        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                        <a href="index.php" class="continue-shopping">Continue Shopping</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 30px; background: white; border-top: 3px solid #1655c2; margin-top: 50px;">
        <p>&copy; <?= date('Y') ?> Munster Sport. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="about.php">About</a></p>
    </footer>

    <script>
        function showAlert(message, type) {
            const alertsContainer = document.getElementById('cart-alerts');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alert.style.display = 'block';
            
            alertsContainer.innerHTML = '';
            alertsContainer.appendChild(alert);
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 3000);
        }
        
        function updateQuantity(productId, quantity) {
            fetch('cart_functions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update_quantity&product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Refresh to update totals
                } else {
                    showAlert(data.message, 'error');
                    // Reset quantity input to previous value
                    document.getElementById(`qty-${productId}`).value = quantity - 1;
                }
            })
            .catch(error => {
                showAlert('Error updating cart', 'error');
                console.error('Error:', error);
            });
        }
        
        function removeFromCart(productId) {
            if (confirm('Remove this item from your cart?')) {
                fetch('cart_functions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=remove_from_cart&product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Error removing item', 'error');
                    console.error('Error:', error);
                });
            }
        }
    </script>
</body>
</html>