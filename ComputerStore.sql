-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS ComputerStore
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE ComputerStore;

-- Bảng người dùng (khách hàng và admin)
CREATE TABLE Users (
    UserId INT AUTO_INCREMENT PRIMARY KEY, -- Mã người dùng
    Username VARCHAR(50) UNIQUE NOT NULL,  -- Tên đăng nhập
    Password VARCHAR(255) NOT NULL,        -- Mật khẩu (hash)
    Email VARCHAR(100) UNIQUE NOT NULL,    -- Email
    FullName VARCHAR(100),                 -- Họ tên đầy đủ
    Phone VARCHAR(20),                     -- Số điện thoại
    Role ENUM('customer','admin') DEFAULT 'customer', -- Vai trò
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Ngày tạo tài khoản
);

-- Bảng địa chỉ giao hàng
CREATE TABLE ShippingAddresses (
    AddressId INT AUTO_INCREMENT PRIMARY KEY, -- Mã địa chỉ
    UserId INT NOT NULL,                      -- Người dùng sở hữu
    RecipientName VARCHAR(100) NOT NULL,      -- Tên người nhận
    Phone VARCHAR(20) NOT NULL,               -- Số điện thoại người nhận
    AddressLine VARCHAR(255) NOT NULL,        -- Địa chỉ chi tiết
    City VARCHAR(100) NOT NULL,               -- Thành phố
    District VARCHAR(100),                    -- Quận / Huyện
    Ward VARCHAR(100),                        -- Phường / Xã
    IsDefault BOOLEAN DEFAULT FALSE,          -- Có phải địa chỉ mặc định?
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE
);

-- Bảng danh mục sản phẩm
CREATE TABLE Categories (
    CategoryId INT AUTO_INCREMENT PRIMARY KEY, -- Mã danh mục
    CategoryName VARCHAR(100) NOT NULL         -- Tên danh mục
);

-- Bảng thương hiệu sản phẩm
CREATE TABLE Brands (
    BrandId INT AUTO_INCREMENT PRIMARY KEY, -- Mã thương hiệu
    BrandName VARCHAR(100) NOT NULL         -- Tên thương hiệu
);

-- Bảng sản phẩm
CREATE TABLE Products (
    ProductId INT AUTO_INCREMENT PRIMARY KEY, -- Mã sản phẩm
    Name VARCHAR(255) NOT NULL,               -- Tên sản phẩm
    Description TEXT,                         -- Mô tả sản phẩm
    CategoryId INT,                           -- Danh mục
    BrandId INT,                              -- Thương hiệu
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày tạo
    FOREIGN KEY (CategoryId) REFERENCES Categories(CategoryId),
    FOREIGN KEY (BrandId) REFERENCES Brands(BrandId)
);

-- Bảng biến thể sản phẩm (RAM/ROM/Màu)
CREATE TABLE ProductVariants (
    VariantId INT AUTO_INCREMENT PRIMARY KEY, -- Mã biến thể
    ProductId INT,                            -- Thuộc sản phẩm
    Ram VARCHAR(50),                          -- Dung lượng RAM
    Rom VARCHAR(50),                          -- Dung lượng ROM
    Color VARCHAR(50),                        -- Màu sắc
    Price DECIMAL(10,2) NOT NULL,             -- Giá
    Stock INT DEFAULT 0,                      -- Tồn kho
    Image VARCHAR(255),                       -- Ảnh sản phẩm
    FOREIGN KEY (ProductId) REFERENCES Products(ProductId) ON DELETE CASCADE
);

-- Bảng khuyến mãi chung
CREATE TABLE Promotions (
    PromotionId INT AUTO_INCREMENT PRIMARY KEY, -- Mã khuyến mãi
    Code VARCHAR(50) UNIQUE NOT NULL,           -- Mã code
    Description VARCHAR(255),                   -- Mô tả
    DiscountPercent DECIMAL(5,2),               -- Giảm theo %
    DiscountAmount DECIMAL(10,2),               -- Giảm theo số tiền
    MinOrderValue DECIMAL(10,2) DEFAULT 0,      -- Đơn tối thiểu
    StartDate DATE NOT NULL,                    -- Ngày bắt đầu
    EndDate DATE NOT NULL,                      -- Ngày kết thúc
    UsageLimit INT DEFAULT 0,                   -- Giới hạn số lần
    UsedCount INT DEFAULT 0                     -- Đã sử dụng bao nhiêu
);

