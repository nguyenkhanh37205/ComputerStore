<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTC Computer</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/png" />
    <link rel="stylesheet" href="{{ asset('assets/css/clien-css/style.css') }}">
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
                            <a href="{{ route('register.form') }}">Đăng ký</a>
                            @endauth
                        </div>
                    </div>
                    <a href="#" class="cart-icon">
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

    <section class="banner-section">
        <!-- <div class="main-banner-content"> -->
        <!-- <img src="../public/images/iphone.webp" alt=""> -->
        <div class="banner-slider">
            <div class="slides">
                <div class="slide active">
                    <img src="{{ asset('assets/images/slide/banner-ip15.jpg') }}" alt="Banner 1">
                </div>
                <div class="slide">
                    <img src="{{ asset('assets/images/slide/banner-oppo.jpg') }}" alt="Banner 2">
                </div>
                <div class="slide">
                    <img src="{{ asset('assets/images/slide/banner-samsung.jpg') }}" alt="Banner 3">
                </div>
            </div>

            <!-- Nút điều khiển -->
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>

            <!-- Dots -->
            <div class="dots">
                <span class="dot active" data-index="0"></span>
                <span class="dot" data-index="1"></span>
                <span class="dot" data-index="2"></span>
            </div>
        </div>
    </section>

    <main>
        <section class="featured-categories">
            <div class="container">
                <h2>Danh mục nổi bật</h2>
                <div class="category-grid">
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/dien_thoai_icon_cate_05347c5136.webp') }}" alt="Điện thoại">
                        <span>Điện thoại</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/man_hinh_ic_cate_7663908793.webp') }}" alt="Máy tính bảng">
                        <span>Máy tính bảng</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/laptop_ic_cate_47e7264bc7.webp') }}" alt="Laptop">
                        <span>Laptop</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/man_hinh_ic_cate_7663908793.webp') }}" alt="Màn hình">
                        <span>Màn hình</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/pc_ic_cate_accf0a1f43.webp') }}" alt="PC - Máy tính để bàn">
                        <span>PC - Máy tính để bàn</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/phu_kien_ic_cate_ecae8ddd38.webp') }}" alt="Phụ kiện">
                        <span>Phụ kiện</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/sim-fpt.png') }}" alt="Sim FPT">
                        <span>Sim FPT</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/dong-ho-thong-minh.png') }}" alt="Đồng hồ thông minh">
                        <span>Đồng hồ thông minh</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/tivi.png') }} " alt="Tivi">
                        <span>Tivi</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/tu-lanh.png') }}" alt="Tủ lạnh">
                        <span>Tủ lạnh</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/robot-hut-bui.png') }}" alt="Robot hút bụi">
                        <span>Robot hút bụi</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/may-loc-nuoc.png') }}" alt="Máy lọc nước">
                        <span>Máy lọc nước</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/dien-gia-dung.png') }}" alt="Điện gia dụng">
                        <span>Điện gia dụng</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/may-giat.png') }}" alt="Máy giặt">
                        <span>Máy giặt</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/thiet-bi-bep.png') }}" alt="Thiết bị bếp">
                        <span>Thiết bị bếp</span>
                    </a>
                    <a href="#" class="category-item">
                        <img src="{{ asset('assets/images/may_doi_tra_icon_cate_f272970ca9.webp') }}"
                            alt="Máy cũ giá rẻ">
                        <span>Máy cũ giá rẻ</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="product-section viewed-products">
            <div class="container">
                {{-- --- HIỂN THỊ THEO TỪNG THƯƠNG HIỆU --- --}}
                @foreach($productsByBrand as $brandName => $brandProducts)
                <div class="section-header">
                    <h2>{{ $brandName }}</h2>
                </div>

                <div class="product-list-container">
                    <div class="product-list">
                        @foreach($brandProducts as $product)
                        {{-- Lấy variant đầu tiên (nếu có) --}}
                        @php
                        $variant = optional($product->variants)->first();

                        // Nếu sản phẩm chưa có variant, dùng dữ liệu gốc từ bảng products
                        if (!$variant) {
                        $variant = (object)[
                        'price' => $product->price,
                        'image' => $product->image,
                        'ram' => null,
                        'rom' => null,
                        'color' => null,
                        'old_price' => null,
                        ];
                        }
                        @endphp

                        {{-- Hiển thị sản phẩm --}}
                        <div class="product-card">
                            <div class="product-header">
                                {{-- Nếu có giảm giá --}}
                                @if(isset($variant->old_price) && $variant->old_price > $variant->price)
                                <span class="save-badge">
                                    Tiết kiệm<br>
                                    <b>{{ number_format($variant->old_price - $variant->price, 0, ',', '.') }} ₫</b>
                                </span>
                                @endif
                                <button class="favorite-btn"><i class="far fa-heart"></i></button>
                            </div>

                            {{-- Ảnh sản phẩm --}}
                            <img src="{{ asset('storage/images/' . ($variant->image ?? $product->image)) }}"
                                alt="{{ $product->productName }}" class="product-img">

                            {{-- Thông tin sản phẩm --}}
                            <div class="product-info">
                                <p class="brand">{{ $product->brand->brandName ?? '' }}</p>
                                <h4 class="product-name">{{ $product->productName }}</h4>

                                {{-- Thông tin variant (nếu có) --}}
                                @if($variant->ram || $variant->rom || $variant->color)
                                <p class="variant-info">
                                    {{ $variant->ram ?? '' }} {{ $variant->ram ? '/' : '' }}
                                    {{ $variant->rom ?? '' }} {{ $variant->rom ? '/' : '' }}
                                    {{ $variant->color ?? '' }}
                                </p>
                                @endif

                                <div class="price-info">
                                    <p class="current-price">{{ number_format($variant->price, 0, ',', '.') }} ₫</p>
                                    @if(isset($variant->old_price) && $variant->old_price > $variant->price)
                                    <p class="old-price">{{ number_format($variant->old_price, 0, ',', '.') }} ₫</p>
                                    @endif
                                </div>
                            </div>

                            <a href="#" class="contact-link">Thêm giỏ hàng</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </section>


    </main>

    <footer>
        <div class="footer-top">
            <div class="container">
                <div class="footer-intro">
                    <p class="intro-title">Hệ thống Máy Tính Minh Thường Computer</p>
                    <p class="intro-text">Bao gồm Cửa hàng MTC</p>
                    <p class="intro-text">Cửa hàng NVK</p>
                    <a href="#" class="view-store-link">Xem danh sách cửa hàng</a>
                </div>
                <div class="footer-columns">
                    <div class="footer-column">
                        <h4>KẾT NỐI VỚI MÁY TÍNH MINH THƯỜNG</h4>
                        <div class="social-icons">
                            <a href="https://www.facebook.com/khanhnguyen03072005"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                            <a href="#"><i class="fab fa-tiktok"></i></a>
                        </div>
                        <h4>TỔNG ĐÀI MIỄN PHÍ</h4>
                        <p>Tư vấn mua hàng (Miễn phí):<br><span>1800.6601 (Nhánh 1)</span></p>
                        <!-- <p>Hỗ trợ kỹ thuật:<br><span>1800.6601 (Nhánh 2)</span></p> -->
                        <p>Góp ý, khiếu nại và tiếp nhận bảo hành:<br><span>1800.6616 (8h00 - 22h00)</span></p>
                    </div>
                    <div class="footer-column">
                        <h4>VỀ CHÚNG TÔI</h4>
                        <ul class="footer-links">
                            <li><a href="#">Giới thiệu về công ty</a></li>
                            <li><a href="#">Quy chế hoạt động</a></li>
                            <li><a href="#">Dự án Doanh nghiệp</a></li>
                            <li><a href="#">Tin tức khuyến mại</a></li>
                            <li><a href="#">Giới thiệu máy đổi trả</a></li>
                            <li><a href="#">Hướng dẫn mua hàng & thanh toán online</a></li>
                            <li><a href="#">Đại lý ủy quyền và TTBH ủy quyền của Apple</a></li>
                            <li><a href="#">Tra cứu hóa đơn điện tử</a></li>
                            <li><a href="#">Tra cứu bảo hành</a></li>
                            <li><a href="#">Câu hỏi thường gặp</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>CHÍNH SÁCH</h4>
                        <ul class="footer-links">
                            <li><a href="#">Chính sách bảo hành</a></li>
                            <li><a href="#">Chính sách đổi trả</a></li>
                            <li><a href="#">Chính sách bảo mật</a></li>
                            <li><a href="#">Chính sách trả góp</a></li>
                            <li><a href="#">Chính sách khu hộp sản phẩm</a></li>
                            <li><a href="#">Chính sách giao hàng & lắp đặt</a></li>
                            <li><a href="#">Chính sách mang di động FPT</a></li>
                            <li><a href="#">Chính sách thu thập & xử lý dữ liệu cá nhân</a></li>
                            <li><a href="#">Quy định về hỗ trợ kỹ thuật & sao lưu dữ liệu</a></li>
                            <li><a href="#">Chính sách giao hàng & lắp đặt Điện máy, Gia dụng</a></li>
                            <li><a href="#">Chính sách chương trình khách hàng thân thiết</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>HỖ TRỢ THANH TOÁN</h4>
                        <div class="payment-methods">
                            <img src="{{ asset('images/payment-icons.png') }}" alt="Các phương thức thanh toán"
                                class="payment-icons">
                        </div>
                        <h4>CHỨNG NHẬN</h4>
                        <div class="certifications">
                            <img src="{{ asset('images/cert-icons.png') }}" alt="Các chứng nhận" class="cert-icons">
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <div class="fpt-retail">
                        <p>WEBSITE CÙNG MTC</p>
                        <div class="partner-logos">
                            <img src="{{ asset('images/f-studio.png') }}" alt="F.Studio">
                            <img src="{{ asset('images/f-care.png') }}" alt="F.Care">
                            <img src="{{ asset('images/long-chau.png') }}" alt="Nhà thuốc Long Châu">
                        </div>
                    </div>
                    <div class="search-keywords">
                        <p class="search-keywords-title">Mọi người cũng tìm kiếm:</p>
                        <p>iPhone 17 Pro Max | iPhone 17 | iPhone 17 Pro | iPhone Air | iPhone 16 | iPhone 16 Pro
                            Max | Laptop | Samsung | iPhone 15 | Laptop gaming | Laptop AI</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-section">
            <div class="container">
                <p>© 2007 - 2024 Công Ty Cổ Phần Bán Lẻ Kỹ Thuật Số NVK • Địa chỉ: ngõ 4/2 Cầu Tó, Thanh Trì,
                    TP. Hà Nội • GPĐKKD số 031609355 do Sở KHĐT TP.Hà Nội cấp ngày 06/03/2012.</p>
            </div>
        </div>
    </footer>

    <script src="{{  asset('assets/js/banner-slide.js') }}"></script>
    <script src="{{  asset('assets/js/dropdown-account.js') }}"></script>

</body>

</html>