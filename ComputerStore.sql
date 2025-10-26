-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS ComputerStore
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE ComputerStore;

-- Bảng người dùng (khách hàng và admin)
CREATE TABLE users (
    userId INT AUTO_INCREMENT PRIMARY KEY, -- Mã người dùng
    username VARCHAR(50) UNIQUE NOT NULL,  -- Tên đăng nhập
    password VARCHAR(255) NOT NULL,        -- Mật khẩu (hash)
    email VARCHAR(100) UNIQUE NOT NULL,    -- Email
    fullName VARCHAR(100),                 -- Họ tên đầy đủ
    phone VARCHAR(20),                     -- Số điện thoại
    role ENUM('customer','admin') DEFAULT 'customer', -- Vai trò
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Ngày tạo tài khoản
);

-- Bảng địa chỉ giao hàng
CREATE TABLE shippingAddresses (
    addressId INT AUTO_INCREMENT PRIMARY KEY, -- Mã địa chỉ
    userId INT NOT NULL,                      -- Người dùng sở hữu
    recipientName VARCHAR(100) NOT NULL,      -- Tên người nhận
    phone VARCHAR(20) NOT NULL,               -- Số điện thoại người nhận
    addressLine VARCHAR(255) NOT NULL,        -- Địa chỉ chi tiết
    city VARCHAR(100) NOT NULL,               -- Thành phố
    district VARCHAR(100),                    -- Quận / Huyện
    ward VARCHAR(100),                        -- Phường / Xã
    isDefault BOOLEAN DEFAULT FALSE,          -- Có phải địa chỉ mặc định?
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
);

-- Bảng danh mục sản phẩm
CREATE TABLE productCategories (
    categoryId INT AUTO_INCREMENT PRIMARY KEY, -- Mã danh mục
    categoryName VARCHAR(100) NOT NULL         -- Tên danh mục
);

-- Bảng thương hiệu sản phẩm
CREATE TABLE brands (
    brandId INT AUTO_INCREMENT PRIMARY KEY, -- Mã thương hiệu
    brandName VARCHAR(100) NOT NULL         -- Tên thương hiệu
);

-- Bảng sản phẩm
CREATE TABLE products (
    productId INT AUTO_INCREMENT PRIMARY KEY, -- Mã sản phẩm
    productName VARCHAR(255) NOT NULL,        -- Tên sản phẩm
    image VARCHAR(255),                       -- Ảnh chung của sản phẩm
    productDescription TEXT,                  -- Mô tả sản phẩm
    categoryId INT,                           -- Danh mục
    brandId INT,                              -- Thương hiệu
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày tạo
    FOREIGN KEY (categoryId) REFERENCES productCategories(categoryId),
    FOREIGN KEY (brandId) REFERENCES brands(brandId)
);

-- Bảng biến thể sản phẩm (RAM/ROM/Màu)
CREATE TABLE productVariants (
    variantId INT AUTO_INCREMENT PRIMARY KEY, -- Mã biến thể
    productId INT,                            -- Thuộc sản phẩm
    ram VARCHAR(50),                          -- Dung lượng RAM
    rom VARCHAR(50),                          -- Dung lượng ROM
    color VARCHAR(50),                        -- Màu sắc
    price DECIMAL(10,2) NOT NULL,             -- Giá
    stock INT DEFAULT 0,                      -- Tồn kho
    image VARCHAR(255),                       -- Ảnh sản phẩm
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE CASCADE
);

-- Bảng khuyến mãi chung
CREATE TABLE promotions (
    promotionId INT AUTO_INCREMENT PRIMARY KEY, -- Mã khuyến mãi
    code VARCHAR(50) UNIQUE NOT NULL,           -- Mã code
    description VARCHAR(255),                   -- Mô tả
    discountPercent DECIMAL(5,2),               -- Giảm theo %
    discountAmount DECIMAL(10,2),               -- Giảm theo số tiền
    minOrderValue DECIMAL(10,2) DEFAULT 0,      -- Đơn tối thiểu
    startDate DATE NOT NULL,                    -- Ngày bắt đầu
    endDate DATE NOT NULL,                      -- Ngày kết thúc
    usageLimit INT DEFAULT 0,                   -- Giới hạn số lần
    usedCount INT DEFAULT 0                     -- Đã sử dụng bao nhiêu
);

