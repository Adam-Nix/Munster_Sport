<?php
session_start();
require 'db.php';

check_login('customer');

$customer_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "
    SELECT c.id as cart_id, p.id, p.name, p.price, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.customer_id = '$customer_id'
");

$total = 0;
$item_count = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - E-Commerce Platform</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🛒 Shopping Cart</h1>
            <p>Review your items before checkout</p>
            <div class="nav-links">
                <a href="customer_shop.php">🏪 Continue Shopping</a>
                <a href="view_orders.php">📦 My Orders</a>
                <a href="index.php">🏠 Home</a>
                <a href="logout.php">🚪 Logout</a>
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

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        $item_count += $item['quantity'];
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><strong style="color: #667eea;">$<?= number_format($subtotal, 2) ?></strong></td>
                            <td>
                                <a href="remove_from_cart.php?id=<?= $item['id'] ?>"
                                   class="btn btn-danger"
                                   onclick="return confirm('Remove this item from cart?')">
                                    🗑️ Remove
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <h3>Cart Summary</h3>
                <p>Total Items: <strong><?= $item_count ?></strong></p>
                <div class="cart-total">Total: $<?= number_format($total, 2) ?></div>
                <a href="checkout.php" class="btn btn-success" style="width: 100%;">
                    💳 Proceed to Checkout
                </a>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>Your cart is empty</h3>
                <p>Add some products to get started!</p>
                <a href="customer_shop.php" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
