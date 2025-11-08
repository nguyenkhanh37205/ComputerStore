<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTC Computer</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/png" />
    <link rel="stylesheet" href="{{ asset('assets/css/auth-css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <header class="main-header">
        <div class="header-top">
            <div class="container">
                <a href="/" class="logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo MTC">
                </a>
                <div class="search-bar">
                    <input type="text" placeholder="Nhập tên điện thoại, máy tính, phụ kiện... cần tìm">
                    <button><i class="fas fa-search"></i></button>
                </div>
                <div class="user-actions">
                    <div class="account-menu">
                        <a href="" class="login-icon">
                            @auth
                            @if(Auth::user()->image)
                            <img src="{{ asset(Auth::user()->image) }}" alt="Avatar"
                                style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; vertical-align: middle; margin-right: 8px;">
                            @else
                            <i class="fas fa-user-circle" style="font-size: 30px;"></i>
                            @endif
                            <span>{{ Auth::user()->username }}</span>
                            @else
                            <i class="fas fa-user-circle"></i>
                            <span>Tài khoản</span>
                            @endauth
                        </a>

                        <div class="dropdown-login">
                            @auth
                            <a href="{{ url('profile') }}">Hồ sơ cá nhân</a>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit"
                                    style="background:none;border:none;padding:0;color:#007bff;cursor:pointer;">
                                    Đăng xuất
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login.form') }}">Đăng nhập</a>
                            <!-- <a href="{{ route('register.form') }}">Đăng ký</a> -->
                            @endauth
                        </div>
                    </div>
                    <a href="{{ url('cart') }}" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Giỏ hàng</span>
                    </a>
                </div>
            </div>
        </div>
        <nav class="header-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li class="nav-item has-dropdown">
                        <a href="#"><i class="fas fa-list"></i> Danh mục</a>
                        <ul class="dropdown-content">
                            <li class="dropdown-item has-sub-dropdown">
                                <a href="#">Điện thoại</a>
                                <ul class="sub-dropdown-content">
                                    <li><a href="#">Apple</a></li>
                                    <li><a href="#">Samsung</a></li>
                                    <li><a href="#">Xiaomi</a></li>
                                    <li><a href="#">Oppo</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-item has-sub-dropdown">
                                <a href="#">Laptop</a>
                                <ul class="sub-dropdown-content">
                                    <li><a href="#">Macbook</a></li>
                                    <li><a href="#">Dell</a></li>
                                    <li><a href="#">HP</a></li>
                                    <li><a href="#">Lenovo</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-item"><a href="#">Tablet</a></li>
                            <li class="dropdown-item"><a href="#">Phụ kiện</a></li>
                            <li class="dropdown-item"><a href="#">Đồng hồ</a></li>
                            <li class="dropdown-item"><a href="#">Âm thanh</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a href="#">Săn Deal Online</a></li>
                    <li class="nav-item"><a href="#">LG mua 1 tặng 1</a></li>
                    <li class="nav-item"><a href="#">XIAOMI 17 Pro Max 22.299.000đ</a></li>
                </ul>
                <div class="location-picker">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>Chọn khu vực để xem ưu đãi</p>
                </div>
            </div>
        </nav>
    </header>