-- Bảng voucher (phiếu giảm giá phát cho user)
CREATE TABLE vouchers (
    voucherId INT AUTO_INCREMENT PRIMARY KEY, -- Mã voucher
    code VARCHAR(50) UNIQUE NOT NULL,         -- Mã code
    description VARCHAR(255),                 -- Mô tả
    discountPercent DECIMAL(5,2),             -- Giảm theo %
    discountAmount DECIMAL(10,2),             -- Giảm theo số tiền
    minOrderValue DECIMAL(10,2) DEFAULT 0,    -- Đơn tối thiểu
    maxDiscount DECIMAL(10,2),                -- Giảm tối đa
    startDate DATE NOT NULL,                  -- Ngày bắt đầu
    endDate DATE NOT NULL,                    -- Ngày kết thúc
    usageLimit INT DEFAULT 0,                 -- Giới hạn số lần
    usedCount INT DEFAULT 0                   -- Đã sử dụng
);

-- Bảng quản lý voucher phát cho từng user
CREATE TABLE userVouchers (
    userVoucherId INT AUTO_INCREMENT PRIMARY KEY, -- Mã user-voucher
    userId INT NOT NULL,                          -- Người dùng
    voucherId INT NOT NULL,                       -- Voucher được phát
    isUsed BOOLEAN DEFAULT FALSE,                 -- Đã dùng hay chưa
    dateUsed TIMESTAMP NULL,                      -- Ngày dùng
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (voucherId) REFERENCES vouchers(voucherId) ON DELETE CASCADE
);