-- Bảng voucher (phiếu giảm giá phát cho user)
CREATE TABLE Vouchers (
    VoucherId INT AUTO_INCREMENT PRIMARY KEY, -- Mã voucher
    Code VARCHAR(50) UNIQUE NOT NULL,         -- Mã code
    Description VARCHAR(255),                 -- Mô tả
    DiscountPercent DECIMAL(5,2),             -- Giảm theo %
    DiscountAmount DECIMAL(10,2),             -- Giảm theo số tiền
    MinOrderValue DECIMAL(10,2) DEFAULT 0,    -- Đơn tối thiểu
    MaxDiscount DECIMAL(10,2),                -- Giảm tối đa
    StartDate DATE NOT NULL,                  -- Ngày bắt đầu
    EndDate DATE NOT NULL,                    -- Ngày kết thúc
    UsageLimit INT DEFAULT 0,                  -- Giới hạn số lần
    UsedCount INT DEFAULT 0                   -- Đã sử dụng
);

-- Bảng quản lý voucher phát cho từng user
CREATE TABLE UserVouchers (
    UserVoucherId INT AUTO_INCREMENT PRIMARY KEY, -- Mã user-voucher
    UserId INT NOT NULL,                          -- Người dùng
    VoucherId INT NOT NULL,                       -- Voucher được phát
    IsUsed BOOLEAN DEFAULT FALSE,                 -- Đã dùng hay chưa
    DateUsed TIMESTAMP NULL,                      -- Ngày dùng
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (VoucherId) REFERENCES Vouchers(VoucherId) ON DELETE CASCADE
);

