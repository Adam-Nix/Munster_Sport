# E-Commerce Platform - Quick Reference

## 🚀 Getting Started

### URLs
- **Homepage:** http://localhost/Progect_2025/
- **Test Installation:** http://localhost/Progect_2025/test_install.php
- **phpMyAdmin:** http://localhost/phpmyadmin

### Default Login Credentials

**Customer Account:**
- Username: `customer1`
- Password: `customer123`

**Seller Account:**
- Username: `seller1`
- Password: `seller123`

## 📁 Project Structure

```
Main Files:
├── index.php              → Landing page
├── login.php              → User login
├── register.php           → User registration
├── logout.php             → Logout handler
├── db.php                 → Database connection
├── style.css              → Styling
└── test_install.php       → Installation tester

Customer Features:
├── customer_shop.php      → Browse products
├── cart.php               → View cart
├── add_to_cart.php        → Add item handler
├── remove_from_cart.php   → Remove item handler
├── checkout.php           → Process order
└── confirmation.php       → Order success

Seller Features:
├── seller_dashboard.php   → Dashboard with stats
├── add_product.php        → Add new product
└── delete_product.php     → Delete product

Shared:
└── view_orders.php        → Order history (both roles)

Documentation:
├── README.md              → Full documentation
├── SETUP_GUIDE.md         → Setup instructions
└── database_setup.sql     → Database schema
```

## 🗄️ Database

**Database Name:** `Db_Munster_Sport`

**Tables:**
- `users` - User accounts (customers & sellers)
- `products` - Product listings
- `cart` - Shopping cart items
- `orders` - Order records
- `order_items` - Order line items

## 🔐 Security Features

✅ Password hashing (bcrypt)
✅ Prepared statements (SQL injection prevention)
✅ Session-based authentication
✅ Role-based access control
✅ Input sanitization
✅ XSS protection

## 🛠️ Common Tasks

### Add a Test Product (as Seller)
1. Login as seller
2. Click "Add Product"
3. Fill in details
4. Submit

### Make a Test Purchase (as Customer)
1. Login as customer
2. Click "Shop"
3. Add products to cart
4. Go to cart
5. Click checkout
6. View confirmation

### Check Orders
- **Customer:** View order history in "My Orders"
- **Seller:** View sales in "View Orders"

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| Can't connect to database | Start MySQL in XAMPP |
| Login fails | Check if database_setup.sql was imported |
| Products not showing | Add products as seller first, ensure stock > 0 |
| Page blank | Check Apache error logs, enable PHP errors |
| Port 80 in use | Change Apache port in XAMPP config |

## 📊 Features Checklist

### Customer Features
- [x] User registration
- [x] User login/logout
- [x] Browse products
- [x] Add to cart
- [x] View cart
- [x] Remove from cart
- [x] Checkout
- [x] Order confirmation
- [x] Order history

### Seller Features
- [x] Seller registration
- [x] Seller login/logout
- [x] Dashboard with statistics
- [x] Add products
- [x] View products
- [x] Delete products
- [x] View sales
- [x] Track customer orders

## 💻 Technology Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3
- **Server:** Apache (XAMPP)

## 🎨 UI Features

- Responsive design
- Modern gradient styling
- Alert messages (success/error)
- Card-based product layout
- Clean navigation
- Mobile-friendly

## 📝 Code Quality

- Input sanitization
- Error handling
- Transaction support
- Prepared statements
- Password hashing
- Session management

## 🔄 Workflow

### Customer Workflow
```
Register → Login → Browse → Add to Cart → Checkout → Confirmation → View Orders
```

### Seller Workflow
```
Register → Login → Add Products → View Dashboard → Track Sales → Manage Products
```

## 📞 Quick Help

**Reset Everything:**
1. Drop database in phpMyAdmin
2. Re-import database_setup.sql
3. Refresh application

**Clear Session:**
- Click logout
- Clear browser cookies
- Close and reopen browser

**Check Installation:**
- Visit: http://localhost/Progect_2025/test_install.php

## 🎯 Next Steps

1. ✅ Complete setup following SETUP_GUIDE.md
2. ✅ Run test_install.php to verify
3. ✅ Create test accounts
4. ✅ Add sample products
5. ✅ Test complete purchase flow
6. ✅ Explore both customer and seller views

## 📚 Learn More

- Check README.md for detailed documentation
- Review SETUP_GUIDE.md for troubleshooting
- Inspect PHP files for code examples
- Check database_setup.sql for schema details
