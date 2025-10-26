<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Trị</title>
    <link rel="stylesheet" href="{{ asset('assets/css/admin-css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="logo-section">
                <img src="../../public/images/atc-logo.png" alt="ATC Logo" class="logo">
                <p>HỆ THỐNG BÁN LINH KIỆN MÁY TÍNH</p>
            </div>
            <div class="sidebar-menu">
                <p class="menu-title">TRANG QUẢN TRỊ</p>
                <ul>
                    <li class="">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Bảng điều khiển</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}">
                            <i class="fas fa-box"></i>
                            <span>Sản phẩm</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users"></i>
                            <span>Người dùng</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}">
                            <i class="fas fa-th-list"></i>
                            <span>Danh mục sản phẩm</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.brands.index') }}">
                            <i class="fas fa-tag"></i>
                            <span>Thương hiệu sản phẩm</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Đơn hàng</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.cart.index') }}">
                            <i class="fas fa-shopping-bag"></i>
                            <span>Giỏ hàng</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.variants.index') }}">
                            <i class="fas fa-cogs"></i>
                            <span>Biến thể sản phẩm</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.wishlists.index') }}">
                            <i class="fas fa-heart"></i>
                            <span>Danh sách yêu thích</span>
                        </a>
                    </li>
                    <li class="sign-out">
                        <a href="{}">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Đăng xuất</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="header">
                <div class="search-box">
                    <input type="text" placeholder="Bạn cần tìm gì?">
                    <button><i class="fas fa-search"></i> Tìm kiếm</button>
                </div>
                <div class="user-info">
                    <p>Xin chào, Quản trị viên</p>
                </div>
            </div>

            <div class="content-area">
                @yield('content')

            </div>
        </div>

    </div>
</body>

</html>