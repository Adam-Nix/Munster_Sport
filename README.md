# Munster Sport E-Commerce Website

A complete e-commerce web application for Munster Sport, built with PHP and MySQL.

## Features

### Public Site

- Browse Munster Sport products
- Add products to shopping cart
- Complete checkout with customer details
- Order confirmation with order number
- Contact form

### Staff Area

- Staff dashboard with quick access
- Order management system
- Stock management and inventory control
- Secure staff login
- Role-based access control

### Security Features

- Password hashing with bcrypt
- Session-based authentication
- Input sanitization
- SQL injection prevention

## Installation

### Prerequisites

- XAMPP (Apache, PHP, MySQL)
- PHP 8.2 or higher
- MySQL database

### Setup Steps

1. **Start XAMPP**
   - Start Apache and MySQL servers

2. **Create Database**
   - Open phpMyAdmin (<http://localhost/phpmyadmin>)
   - Import the `database_setup.sql` file

3. **Configure Database Connection**
   - Open `db.php`
   - Update database credentials if needed (default: root with no password)

4. **Access the Application**
   - Public site: <http://localhost/Munster_Sport/>
   - Staff login: <http://localhost/Munster_Sport/login.php>

## Default Staff Accounts

All staff accounts use password: `Munster123`

- Username: `adam`
- Username: `brandon`
- Username: `darren`
- Username: `ernest`
- Username: `admin`

## File Structure

```text
Munster_Sport/
├── index.php              # Home page with product listings
├── cart.php               # Shopping cart
├── checkout.php           # Checkout process
├── order_confirmation.php # Order success page
├── contact.php            # Contact form
├── about.php              # About page
├── login.php              # Staff login
├── logout.php             # Logout handler
├── staff_area.php         # Staff dashboard
├── order_management.php   # Order management (staff)
├── stock_management.php   # Stock management (staff)
├── cart_functions.php     # Cart helper functions
├── db.php                 # Database connection
├── style.css              # Main stylesheet
├── database_setup.sql     # Database schema
└── images/                # Product images
```

## Technologies Used

- **Backend**: PHP 8.2
- **Database**: MySQL
- **Frontend**: HTML5, CSS3
- **Server**: Apache (XAMPP)

## License

© 2025 Munster Sport. All rights reserved.
