<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Info - E-Commerce Platform</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="home-hero">
            <h1>📚 E-Commerce Platform Documentation</h1>
            <p>Complete setup and reference guide</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
            
            <div class="option-card">
                <h3>🚀 Quick Start</h3>
                <p>New to the project? Start here for step-by-step setup instructions.</p>
                <a href="SETUP_GUIDE.md" class="btn btn-primary" target="_blank">Setup Guide</a>
            </div>

            <div class="option-card">
                <h3>🔍 Test Installation</h3>
                <p>Verify that everything is configured correctly and ready to use.</p>
                <a href="test_install.php" class="btn btn-success">Run Tests</a>
            </div>

            <div class="option-card">
                <h3>📖 Documentation</h3>
                <p>Complete feature list, architecture details, and usage instructions.</p>
                <a href="README.md" class="btn btn-primary" target="_blank">Read Docs</a>
            </div>

            <div class="option-card">
                <h3>📝 Quick Reference</h3>
                <p>Quick access to credentials, URLs, and common tasks.</p>
                <a href="QUICK_REFERENCE.md" class="btn btn-primary" target="_blank">View Reference</a>
            </div>

            <div class="option-card">
                <h3>🗄️ Database Setup</h3>
                <p>SQL schema for creating and initializing the database.</p>
                <a href="database_setup.sql" class="btn btn-secondary" target="_blank">View SQL</a>
            </div>

            <div class="option-card">
                <h3>🏠 Go to Application</h3>
                <p>Ready to use? Launch the e-commerce platform.</p>
                <a href="index.php" class="btn btn-success">Launch App</a>
            </div>

        </div>

        <div style="margin-top: 40px; padding: 30px; background: #f9fafb; border-radius: 10px;">
            <h2 style="color: #667eea; margin-bottom: 20px;">📋 Project Information</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <h4>Technology Stack</h4>
                    <ul>
                        <li>PHP 7.4+</li>
                        <li>MySQL 5.7+</li>
                        <li>HTML5 & CSS3</li>
                        <li>Apache Server</li>
                    </ul>
                </div>

                <div>
                    <h4>Key Features</h4>
                    <ul>
                        <li>User Authentication</li>
                        <li>Product Management</li>
                        <li>Shopping Cart</li>
                        <li>Order Processing</li>
                    </ul>
                </div>

                <div>
                    <h4>Security</h4>
                    <ul>
                        <li>Password Hashing</li>
                        <li>SQL Injection Prevention</li>
                        <li>Session Management</li>
                        <li>Input Sanitization</li>
                    </ul>
                </div>

                <div>
                    <h4>User Roles</h4>
                    <ul>
                        <li>Customer (Buyer)</li>
                        <li>Seller (Vendor)</li>
                    </ul>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #dbeafe; border-radius: 10px; border-left: 4px solid #3b82f6;">
            <h3 style="color: #1e40af; margin-bottom: 10px;">💡 Getting Started</h3>
            <ol style="color: #1e40af; line-height: 2;">
                <li>Ensure XAMPP is running (Apache + MySQL)</li>
                <li>Import <code>database_setup.sql</code> via phpMyAdmin</li>
                <li>Run the <strong>Test Installation</strong> to verify setup</li>
                <li>Launch the application and start testing!</li>
            </ol>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <h3>Default Test Accounts</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
                <div style="background: #d1fae5; padding: 20px; border-radius: 8px;">
                    <h4 style="color: #065f46;">👤 Customer</h4>
                    <p style="color: #065f46;"><strong>Username:</strong> customer1</p>
                    <p style="color: #065f46;"><strong>Password:</strong> customer123</p>
                </div>
                <div style="background: #fef3c7; padding: 20px; border-radius: 8px;">
                    <h4 style="color: #92400e;">💼 Seller</h4>
                    <p style="color: #92400e;"><strong>Username:</strong> seller1</p>
                    <p style="color: #92400e;"><strong>Password:</strong> seller123</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
