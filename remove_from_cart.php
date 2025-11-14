<?php
session_start();
require 'db.php';

check_login('customer');

$customer_id = $_SESSION['user_id'];
$product_id = intval($_GET['id']);

$stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE customer_id = ? AND product_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $customer_id, $product_id);
mysqli_stmt_execute($stmt);

$_SESSION['success'] = "Item removed from cart!";
header('Location: cart.php');
exit;
