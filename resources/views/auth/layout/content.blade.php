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
                    <img src="{{ asset('assets/images/may_doi_tra_icon_cate_f272970ca9.webp') }}" alt="Máy cũ giá rẻ">
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
                    $variant = (object) [
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
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="variantId" value="{{ $variant->variantId ?? null }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="contact-link">Thêm giỏ hàng</button>
                        </form>

                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
    </section>


</main>