-- Bảng đơn hàng
CREATE TABLE orders (
    orderId INT AUTO_INCREMENT PRIMARY KEY, -- Mã đơn hàng
    userId INT NOT NULL,                    -- Người đặt
    promotionId INT,                        -- Khuyến mãi áp dụng
    userVoucherId INT,                      -- Voucher áp dụng
    status ENUM('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending', -- Trạng thái
    total DECIMAL(10,2) NOT NULL DEFAULT 0, -- Tổng tiền sau giảm giá
    paymentMethod VARCHAR(50),              -- Phương thức thanh toán
    shippingAddressId INT,                  -- Địa chỉ giao hàng
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày đặt hàng
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (promotionId) REFERENCES promotions(promotionId),
    FOREIGN KEY (userVoucherId) REFERENCES userVouchers(userVoucherId) ON DELETE SET NULL,
    FOREIGN KEY (shippingAddressId) REFERENCES shippingAddresses(addressId)
);

-- Bảng chi tiết đơn hàng
CREATE TABLE orderItems (
    orderItemId INT AUTO_INCREMENT PRIMARY KEY, -- Mã chi tiết đơn
    orderId INT NOT NULL,                       -- Đơn hàng
    variantId INT NOT NULL,                     -- Biến thể sản phẩm
    quantity INT NOT NULL,                      -- Số lượng
    price DECIMAL(10,2) NOT NULL,               -- Giá tại thời điểm mua
    FOREIGN KEY (orderId) REFERENCES orders(orderId) ON DELETE CASCADE,
    FOREIGN KEY (variantId) REFERENCES productVariants(variantId)
);

-- Bảng giỏ hàng
CREATE TABLE cart (
    cartId INT AUTO_INCREMENT PRIMARY KEY,      -- Mã giỏ hàng
    userId INT NOT NULL,                        -- Người dùng
    variantId INT NOT NULL,                     -- Biến thể sản phẩm
    quantity INT NOT NULL,                      -- Số lượng
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày thêm
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (variantId) REFERENCES productVariants(variantId) ON DELETE CASCADE,
    UNIQUE(userId, variantId)                   -- Một biến thể chỉ 1 dòng trong giỏ
);

-- Bảng đánh giá sản phẩm
CREATE TABLE reviews (
    reviewId INT AUTO_INCREMENT PRIMARY KEY,   -- Mã đánh giá
    userId INT NOT NULL,                       -- Người đánh giá
    productId INT NOT NULL,                    -- Sản phẩm
    rating INT CHECK (rating BETWEEN 1 AND 5), -- Số sao
    comment TEXT,                              -- Nội dung
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày đánh giá
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE CASCADE
);

-- Bảng điểm thưởng (số dư hiện tại)
CREATE TABLE rewardPoints (
    rewardId INT AUTO_INCREMENT PRIMARY KEY, -- Mã điểm
    userId INT NOT NULL,                     -- Người dùng
    points INT NOT NULL,                     -- Số điểm
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
);

-- Bảng lịch sử giao dịch điểm
CREATE TABLE pointTransactions (
    transactionId INT AUTO_INCREMENT PRIMARY KEY, -- Mã giao dịch
    userId INT NOT NULL,                          -- Người dùng
    points INT NOT NULL,                          -- Số điểm thay đổi
    type ENUM('Earned','Used') NOT NULL,          -- Loại: nhận / dùng
    description VARCHAR(255),                     -- Ghi chú
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
);

-- Bảng log sử dụng khuyến mãi
CREATE TABLE promotionUses (
    useId INT AUTO_INCREMENT PRIMARY KEY, -- Mã log
    promotionId INT NOT NULL,             -- Khuyến mãi
    userId INT NOT NULL,                  -- Người dùng
    orderId INT NOT NULL,                 -- Đơn hàng
    usedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày dùng
    FOREIGN KEY (promotionId) REFERENCES promotions(promotionId),
    FOREIGN KEY (userId) REFERENCES users(userId),
    FOREIGN KEY (orderId) REFERENCES orders(orderId)
);

-- Bảng giao dịch VNPay
CREATE TABLE vnPayTransactions (
    vnPayId INT AUTO_INCREMENT PRIMARY KEY, -- Mã giao dịch VNPay
    orderId INT NOT NULL,                   -- Đơn hàng
    amount DECIMAL(10,2) NOT NULL,          -- Số tiền
    status VARCHAR(50),                     -- Trạng thái
    transactionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày giao dịch
    FOREIGN KEY (orderId) REFERENCES orders(orderId) ON DELETE CASCADE
);

-- Bảng log thanh toán
CREATE TABLE paymentLogs (
    paymentId INT AUTO_INCREMENT PRIMARY KEY, -- Mã log
    orderId INT NOT NULL,                     -- Đơn hàng
    method VARCHAR(50),                       -- Phương thức
    status VARCHAR(50),                       -- Trạng thái
    amount DECIMAL(10,2),                     -- Số tiền
    transactionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày giao dịch
    FOREIGN KEY (orderId) REFERENCES orders(orderId) ON DELETE CASCADE
);

-- Bảng danh sách yêu thích
CREATE TABLE wishlists (
    wishlistId INT AUTO_INCREMENT PRIMARY KEY, -- Mã wishlist
    userId INT NOT NULL,                       -- Người dùng
    productId INT NOT NULL,                    -- Sản phẩm
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày thêm
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE CASCADE
);

-- Bảng yêu cầu hỗ trợ / bảo hành
CREATE TABLE supportTickets (
    ticketId INT AUTO_INCREMENT PRIMARY KEY, -- Mã ticket
    userId INT NOT NULL,                     -- Người gửi
    orderId INT NULL,                        -- Đơn liên quan
    subject VARCHAR(255) NOT NULL,           -- Chủ đề
    message TEXT NOT NULL,                   -- Nội dung
    status ENUM('Open','In Progress','Resolved','Closed') DEFAULT 'Open', -- Trạng thái
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày tạo
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (orderId) REFERENCES orders(orderId) ON DELETE SET NULL
);

-- Bảng log tồn kho
CREATE TABLE inventoryLogs (
    logId INT AUTO_INCREMENT PRIMARY KEY, -- Mã log
    productVariantId INT NOT NULL,        -- Biến thể
    changeType ENUM('Import','Export') NOT NULL, -- Nhập / Xuất
    quantity INT NOT NULL,                -- Số lượng thay đổi
    note VARCHAR(255),                    -- Ghi chú
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày ghi log
    FOREIGN KEY (productVariantId) REFERENCES productVariants(variantId) ON DELETE CASCADE
);