-- Bảng đơn hàng
CREATE TABLE Orders (
    OrderId INT AUTO_INCREMENT PRIMARY KEY, -- Mã đơn hàng
    UserId INT NOT NULL,                    -- Người đặt
    PromotionId INT,                        -- Khuyến mãi áp dụng
    UserVoucherId INT,                      -- Voucher áp dụng
    Status ENUM('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending', -- Trạng thái
    Total DECIMAL(10,2) NOT NULL DEFAULT 0, -- Tổng tiền sau giảm giá
    PaymentMethod VARCHAR(50),              -- Phương thức thanh toán
    ShippingAddressId INT,                  -- Địa chỉ giao hàng
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày đặt hàng
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (PromotionId) REFERENCES Promotions(PromotionId),
    FOREIGN KEY (UserVoucherId) REFERENCES UserVouchers(UserVoucherId) ON DELETE SET NULL,
    FOREIGN KEY (ShippingAddressId) REFERENCES ShippingAddresses(AddressId)
);

-- Bảng chi tiết đơn hàng
CREATE TABLE OrderItems (
    OrderItemId INT AUTO_INCREMENT PRIMARY KEY, -- Mã chi tiết đơn
    OrderId INT NOT NULL,                       -- Đơn hàng
    VariantId INT NOT NULL,                     -- Biến thể sản phẩm
    Quantity INT NOT NULL,                      -- Số lượng
    Price DECIMAL(10,2) NOT NULL,               -- Giá tại thời điểm mua
    FOREIGN KEY (OrderId) REFERENCES Orders(OrderId) ON DELETE CASCADE,
    FOREIGN KEY (VariantId) REFERENCES ProductVariants(VariantId)
);

-- Bảng giỏ hàng
CREATE TABLE Cart (
    CartId INT AUTO_INCREMENT PRIMARY KEY,      -- Mã giỏ hàng
    UserId INT NOT NULL,                        -- Người dùng
    VariantId INT NOT NULL,                     -- Biến thể sản phẩm
    Quantity INT NOT NULL,                      -- Số lượng
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày thêm
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (VariantId) REFERENCES ProductVariants(VariantId) ON DELETE CASCADE,
    UNIQUE(UserId, VariantId)                   -- Một biến thể chỉ 1 dòng trong giỏ
);

-- Bảng đánh giá sản phẩm
CREATE TABLE Reviews (
    ReviewId INT AUTO_INCREMENT PRIMARY KEY,   -- Mã đánh giá
    UserId INT NOT NULL,                       -- Người đánh giá
    ProductId INT NOT NULL,                    -- Sản phẩm
    Rating INT CHECK (Rating BETWEEN 1 AND 5), -- Số sao
    Comment TEXT,                              -- Nội dung
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày đánh giá
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Products(ProductId) ON DELETE CASCADE
);

-- Bảng điểm thưởng (số dư hiện tại)
CREATE TABLE RewardPoints (
    RewardId INT AUTO_INCREMENT PRIMARY KEY, -- Mã điểm
    UserId INT NOT NULL,                     -- Người dùng
    Points INT NOT NULL,                     -- Số điểm
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE
);

-- Bảng lịch sử giao dịch điểm
CREATE TABLE PointTransactions (
    TransactionId INT AUTO_INCREMENT PRIMARY KEY, -- Mã giao dịch
    UserId INT NOT NULL,                          -- Người dùng
    Points INT NOT NULL,                          -- Số điểm thay đổi
    Type ENUM('Earned','Used') NOT NULL,          -- Loại: nhận / dùng
    Description VARCHAR(255),                     -- Ghi chú
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE
);

-- Bảng log sử dụng khuyến mãi
CREATE TABLE PromotionUses (
    UseId INT AUTO_INCREMENT PRIMARY KEY, -- Mã log
    PromotionId INT NOT NULL,             -- Khuyến mãi
    UserId INT NOT NULL,                  -- Người dùng
    OrderId INT NOT NULL,                 -- Đơn hàng
    UsedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày dùng
    FOREIGN KEY (PromotionId) REFERENCES Promotions(PromotionId),
    FOREIGN KEY (UserId) REFERENCES Users(UserId),
    FOREIGN KEY (OrderId) REFERENCES Orders(OrderId)
);

-- Bảng giao dịch VNPay
CREATE TABLE VNPayTransactions (
    VNPayId INT AUTO_INCREMENT PRIMARY KEY, -- Mã giao dịch VNPay
    OrderId INT NOT NULL,                   -- Đơn hàng
    Amount DECIMAL(10,2) NOT NULL,          -- Số tiền
    Status VARCHAR(50),                     -- Trạng thái
    TransactionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày giao dịch
    FOREIGN KEY (OrderId) REFERENCES Orders(OrderId) ON DELETE CASCADE
);

-- Bảng log thanh toán
CREATE TABLE PaymentLogs (
    PaymentId INT AUTO_INCREMENT PRIMARY KEY, -- Mã log
    OrderId INT NOT NULL,                     -- Đơn hàng
    Method VARCHAR(50),                       -- Phương thức
    Status VARCHAR(50),                       -- Trạng thái
    Amount DECIMAL(10,2),                     -- Số tiền
    TransactionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày giao dịch
    FOREIGN KEY (OrderId) REFERENCES Orders(OrderId) ON DELETE CASCADE
);

-- Bảng danh sách yêu thích
CREATE TABLE Wishlists (
    WishlistId INT AUTO_INCREMENT PRIMARY KEY, -- Mã wishlist
    UserId INT NOT NULL,                       -- Người dùng
    ProductId INT NOT NULL,                    -- Sản phẩm
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày thêm
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Products(ProductId) ON DELETE CASCADE
);

-- Bảng yêu cầu hỗ trợ / bảo hành
CREATE TABLE SupportTickets (
    TicketId INT AUTO_INCREMENT PRIMARY KEY, -- Mã ticket
    UserId INT NOT NULL,                     -- Người gửi
    OrderId INT NULL,                        -- Đơn liên quan
    Subject VARCHAR(255) NOT NULL,           -- Chủ đề
    Message TEXT NOT NULL,                   -- Nội dung
    Status ENUM('Open','In Progress','Resolved','Closed') DEFAULT 'Open', -- Trạng thái
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày tạo
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE,
    FOREIGN KEY (OrderId) REFERENCES Orders(OrderId) ON DELETE SET NULL
);

-- Bảng log tồn kho
CREATE TABLE InventoryLogs (
    LogId INT AUTO_INCREMENT PRIMARY KEY, -- Mã log
    ProductVariantId INT NOT NULL,        -- Biến thể
    ChangeType ENUM('Import','Export') NOT NULL, -- Nhập / Xuất
    Quantity INT NOT NULL,                -- Số lượng thay đổi
    Note VARCHAR(255),                    -- Ghi chú
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày ghi log
    FOREIGN KEY (ProductVariantId) REFERENCES ProductVariants(VariantId) ON DELETE CASCADE
);
