<?php
session_start();
require 'db.php';

check_login('customer');

$customer_id = $_SESSION['user_id'];
$product_id = intval($_GET['id']);

// Check if product exists and has stock
$product_check = mysqli_query($conn, "SELECT stock FROM products WHERE id = '$product_id'");
$product = mysqli_fetch_assoc($product_check);

if (!$product || $product['stock'] <= 0) {
    $_SESSION['error'] = "Product not available!";
    header('Location: customer_shop.php');
    exit;
}

// Check if item already in cart
$cart_check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE customer_id = '$customer_id' AND product_id = '$product_id'");

if (mysqli_num_rows($cart_check) > 0) {
    // Update quantity
    $cart_item = mysqli_fetch_assoc($cart_check);
    $new_quantity = $cart_item['quantity'] + 1;
    mysqli_query($conn, "UPDATE cart SET quantity = '$new_quantity' WHERE id = '{$cart_item['id']}'");
} else {
    // Insert new cart item
    mysqli_query($conn, "INSERT INTO cart (customer_id, product_id, quantity) VALUES ('$customer_id', '$product_id', 1)");
}

$_SESSION['success'] = "Product added to cart!";
header('Location: customer_shop.php');
exit;
