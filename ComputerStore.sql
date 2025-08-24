CREATE DATABASE IF NOT EXISTS ComputerStore
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;
USE ComputerStore;

-- BẢNG NGƯỜI DÙNG
CREATE TABLE Users (
    UserId INT AUTO_INCREMENT PRIMARY KEY,            -- Mã người dùng
    Username VARCHAR(50) UNIQUE NOT NULL,             -- Tên đăng nhập
    Password VARCHAR(255) NOT NULL,                   -- Mật khẩu
    Email VARCHAR(100) UNIQUE NOT NULL,               -- Email
    FullName VARCHAR(100),                            -- Họ và tên
    Phone VARCHAR(20),                                -- Số điện thoại
    Role ENUM('customer','admin') DEFAULT 'customer', -- Vai trò
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP     -- Thời gian tạo
);

-- BẢNG ĐỊA CHỈ GIAO HÀNG
CREATE TABLE ShippingAddresses (
    AddressId INT AUTO_INCREMENT PRIMARY KEY,         -- Mã địa chỉ
    UserId INT NOT NULL,                              -- Người dùng sở hữu địa chỉ
    RecipientName VARCHAR(100) NOT NULL,              -- Tên người nhận
    Phone VARCHAR(20) NOT NULL,                       -- Số điện thoại nhận hàng
    AddressLine VARCHAR(255) NOT NULL,                -- Địa chỉ chi tiết
    City VARCHAR(100) NOT NULL,                       -- Thành phố
    District VARCHAR(100),                            -- Quận/Huyện
    Ward VARCHAR(100),                                -- Phường/Xã
    IsDefault BOOLEAN DEFAULT FALSE,                  -- Có phải địa chỉ mặc định không
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE
);

-- BẢNG DANH MỤC SẢN PHẨM
CREATE TABLE Categories (
    CategoryId INT AUTO_INCREMENT PRIMARY KEY,        -- Mã danh mục
    CategoryName VARCHAR(100) NOT NULL                -- Tên danh mục
);

-- BẢNG THƯƠNG HIỆU
CREATE TABLE Brands (
    BrandId INT AUTO_INCREMENT PRIMARY KEY,           -- Mã thương hiệu
    BrandName VARCHAR(100) NOT NULL                   -- Tên thương hiệu
);

-- BẢNG SẢN PHẨM
CREATE TABLE Products (
    ProductId INT AUTO_INCREMENT PRIMARY KEY,         -- Mã sản phẩm
    Name VARCHAR(255) NOT NULL,                       -- Tên sản phẩm
    Description TEXT,                                 -- Mô tả
    CategoryId INT,                                   -- Danh mục
    BrandId INT,                                      -- Thương hiệu
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,    -- Ngày thêm
    FOREIGN KEY (CategoryId) REFERENCES Categories(CategoryId),
    FOREIGN KEY (BrandId) REFERENCES Brands(BrandId)
);

-- BẢNG BIẾN THỂ SẢN PHẨM
CREATE TABLE ProductVariants (
    VariantId INT AUTO_INCREMENT PRIMARY KEY,         -- Mã biến thể
    ProductId INT,                                    -- Thuộc sản phẩm
    Ram VARCHAR(50),                                  -- RAM
    Rom VARCHAR(50),                                  -- ROM
    Color VARCHAR(50),                                -- Màu sắc
    Price DECIMAL(10,2) NOT NULL,                     -- Giá
    Stock INT DEFAULT 0,                              -- Tồn kho
    Image VARCHAR(255),                               -- Hình ảnh
    FOREIGN KEY (ProductId) REFERENCES Products(ProductId) ON DELETE CASCADE
);

-- BẢNG GIỎ HÀNG
CREATE TABLE Cart (
    CartId INT AUTO_INCREMENT PRIMARY KEY,            -- Mã giỏ
    UserId INT NOT NULL,                              -- Người dùng
    VariantId INT NOT NULL,                           -- Biến thể sản phẩm
    Quantity INT NOT NULL,                            -- Số lượng
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,    -- Ngày tạo
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (VariantId) REFERENCES ProductVariants(VariantId) ON DELETE CASCADE
);

