# Mandala Maven - E-Commerce Platform

A full-featured e-commerce platform with admin dashboard and user interface. Built with PHP, MySQL, and modern web technologies.

## 📋 Project Description

Mandala Maven is an e-commerce application that manages products, orders, and user accounts. It features a dual-interface system with separate admin and user sections, payment gateway integration (eSewa), and comprehensive order management.

## ✨ Features

- **User Features:**
  - User registration and authentication
  - Browse and search products
  - Shopping cart and checkout
  - Order management
  - Payment integration (eSewa)
  - User profile management

- **Admin Features:**
  - Admin panel with dashboard
  - Product management (create, edit, delete)
  - Order management and tracking
  - Order approval workflow
  - Pending and delivered order tracking
  - Admin account management
  - User authentication and security

## 🛠️ Requirements

- **Server:** Apache (XAMPP)
- **PHP:** Version 8.0 or higher
- **Database:** MySQL/MariaDB 10.4+
- **Web Browser:** Modern browser (Chrome, Firefox, Safari, Edge)
- **Local Development:** XAMPP Stack

## 📦 Installation Instructions

### Step 1: Prerequisites
Ensure you have XAMPP installed on your system. Download from [https://www.apachefriends.org/](https://www.apachefriends.org/)

### Step 2: Start XAMPP Services
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** modules
3. Verify both services are running (indicators should be green)

### Step 3: Clone/Copy Project Files
```bash
# Copy the project folder to XAMPP htdocs directory
cp -r Mandala_Maven C:\xampp\htdocs\
# OR manually copy to: C:\xampp\htdocs\Mandala_Maven\
```

### Step 4: Create Database
1. Open your browser and go to: `http://localhost/phpmyadmin/`
2. Click on "Databases" tab
3. Click "New" to create a new database
4. **Database name:** `mandala_maven`
5. **Collation:** Select `utf8mb4_general_ci`
6. Click "Create"

### Step 5: Import Database Schema
1. In phpMyAdmin, select the `mandala_maven` database
2. Click the "Import" tab
3. Click "Choose File" and select `mandala_maven.sql` from the project root
4. Click "Import"

The database will be populated with the required tables:
- `admin` - Admin accounts
- `admin_security` - Admin security settings
- `users` - User accounts
- `products` - Product inventory
- `orders` - Order records
- `cart` - Shopping cart items

### Step 6: Configure Database Connection
The database configuration is already set in [database.php](database.php):

```php
$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "mandala_maven";
```

**If your MySQL has a password**, edit [database.php](database.php):
```php
$db_password = "your_mysql_password";
```

### Step 7: Access the Application

**User Interface:**
```
http://localhost/Mandala_Maven/User/userpage.php
```

**Admin Interface:**
```
http://localhost/Mandala_Maven/admin/admin.php
```

**Registration:**
- User registration: `http://localhost/Mandala_Maven/registerform.php`
- Admin registration: `http://localhost/Mandala_Maven/admin-register.php`

## 📁 Project Structure

```
Mandala_Maven/
├── admin/                          # Admin panel files
│   ├── admin-homepage.php         # Admin dashboard
│   ├── admin.php                  # Admin main page
│   ├── approval.php               # Order approval
│   ├── delivered_orders.php       # Delivered orders view
│   ├── edit.php                   # Admin edit page
│   ├── editProduct.php            # Product editing
│   ├── order.php                  # Order management
│   ├── pending_orders.php         # Pending orders view
│   ├── product.php                # Product management
│   ├── navbar.php                 # Admin navigation
│   ├── logout.php                 # Admin logout
│   ├── admincss/                  # Admin stylesheets
│   └── js/                        # Admin JavaScript
│
├── User/                          # User interface files
│   ├── userpage.php              # User home page
│   ├── productpage.php           # Product listing
│   ├── checkout.php              # Checkout process
│   ├── order.php                 # User orders
│   ├── search.php                # Product search
│   ├── cart.php                  # Shopping cart
│   ├── nav.php                   # User navigation
│   ├── footer.php                # Footer
│   ├── css/                      # User stylesheets
│   └── js/                       # User JavaScript
│
├── css/                          # Global stylesheets
├── img/                          # Global images
├── image_source/                 # Image resources
│
├── database.php                  # Database connection
├── registerform.php              # User registration form
├── signin.php                    # User login page
├── admin-register.php            # Admin registration
├── admin-signin.php              # Admin login page
├── logout.php                    # Logout handler
├── pwdchange.php                 # Password change
├── mandala_maven.sql             # Database schema
└── README.md                     # This file
```

## 🔑 Default Admin Credentials

After database import, use these credentials to login:

- **Username:** `bishal`
- **Password:** `(stored as hashed in database)`

⚠️ **Important:** Change the admin password immediately after first login for security.

## 🚀 Quick Start

1. **Start XAMPP** (Apache & MySQL)
2. **Navigate to:** `http://localhost/Mandala_Maven/User/userpage.php`
3. **Register or Login** with your credentials
4. **Browse Products** and test the platform
5. **Admin Access:** `http://localhost/Mandala_Maven/admin/admin.php`

## 🔐 Security Notes

- All passwords are hashed using bcrypt (`$2y$10$` hash prefix)
- Change default admin credentials after installation
- Update [database.php](database.php) if using a non-default MySQL password
- Enable HTTPS in production
- Validate all user inputs
- Implement proper session management

## 🐛 Troubleshooting

### MySQL Connection Error
- Verify XAMPP MySQL is running
- Check [database.php](database.php) credentials
- Ensure `mandala_maven` database exists

### Page Not Found (404)
- Verify project folder is in `C:\xampp\htdocs\`
- Check file paths in imports
- Clear browser cache (Ctrl+F5)

### Upload/Image Issues
- Ensure `admin/uploaded_images/` and `User/img/` directories have write permissions
- Check file size limits in php.ini

### Payment Gateway (eSewa) Issues
- Verify eSewa API credentials
- Check [User/esewa_success.php](User/esewa_success.php) and [User/esewa_failed.php](User/esewa_failed.php) configurations

## 📝 Usage Guide

### For Users
1. Register an account via [registerform.php](registerform.php)
2. Browse products on user homepage
3. Add items to cart
4. Proceed to checkout
5. Complete payment via eSewa
6. Track orders in user profile

### For Admins
1. Login at [admin/admin.php](admin/admin.php)
2. Manage products via [admin/product.php](admin/product.php)
3. Review pending orders in [admin/pending_orders.php](admin/pending_orders.php)
4. Approve/manage orders in [admin/approval.php](admin/approval.php)
5. Track delivered orders in [admin/delivered_orders.php](admin/delivered_orders.php)

## 📧 Support

For issues or questions, please refer to the specific file documentation or review the inline code comments.

## 📄 License

This project is developed for educational purposes.

---

**Last Updated:** January 16, 2026  
**Version:** 1.0
