<?php
session_start();
require 'db.php';

// Check if user is logged in as staff
check_login('seller');

$message = '';
$error = '';

// Handle stock update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_stock') {
        $product_id = sanitize_input($_POST['product_id']);
        $new_stock = (int)sanitize_input($_POST['stock']);
        
        if ($new_stock < 0) {
            $error = "Stock cannot be negative";
        } else {
            $update_query = "UPDATE products SET stock = '$new_stock' WHERE id = '$product_id'";
            if (mysqli_query($conn, $update_query)) {
                $message = "Stock updated successfully!";
            } else {
                $error = "Failed to update stock";
            }
        }
    }
}

// Get all products
$products_query = "SELECT p.*, u.username as seller_name FROM products p JOIN users u ON p.seller_id = u.id ORDER BY p.name ASC";
$products_result = mysqli_query($conn, $products_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management - Munster Sport</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .stock-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
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
        
        .stock-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .stock-table th {
            background: #1655c2;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }
        
        .stock-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .stock-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .product-name {
            font-weight: bold;
            color: #1655c2;
        }
        
        .stock-low {
            color: #dc3545;
            font-weight: bold;
        }
        
        .stock-ok {
            color: #28a745;
            font-weight: bold;
        }
        
        .stock-input {
            width: 80px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        
        .btn-update {
            background: #1655c2;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-update:hover {
            background: #0d47a1;
        }
        
        .action-form {
            display: flex;
            gap: 10px;
            align-items: center;
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
                    <a href="stock_management.php" class="active">Stock Management</a>
                    <a href="order_management.php">Orders</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </header>

        <div class="stock-container">
            <h2>Stock Management</h2>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <table class="stock-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Current Stock</th>
                        <th>New Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($products_result)): ?>
                        <tr>
                            <td class="product-name"><?= htmlspecialchars($product['name']) ?></td>
                            <td>€<?= number_format($product['price'], 2) ?></td>
                            <td>
                                <span class="<?= $product['stock'] < 20 ? 'stock-low' : 'stock-ok' ?>">
                                    <?= $product['stock'] ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="action-form" style="display: inline-flex;">
                                    <input type="hidden" name="action" value="update_stock">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="number" name="stock" class="stock-input" value="<?= $product['stock'] ?>" min="0" required>
                                    <button type="submit" class="btn-update">Update</button>
                                </form>
                            </td>
                            <td>
                                <?php if ($product['stock'] < 20): ?>
                                    <span style="color: #dc3545; font-weight: bold;">⚠️ Low Stock</span>
                                <?php else: ?>
                                    <span style="color: #28a745; font-weight: bold;">✓ OK</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 30px; background: white; border-top: 3px solid #1655c2; margin-top: 50px;">
        <p>&copy; <?= date('Y') ?> Munster Sport. All rights reserved.</p>
    </footer>
</body>
</html>