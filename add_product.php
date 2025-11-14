<?php
session_start();
require 'db.php';

check_login('seller');

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_SESSION['user_id'];
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    
    $image_path = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['image']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_filename = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
            $upload_path = 'uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_path = $new_filename;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Only JPG, PNG, and GIF images are allowed.";
        }
    }

    if (!$error) {
        if ($price <= 0) {
            $error = "Price must be greater than 0!";
        } elseif ($stock < 0) {
            $error = "Stock cannot be negative!";
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO products (seller_id, name, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "issdis", $seller_id, $name, $description, $price, $stock, $image_path);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Product added successfully!";
            } else {
                $error = "Failed to add product. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="logo.png" alt="Munster Sport" style="max-width: 200px;">
        </div>
        <h2>Add New Product</h2>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?= $success ?>
                <br><a href="seller_dashboard.php">Go to Dashboard</a>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label>Price ($):</label>
                <input type="number" name="price" step="0.01" min="0.01" required>
            </div>

            <div class="form-group">
                <label>Stock:</label>
                <input type="number" name="stock" min="0" required>
            </div>

            <div class="form-group">
                <label>Product Image:</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-success" style="width: 100%;">Add Product</button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            <a href="seller_dashboard.php">Back to Dashboard</a>
        </p>
    </div>
</body>
</html>
