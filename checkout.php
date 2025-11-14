<?php
session_start();
require 'db.php';

check_login('customer');

$customer_id = $_SESSION['user_id'];

// Check if cart is empty
$cart_check = mysqli_query($conn, "SELECT COUNT(*) as count FROM cart WHERE customer_id = '$customer_id'");
$cart_data = mysqli_fetch_assoc($cart_check);

if ($cart_data['count'] == 0) {
    $_SESSION['error'] = "Your cart is empty!";
    header('Location: cart.php');
    exit;
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Calculate total
    $cart_result = mysqli_query($conn, "
        SELECT c.product_id, c.quantity, p.price, p.seller_id, p.stock
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.customer_id = '$customer_id'
    ");

    $total_amount = 0;
    $order_items = [];

    while ($item = mysqli_fetch_assoc($cart_result)) {
        // Check stock
        if ($item['stock'] < $item['quantity']) {
            throw new Exception("Insufficient stock for one or more items!");
        }

        $subtotal = $item['price'] * $item['quantity'];
        $total_amount += $subtotal;

        $order_items[] = [
            'product_id' => $item['product_id'],
            'seller_id' => $item['seller_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ];
    }

    // Create order
    $stmt = mysqli_prepare($conn, "INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, 'pending')");
    mysqli_stmt_bind_param($stmt, "id", $customer_id, $total_amount);
    mysqli_stmt_execute($stmt);
    $order_id = mysqli_insert_id($conn);

    // Insert order items and update stock
    foreach ($order_items as $item) {
        $stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, seller_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iiiid", $order_id, $item['product_id'], $item['seller_id'], $item['quantity'], $item['price']);
        mysqli_stmt_execute($stmt);

        // Update product stock
        $new_stock = $item['stock'] - $item['quantity'];
        mysqli_query($conn, "UPDATE products SET stock = stock - {$item['quantity']} WHERE id = {$item['product_id']}");
    }

    // Clear cart
    mysqli_query($conn, "DELETE FROM cart WHERE customer_id = '$customer_id'");

    // Commit transaction
    mysqli_commit($conn);

    $_SESSION['order_id'] = $order_id;
    $_SESSION['success'] = "Order placed successfully!";
    header('Location: confirmation.php');
    exit;

} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($conn);
    $_SESSION['error'] = "Checkout failed: " . $e->getMessage();
    header('Location: cart.php');
    exit;
}
