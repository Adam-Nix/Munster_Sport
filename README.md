# E-Commerce Platform

A complete e-commerce web application built with PHP and MySQL, featuring separate interfaces for customers and sellers.

## Features

### For Customers
- 🛍️ Browse available products
- 🛒 Add products to cart
- 💳 Complete checkout process
- 📦 View order history
- 👤 User registration and login

### For Sellers
- 💼 Seller dashboard with sales statistics
- ➕ Add new products with descriptions and pricing
- 📊 View all products and manage inventory
- 📦 Track sales and customer orders
- 🗑️ Delete products

### Security Features
- Password hashing with bcrypt
- Prepared statements to prevent SQL injection
- Session-based authentication
- Role-based access control
- Input sanitization

## Installation

### Prerequisites
- XAMPP (or any Apache/PHP/MySQL stack)
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Setup Steps

1. **Start XAMPP**
   - Start Apache and MySQL servers

2. **Create Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the `database_setup.sql` file
   - Or manually run the SQL commands in the file

3. **Configure Database Connection**
   - Open `db.php`
   - Update database credentials if needed (default: root with no password)

4. **Access the Application**
   - Open your browser
   - Navigate to: `http://localhost/Progect_2025/`

## Default Accounts

### Test Seller Account
- Username: `seller1`
- Password: `seller123`

### Test Customer Account
- Username: `customer1`
- Password: `customer123`

## File Structure

```
Progect_2025/
├── db.php                    # Database connection and helper functions
├── index.php                 # Home page
├── login.php                 # Login page
├── register.php              # Registration page
├── logout.php                # Logout handler
├── style.css                 # Global stylesheet
├── database_setup.sql        # Database schema
│
├── Customer Files:
├── customer_shop.php         # Product browsing page
├── cart.php                  # Shopping cart
├── add_to_cart.php           # Add product to cart handler
├── remove_from_cart.php      # Remove from cart handler
├── checkout.php              # Checkout process handler
├── confirmation.php          # Order confirmation page
│
├── Seller Files:
├── seller_dashboard.php      # Seller dashboard
├── add_product.php           # Add new product
├── delete_product.php        # Delete product handler
│
└── Shared:
    └── view_orders.php       # Order history (for both customers and sellers)
```

## Database Schema

### Tables
- **users** - User accounts (customers and sellers)
- **products** - Product listings
- **cart** - Shopping cart items
- **orders** - Order records
- **order_items** - Individual items in orders

## Usage Guide

### For Customers

1. **Register/Login**
   - Click "Register" and create a customer account
   - Or login with existing credentials

2. **Shop Products**
   - Browse available products
   - Click "Add to Cart" on desired items

3. **Checkout**
   - View cart and review items
   - Click "Proceed to Checkout"
   - Order confirmation will be displayed

4. **View Orders**
   - Access order history from the navigation menu

### For Sellers

1. **Register/Login**
   - Register as a seller
   - Or login with seller credentials

2. **Add Products**
   - Click "Add Product"
   - Fill in product details (name, description, price, stock)
   - Submit to list the product

3. **Manage Products**
   - View all your products in the dashboard
   - Delete products as needed

4. **Track Sales**
   - Click "View Orders" to see all sales
   - Monitor customer purchases and order details

## Technologies Used

- **Backend**: PHP 7+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3
- **Server**: Apache (via XAMPP)

## Security Notes

- All passwords are hashed using PHP's password_hash()
- SQL injection protection via prepared statements
- Session-based authentication
- Role-based access control
- XSS protection with htmlspecialchars()

## Future Enhancements

- Product images upload
- Payment gateway integration
- Order status tracking
- Product categories and search
- User profile management
- Admin panel
- Email notifications
- Reviews and ratings

## Troubleshooting

### Database Connection Error
- Ensure MySQL is running in XAMPP
- Verify database name and credentials in `db.php`
- Check if database exists

### Session Errors
- Ensure PHP sessions are enabled
- Check file permissions
- Clear browser cookies

### Products Not Showing
- Verify products exist in database
- Check if stock is greater than 0
- Ensure seller_id matches a valid user

## License

This project is created for educational purposes.

## Support

For issues or questions, please refer to the code comments or database schema documentation.