-- BẢNG ĐƠN HÀNG
CREATE TABLE Orders (
    OrderId INT AUTO_INCREMENT PRIMARY KEY,           -- Mã đơn hàng
    UserId INT NOT NULL,                              -- Người đặt
    PromotionId INT,                                  -- Khuyến mãi áp dụng
    Status ENUM('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending', -- Trạng thái
    Total DECIMAL(10,2) NOT NULL,                     -- Tổng tiền
    PaymentMethod VARCHAR(50),                        -- Phương thức thanh toán
    ShippingAddressId INT,                            -- Địa chỉ giao hàng
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,    -- Ngày tạo
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (PromotionId) REFERENCES Promotions(PromotionId),
    FOREIGN KEY (ShippingAddressId) REFERENCES ShippingAddresses(AddressId)
);

-- BẢNG CHI TIẾT ĐƠN HÀNG
CREATE TABLE OrderItems (
    OrderItemId INT AUTO_INCREMENT PRIMARY KEY,       -- Mã chi tiết
    OrderId INT NOT NULL,                             -- Đơn hàng
    VariantId INT NOT NULL,                           -- Biến thể
    Quantity INT NOT NULL,                            -- Số lượng
    Price DECIMAL(10,2) NOT NULL,                     -- Giá tại thời điểm mua
    FOREIGN KEY (OrderId) REFERENCES Orders(OrderId) ON DELETE CASCADE,
    FOREIGN KEY (VariantId) REFERENCES ProductVariants(VariantId)
);

-- BẢNG ĐÁNH GIÁ SẢN PHẨM
CREATE TABLE Reviews (
    ReviewId INT AUTO_INCREMENT PRIMARY KEY,          -- Mã đánh giá
    UserId INT NOT NULL,                              -- Người dùng
    ProductId INT NOT NULL,                           -- Sản phẩm
    Rating INT CHECK (Rating BETWEEN 1 AND 5),        -- Điểm (1-5)
    Comment TEXT,                                     -- Nhận xét
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,    -- Ngày tạo
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Products(ProductId) ON DELETE CASCADE
);

-- BẢNG ĐIỂM THƯỞNG
CREATE TABLE RewardPoints (
    RewardId INT AUTO_INCREMENT PRIMARY KEY,          -- Mã điểm thưởng
    UserId INT NOT NULL,                              -- Người dùng
    Points INT NOT NULL,                              -- Số điểm hiện tại
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE
);

-- BẢNG LỊCH SỬ ĐIỂM
CREATE TABLE PointTransactions (
    TransactionId INT AUTO_INCREMENT PRIMARY KEY,     -- Mã giao dịch điểm
    UserId INT NOT NULL,                              -- Người dùng
    Points INT NOT NULL,                              -- Số điểm
    Type ENUM('Earned','Used') NOT NULL,              -- Loại (Cộng/Trừ)
    Description VARCHAR(255),                         -- Mô tả
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,    -- Ngày tạo
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE
);

-- BẢNG KHUYẾN MÃI
CREATE TABLE Promotions (
    PromotionId INT AUTO_INCREMENT PRIMARY KEY,       -- Mã khuyến mãi
    Code VARCHAR(50) UNIQUE NOT NULL,                 -- Mã code
    Description VARCHAR(255),                         -- Mô tả
    DiscountPercent DECIMAL(5,2),                     -- Giảm theo %
    DiscountAmount DECIMAL(10,2),                     -- Giảm theo số tiền
    MinOrderValue DECIMAL(10,2) DEFAULT 0,            -- Giá trị tối thiểu
    StartDate DATE NOT NULL,                          -- Bắt đầu
    EndDate DATE NOT NULL,                            -- Kết thúc
    UsageLimit INT DEFAULT 0,                         -- Giới hạn
    UsedCount INT DEFAULT 0                           -- Đã sử dụng
);

-- BẢNG LỊCH SỬ SỬ DỤNG KHUYẾN MÃI
CREATE TABLE PromotionUses (
    UseId INT AUTO_INCREMENT PRIMARY KEY,             -- Mã log
    PromotionId INT NOT NULL,                         -- Mã KM
    UserId INT NOT NULL,                              -- Người dùng
    OrderId INT NOT NULL,                             -- Đơn hàng
    UsedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,       -- Ngày dùng
    FOREIGN KEY (PromotionId) REFERENCES Promotions(PromotionId),
    FOREIGN KEY (UserId) REFERENCES Users(UserId),
    FOREIGN KEY (OrderId) REFERENCES Orders(OrderId)
);

-- BẢNG THANH TOÁN (VNPay + log chung)
CREATE TABLE VNPayTransactions (
    VNPayId INT AUTO_INCREMENT PRIMARY KEY,           -- Mã VNPay
    OrderId INT NOT NULL,                             -- Đơn hàng
    Amount DECIMAL(10,2) NOT NULL,                    -- Số tiền
    Status VARCHAR(50),                               -- Trạng thái
    TransactionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày giao dịch
    FOREIGN KEY (OrderId) REFERENCES Orders(OrderId) ON DELETE CASCADE
);

CREATE TABLE PaymentLogs (
    PaymentId INT AUTO_INCREMENT PRIMARY KEY,         -- Mã thanh toán
    OrderId INT NOT NULL,                             -- Đơn hàng
    Method VARCHAR(50),                               -- Phương thức
    Status VARCHAR(50),                               -- Trạng thái
    Amount DECIMAL(10,2),                             -- Số tiền
    TransactionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày giao dịch
    FOREIGN KEY (OrderId) REFERENCES Orders(OrderId) ON DELETE CASCADE
);

-- BẢNG VOUCHERS
CREATE TABLE Vouchers (
    VoucherId INT AUTO_INCREMENT PRIMARY KEY,         -- Mã voucher
    Code VARCHAR(50) UNIQUE NOT NULL,                 -- Mã code
    Description VARCHAR(255),                         -- Mô tả
    DiscountPercent DECIMAL(5,2),                     -- Giảm %
    DiscountAmount DECIMAL(10,2),                     -- Giảm tiền
    MinOrderValue DECIMAL(10,2) DEFAULT 0,            -- Giá trị tối thiểu
    MaxDiscount DECIMAL(10,2),                        -- Giảm tối đa
    StartDate DATE NOT NULL,                          -- Bắt đầu
    EndDate DATE NOT NULL,                            -- Kết thúc
    UsageLimit INT DEFAULT 0,                         -- Giới hạn
    UsedCount INT DEFAULT 0                           -- Đã dùng
);

-- BẢNG WISHLIST
CREATE TABLE Wishlists (
    WishlistId INT AUTO_INCREMENT PRIMARY KEY,        -- Mã wishlist
    UserId INT NOT NULL,                              -- Người dùng
    ProductId INT NOT NULL,                           -- Sản phẩm
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,    -- Ngày thêm
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Products(ProductId) ON DELETE CASCADE
);

-- BẢNG HỖ TRỢ / BẢO HÀNH
CREATE TABLE SupportTickets (
    TicketId INT AUTO_INCREMENT PRIMARY KEY,          -- Mã phiếu
    UserId INT NOT NULL,                              -- Người gửi
    OrderId INT,                                      -- Đơn hàng (nếu có)
    Subject VARCHAR(255) NOT NULL,                    -- Chủ đề
    Message TEXT NOT NULL,                            -- Nội dung
    Status ENUM('Open','In Progress','Resolved','Closed') DEFAULT 'Open', -- Trạng thái
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (OrderId) REFERENCES Orders(OrderId) ON DELETE SET NULL
);

-- BẢNG LỊCH SỬ KHO
CREATE TABLE InventoryLogs (
    LogId INT AUTO_INCREMENT PRIMARY KEY,             -- Mã log
    ProductVariantId INT NOT NULL,                    -- Biến thể
    ChangeType ENUM('Import','Export') NOT NULL,      -- Nhập/Xuất
    Quantity INT NOT NULL,                            -- Số lượng
    Note VARCHAR(255),                                -- Ghi chú
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ProductVariantId) REFERENCES ProductVariants(VariantId) ON DELETE CASCADE
);
