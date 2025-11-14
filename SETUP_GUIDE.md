# E-Commerce Platform Setup Guide

## Quick Start Instructions

### Step 1: Start XAMPP
1. Open XAMPP Control Panel
2. Click "Start" for Apache
3. Click "Start" for MySQL
4. Wait until both show "Running" status

### Step 2: Setup Database
1. Open your web browser
2. Go to: http://localhost/phpmyadmin
3. Click "New" in the left sidebar to create a new database
4. Database name: `Db_Munster_Sport`
5. Click "Create"
6. Select the database you just created
7. Click "Import" tab
8. Click "Choose File" and select `database_setup.sql` from this folder
9. Click "Go" at the bottom
10. You should see "Import has been successfully finished"

### Step 3: Access the Application
1. Open your web browser
2. Go to: http://localhost/Progect_2025/
3. You should see the homepage!

## Test the Application

### Test as Customer
1. Click "Login" or use these credentials:
   - Username: `customer1`
   - Password: `customer123`

2. You can also register a new customer account

3. Once logged in, you can:
   - Browse products
   - Add items to cart
   - Complete checkout
   - View order history

### Test as Seller
1. Click "Seller Login" or use these credentials:
   - Username: `seller1`
   - Password: `seller123`

2. You can also register a new seller account

3. Once logged in, you can:
   - Add new products
   - View your products
   - Delete products
   - View sales and orders

## Troubleshooting

### Can't access localhost
- Make sure XAMPP Apache is running
- Try: http://127.0.0.1/Progect_2025/

### Database connection error
- Verify MySQL is running in XAMPP
- Check that database name is correct: `Db_Munster_Sport`
- Verify db.php has correct settings (default: root with no password)

### Pages show blank or errors
- Check Apache error logs in XAMPP
- Enable error display in php.ini:
  - display_errors = On
  - error_reporting = E_ALL

### Can't login
- Make sure database was imported correctly
- Check that users table has data
- Clear browser cookies and try again

### Products not showing
- Login as seller first
- Add at least one product with stock > 0
- Then login as customer to see products

## Default Database Configuration

In `db.php`:
```php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'Db_Munster_Sport';
```

If your MySQL has a different configuration, update these values.

## Features Overview

✅ User Registration & Login (Customer/Seller)
✅ Product Management (Add, View, Delete)
✅ Shopping Cart
✅ Checkout System
✅ Order History
✅ Sales Tracking
✅ Secure Password Hashing
✅ SQL Injection Protection
✅ Modern Responsive Design

## File Permissions

Make sure the following files are readable:
- All .php files
- style.css
- database_setup.sql

## Port Configuration

Default ports used:
- Apache: 80
- MySQL: 3306

If these ports are in use, you can change them in XAMPP Config.

## Next Steps

After successful setup:
1. Register a seller account
2. Add some products
3. Register a customer account
4. Shop and place orders
5. View orders from both seller and customer perspectives

## Support

For issues:
1. Check XAMPP logs
2. Verify all setup steps were completed
3. Ensure PHP version is 7.4 or higher
4. Check MySQL is properly configured

## Security Note

This is a development/educational project. For production use:
- Add password to MySQL root user
- Enable HTTPS
- Add CSRF protection
- Implement rate limiting
- Add input validation on all forms
- Use environment variables for sensitive data
