<section class="banner-section">
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

        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>

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
                <a href="{{ route('search', ['categories' => 'dien-thoai']) }}" class="category-item">
                    <img src="{{ asset('assets/images/dien_thoai_icon_cate_05347c5136.webp') }}" alt="Điện thoại">
                    <span>Điện thoại</span>
                </a>
                <a href="{{ route('search', ['categories' => 'may-tinh-bang']) }}" class="category-item">
                    <img src="{{ asset('assets/images/man_hinh_ic_cate_7663908793.webp') }}" alt="Máy tính bảng">
                    <span>Máy tính bảng</span>
                </a>
                <a href="{{ route('search', ['categories' => 'laptop']) }}" class="category-item">
                    <img src="{{ asset('assets/images/laptop_ic_cate_47e7264bc7.webp') }}" alt="Laptop">
                    <span>Laptop</span>
                </a>
                <a href="{{ route('search', ['categories' => 'man-hinh']) }}" class="category-item">
                    <img src="{{ asset('assets/images/man_hinh_ic_cate_7663908793.webp') }}" alt="Màn hình">
                    <span>Màn hình</span>
                </a>
                <a href="{{ route('search', ['categories' => 'pc']) }}" class="category-item">
                    <img src="{{ asset('assets/images/pc_ic_cate_accf0a1f43.webp') }}" alt="PC - Máy tính để bàn">
                    <span>PC - Máy tính để bàn</span>
                </a>
                <a href="{{ route('search', ['categories' => 'phu-kien']) }}" class="category-item">
                    <img src="{{ asset('assets/images/phu_kien_ic_cate_ecae8ddd38.webp') }}" alt="Phụ kiện">
                    <span>Phụ kiện</span>
                </a>
                <a href="{{ route('search', ['categories' => 'dong-ho-thong-minh']) }}" class="category-item">
                    <img src="{{ asset('assets/images/dong-ho-thong-minh.png') }}" alt="Đồng hồ thông minh">
                    <span>Đồng hồ thông minh</span>
                </a>
                <a href="{{ route('search', ['categories' => 'may-cu-gia-re']) }}" class="category-item">
                    <img src="{{ asset('assets/images/may_doi_tra_icon_cate_f272970ca9.webp') }}" alt="Máy cũ giá rẻ">
                    <span>Máy cũ giá rẻ</span>
                </a>
            </div>
        </div>
    </section>

    <section class="product-section viewed-products">
        <div class="container">
            {{-- --- HIỂN THỊ THEO TỪNG THƯƠNG HIỆU --- --}}
            @if (isset($productsByBrand))
                @foreach($productsByBrand as $brandName => $brandProducts)
                    <div class="section-header">
                        <h2>{{ $brandName }}</h2>
                    </div>
                    <div class="product-list-container">
                        <div class="product-list">
                            @foreach($brandProducts as $product)
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

                                {{-- Thẻ A (cha) sẽ dẫn đến trang chi tiết --}}
                                <a href="{{ route('product.show', ['id' => $product->productId]) }}" class="product-card"
                                    style="text-decoration: none; color: inherit;">
                                    <div class="product-header">
                                        {{-- Nếu có giảm giá --}}
                                        @if(isset($variant->old_price) && $variant->old_price > $variant->price)
                                            <span class="save-badge">
                                                Tiết kiệm<br>
                                                <b>{{ number_format($variant->old_price - $variant->price, 0, ',', '.') }} ₫</b>
                                            </span>
                                        @endif
                                        {{-- Nút yêu thích --}}
                                        <button class="favorite-btn" onclick="event.preventDefault(); event.stopPropagation();"><i
                                                class="far fa-heart"></i></button>
                                        {{-- SCRIPT NÀY CẦN ĐẶT Ở FILE JS/HEAD để tránh lặp code --}}
                                        <script>
                                            const favBtn = document.querySelector('.favorite-btn');
                                            favBtn.addEventListener('click', (e) => {
                                                e.preventDefault();
                                                e.stopPropagation(); // Ngăn sự kiện click lan truyền lên thẻ <a> cha
                                                favBtn.classList.toggle('active');
                                            });
                                        </script>
                                    </div>

                                    {{-- Ảnh sản phẩm --}}
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->productName }}" class="product-img">


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

                                    {{-- THAY THẾ FORM THÊM GIỎ HÀNG BẰNG NÚT XEM CHI TIẾT --}}
                                    <button class="contact-link view-detail-btn">Xem chi tiết</button>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            @endif
        </div>
    </section>

</main>