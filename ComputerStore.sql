CREATE DATABASE IF NOT EXISTS nvkcomputer
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE nvkcomputer;

-- USERS
CREATE TABLE users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    fullName VARCHAR(100),
    phone VARCHAR(20),
    role ENUM('customer','admin') DEFAULT 'customer',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- SHIPPING ADDRESS
CREATE TABLE shippingAddresses (
    addressId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    recipientName VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    addressLine VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    district VARCHAR(100),
    ward VARCHAR(100),
    isDefault BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
);

-- PRODUCT CATEGORY
CREATE TABLE productCategories (
    categoryId INT AUTO_INCREMENT PRIMARY KEY,
    categoryName VARCHAR(100) NOT NULL
);

-- BRAND
CREATE TABLE brands (
    brandId INT AUTO_INCREMENT PRIMARY KEY,
    brandName VARCHAR(100) NOT NULL
);

-- PRODUCTS
CREATE TABLE products (
    productId INT AUTO_INCREMENT PRIMARY KEY,
    productName VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    productDescription TEXT,
    categoryId INT,
    brandId INT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoryId) REFERENCES productCategories(categoryId),
    FOREIGN KEY (brandId) REFERENCES brands(brandId)
);

-- PRODUCT VARIANTS (RAM/ROM/Color + Stock)
CREATE TABLE productVariants (
    variantId INT AUTO_INCREMENT PRIMARY KEY,
    productId INT,
    ram VARCHAR(50),
    rom VARCHAR(50),
    color VARCHAR(50),
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE CASCADE
);

-- ORDERS
CREATE TABLE orders (
    orderId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    status ENUM('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending',
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    paymentMethod VARCHAR(50),
    shippingAddressId INT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (shippingAddressId) REFERENCES shippingAddresses(addressId)
);

-- ORDER ITEMS
CREATE TABLE orderItems (
    orderItemId INT AUTO_INCREMENT PRIMARY KEY,
    orderId INT NOT NULL,
    variantId INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (orderId) REFERENCES orders(orderId) ON DELETE CASCADE,
    FOREIGN KEY (variantId) REFERENCES productVariants(variantId)
);

-- CART
CREATE TABLE cart (
    cartId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    variantId INT NOT NULL,
    quantity INT NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (variantId) REFERENCES productVariants(variantId) ON DELETE CASCADE,
    UNIQUE(userId, variantId)
);

-- REVIEWS
CREATE TABLE reviews (
    reviewId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    productId INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE CASCADE
);

-- VNPay Transactions
CREATE TABLE vnPayTransactions (
    vnPayId INT AUTO_INCREMENT PRIMARY KEY,
    orderId INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50),
    transactionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orderId) REFERENCES orders(orderId) ON DELETE CASCADE
);

-- Payment Logs
CREATE TABLE paymentLogs (
    paymentId INT AUTO_INCREMENT PRIMARY KEY,
    orderId INT NOT NULL,
    method VARCHAR(50),
    status VARCHAR(50),
    amount DECIMAL(10,2),
    transactionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orderId) REFERENCES orders(orderId) ON DELETE CASCADE
);

-- Wishlist
CREATE TABLE wishlists (
    wishlistId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    productId INT NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE CASCADE
);
