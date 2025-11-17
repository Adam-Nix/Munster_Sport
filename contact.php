<?php
session_start();
require 'db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address!";
    } else {
        
        
        $success = "Thank you for contacting us!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Munster Sport</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                <img src="logo.png" alt="Munster Sport" style="height: 60px; margin-right: 20px;">
                <div style="flex-grow: 1;">
                    <h1 style="margin: 0;">Munster Sport</h1>
                </div>
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <a href="contact.php">Contact</a>
                    <a href="about.php">About Us</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="staff_area.php">Staff Area</a>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Staff Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="form-container" style="max-width: 600px; margin: 40px auto;">
            <h2>Contact Us</h2>
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Your Name:</label>
                    <input type="text" name="name" required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Your Email:</label>
                    <input type="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Subject:</label>
                    <input type="text" name="subject" required value="<?= isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Message:</label>
                    <textarea name="message" rows="6" required><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
            </form>

    
    <footer style="text-align: center; padding: 30px; background: white; border-top: 3px solid #1655c; margin-top: 50px;">
        <p>&copy; <?= date('Y') ?> Munster Sport. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="about.php">About</a></p>
    </footer>
</body>
</html>
