<?php
/**
 * Installation Test Script
 * Run this file to check if your setup is correct
 */

$errors = [];
$warnings = [];
$success = [];

// Test 1: PHP Version
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    $success[] = "✅ PHP Version: " . PHP_VERSION;
} else {
    $errors[] = "❌ PHP Version too old: " . PHP_VERSION . " (Need 7.4+)";
}

// Test 2: Required Extensions
$required_extensions = ['mysqli', 'session'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        $success[] = "✅ PHP Extension '$ext' is loaded";
    } else {
        $errors[] = "❌ PHP Extension '$ext' is NOT loaded";
    }
}

// Test 3: Database Connection
try {
    require_once 'db.php';
    if ($conn) {
        $success[] = "✅ Database connection successful";
        
        // Test 4: Check Tables
        $tables = ['users', 'products', 'cart', 'orders', 'order_items'];
        foreach ($tables as $table) {
            $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
            if (mysqli_num_rows($result) > 0) {
                $success[] = "✅ Table '$table' exists";
            } else {
                $errors[] = "❌ Table '$table' does NOT exist";
            }
        }
        
        // Test 5: Check Default Users
        $user_check = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
        $user_data = mysqli_fetch_assoc($user_check);
        if ($user_data['count'] > 0) {
            $success[] = "✅ Users table has data ({$user_data['count']} users)";
        } else {
            $warnings[] = "⚠️ No users found in database (Expected test accounts)";
        }
        
    } else {
        $errors[] = "❌ Database connection failed";
    }
} catch (Exception $e) {
    $errors[] = "❌ Database error: " . $e->getMessage();
}

// Test 6: File Permissions
$required_files = [
    'index.php',
    'login.php',
    'register.php',
    'customer_shop.php',
    'seller_dashboard.php',
    'style.css'
];

foreach ($required_files as $file) {
    if (file_exists($file) && is_readable($file)) {
        $success[] = "✅ File '$file' exists and is readable";
    } else {
        $errors[] = "❌ File '$file' is missing or not readable";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Test - E-Commerce Platform</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #667eea;
            text-align: center;
            margin-bottom: 30px;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
            padding: 10px;
            margin: 5px 0;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
            padding: 10px;
            margin: 5px 0;
        }
        .warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
            padding: 10px;
            margin: 5px 0;
        }
        .summary {
            text-align: center;
            padding: 30px;
            margin-top: 30px;
            background: #f9fafb;
            border-radius: 10px;
        }
        .summary h2 {
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            font-weight: 600;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Installation Test Results</h1>
        
        <?php if (!empty($success)): ?>
            <h3 style="color: #10b981;">✅ Passed Tests (<?= count($success) ?>)</h3>
            <?php foreach ($success as $msg): ?>
                <div class="success"><?= $msg ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (!empty($warnings)): ?>
            <h3 style="color: #f59e0b; margin-top: 30px;">⚠️ Warnings (<?= count($warnings) ?>)</h3>
            <?php foreach ($warnings as $msg): ?>
                <div class="warning"><?= $msg ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <h3 style="color: #ef4444; margin-top: 30px;">❌ Failed Tests (<?= count($errors) ?>)</h3>
            <?php foreach ($errors as $msg): ?>
                <div class="error"><?= $msg ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <div class="summary">
            <?php if (empty($errors)): ?>
                <h2 style="color: #10b981;">🎉 All Tests Passed!</h2>
                <p>Your e-commerce platform is ready to use.</p>
                <a href="index.php" class="btn">Go to Homepage</a>
                <a href="login.php" class="btn">Login</a>
            <?php else: ?>
                <h2 style="color: #ef4444;">⚠️ Setup Issues Detected</h2>
                <p>Please fix the errors above before using the application.</p>
                <p>Refer to SETUP_GUIDE.md for detailed instructions.</p>
                <a href="test_install.php" class="btn">Retest</a>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #f9fafb; border-radius: 5px;">
            <h3>Quick Fix Guide:</h3>
            <ul>
                <li><strong>Database Connection Error:</strong> Make sure MySQL is running in XAMPP</li>
                <li><strong>Tables Missing:</strong> Import database_setup.sql in phpMyAdmin</li>
                <li><strong>No Users:</strong> The SQL import should create default test accounts</li>
                <li><strong>File Not Found:</strong> Ensure all project files are in the correct directory</li>
            </ul>
        </div>
    </div>
</body>
</html>
