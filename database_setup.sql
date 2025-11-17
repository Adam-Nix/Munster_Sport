-- Database Setup for Munster Sport Company Website
-- Run this script in phpMyAdmin or MySQL client

CREATE DATABASE IF NOT EXISTS Db_Munster_Sport;
USE Db_Munster_Sport;

-- Users table (Staff/Sellers only - customers don't need accounts)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('seller') DEFAULT 'seller',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Cart table (uses session_id for guest shopping)
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    size VARCHAR(10),
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX(session_id)
);

-- Orders table (stores customer info directly, no user account needed)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20),
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    seller_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    size VARCHAR(10),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert staff accounts (all use password: Munster123)
INSERT INTO users (username, password, role, email) VALUES 
('adam', 'Munster123', 'seller', 'adam@munstersport.com'),
('brandon', 'Munster123', 'seller', 'brandon@munstersport.com'),
('darren', 'Munster123', 'seller', 'darren@munstersport.com'),
('ernest', 'Munster123', 'seller', 'ernest@munstersport.com');

-- Insert Munster Sport stock items (all owned by adam - user id 1)
INSERT INTO products (seller_id, name, description, price, stock, image) VALUES 
(1, 'ADIDAS Adults Munster Performance T-Shirt', 'Sizes: XS, S, M, L, XL, XXL, XXXL', 35.00, 50, 'Munster_Tshirt.jpg'),
(1, 'ADIDAS Adults Munster Players Training Jersey', 'Sizes: S, M, L, XL', 65.00, 40, 'MunsterJersey.jpg'),
(1, 'ADIDAS Adults Munster Gym Shorts', 'Sizes: L, XL, XXL, XXXL', 30.00, 35, 'MunsterShorts.jpg'),
(1, 'ADIDAS Adults Munster Training Shorts', 'Sizes: L', 30.00, 20, 'MunsterTraining.jpg'),
(1, 'ADIDAS Adults Munster Wind Jacket', 'Sizes: XS, S, M, L, XL, XXL, XXXL', 75.00, 45, 'MunsterJacket.jpg'),
(1, 'ADIDAS Kids Munster Hoodie', 'Sizes: XS, S, M, L', 45.00, 30, 'MunsterKidHoodie.jpg'),
(1, 'GILBERT Munster Replica Rugby Ball', 'Size 5', 25.00, 50, 'ReplicaBall.jpg');

