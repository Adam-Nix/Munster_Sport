<?php
session_start();
require 'db.php';

$error = '';
$login_type = isset($_GET['role']) && $_GET['role'] === 'employee' ? 'employee' : 'customer';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    $expected_role = $_POST['login_type'] === 'employee' ? 'seller' : 'customer';

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? AND role = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $expected_role);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect based on role
        if ($user['role'] === 'seller') {
            header('Location: seller_dashboard.php');
        } else {
            header('Location: shop.php');
        }
        exit;
    } else {
        $error = "Invalid username or password for " . ($expected_role === 'seller' ? 'employee' : 'customer') . " account!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Munster Sport</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="uploads/logo.png" alt="Munster Sport" style="max-width: 200px;" onerror="this.style.display='none'">
        </div>
        
        <h2><?= $login_type === 'employee' ? 'Employee' : 'Customer' ?> Login</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="login_type" value="<?= $login_type ?>">
            
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        
        <?php if ($login_type === 'customer'): ?>
            <p style="text-align: center; margin-top: 15px;">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        <?php endif; ?>
        
        <p style="text-align: center; margin-top: 10px;">
            <?php if ($login_type === 'employee'): ?>
                <a href="login.php">Login as Customer</a> |
            <?php else: ?>
                <a href="login.php?role=employee">Login as Employee</a> |
            <?php endif; ?>
            <a href="shop.php">Back to Shop</a>
        </p>
    </div>
</body>
</html>
