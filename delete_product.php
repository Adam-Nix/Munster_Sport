<?php
session_start();
require 'db.php';

check_login('seller');

$product_id = intval($_GET['id']);
$seller_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ? AND seller_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $product_id, $seller_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Product deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete product!";
}

header('Location: seller_dashboard.php');
exit;
