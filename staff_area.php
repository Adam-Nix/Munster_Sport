<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Area - Munster Sport</title>
    <link rel="stylesheet" href="style.css">
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
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </header>

        <!-- Page Title Section -->
        <div style="text-align: center; padding: 60px 20px; background: white; border: 3px solid #1655c; margin-bottom: 40px;">
            <h2 style="color: #1655c; font-size: 36px; margin-bottom: 20px;">Staff Portal</h2>
            <p style="font-size: 18px; color: #666;">Welcome <?= htmlspecialchars($username) ?> - Secure Office Access</p>
        </div>

        <!-- Staff Resources Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
            
            <!-- Secure File Downloads -->
            <div style="background: white; padding: 30px; border: 3px solid #1655c; border-radius: 8px;">
                <h3 style="color: #1655c; margin-bottom: 20px;">📁 Company Documents</h3>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 4px;">
                    <p style="margin-bottom: 15px;"><strong>Read-Only Access:</strong></p>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #e6e6e6;">
                            <a href="#" style="color: #1655c; text-decoration: none;">📄 Product Catalogues (PDF)</a>
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e6e6e6;">
                            <a href="#" style="color: #1655c; text-decoration: none;">📊 Price Lists (Excel)</a>
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e6e6e6;">
                            <a href="#" style="color: #1655c; text-decoration: none;">📝 Policy Documents (Word)</a>
                        </li>
                        <li style="padding: 8px 0;">
                            <a href="#" style="color: #1655c; text-decoration: none;">📋 Training Materials (PDF)</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Staff Shared Area -->
            <div style="background: white; padding: 30px; border: 3px solid #1655c; border-radius: 8px;">
                <h3 style="color: #1655c; margin-bottom: 20px;">💾 U: Drive Access</h3>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 4px;">
                    <p style="margin-bottom: 15px;"><strong>Shared Staff Area:</strong></p>
                    <div style="text-align: center; padding: 20px;">
                        <div style="background: #e6f2ff; color: #1655c; padding: 15px; border-radius: 8px; margin-bottom: 15px; border: 2px solid #1655c;">
                            <strong>\\server\staff_shared</strong>
                        </div>
                        <p style="color: #666; font-size: 14px;">Read/Write access for all staff<br>Access from your workstation</p>
                    </div>
                </div>
            </div>

            <!-- Staff Email -->
            <div style="background: white; padding: 30px; border: 3px solid #1655c; border-radius: 8px;">
                <h3 style="color: #1655c; margin-bottom: 20px;">📧 Staff Email</h3>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 4px;">
                    <p style="margin-bottom: 15px;"><strong>Your Email Account:</strong></p>
                    <div style="text-align: center; padding: 20px;">
                        <div style="background: #e6f2ff; color: #1655c; padding: 15px; border-radius: 8px; margin-bottom: 15px; border: 2px solid #1655c;">
                            <?= htmlspecialchars($username) ?>@munstersport.com
                        </div>
                        <p style="color: #666; font-size: 14px;">Access via Outlook on your workstation</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Security Notice -->
        <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <div style="display: flex; align-items: center;">
                <span style="font-size: 24px; margin-right: 15px;">🔒</span>
                <div>
                    <p style="margin: 0; font-weight: bold; color: #856404;">Secure Office Network Access</p>
                    <p style="margin: 5px 0 0 0; color: #856404; font-size: 14px;">
                        You are accessing company resources through a secure connection. 
                        All activity is monitored and backed up according to company security policy.
                    </p>
                </div>
            </div>
        </div>


    </div>
</body>
</html>