<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>

    <link rel="stylesheet" href="{{ asset('assets/css/auth-css/productdetail.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>

    @include('client.layout.header')

    <div class="container product-detail-page">

        {{-- Phần breadcrumb (Điều hướng) --}}
        <div class="breadcrumb-nav">
            <a href="/">Trang chủ</a>
            &gt; <a
                href="{{ route('category.show', $product->categories->id ?? '') }}">{{ $product->categories->categoryName ?? 'Sản phẩm' }}</a>
            &gt; <span>{{ $product->productName ?? 'Chi tiết sản phẩm' }}</span>
        </div>

        {{-- --- KHU VỰC ẢNH & THÔNG TIN CƠ BẢN --- --}}
        <div class="product-main-info">

            {{-- 1. Cột Hình ảnh --}}
            <div class="product-images">
                <img id="productImage"
                    src="{{ asset($variant->image ?? $product->image ?? 'assets/images/placeholder.jpg') }}"
                    alt="{{ $product->productName }}">

                {{-- Ảnh phụ (Thumbnail) --}}
                <div class="thumbnail-images">
                    {{-- Thêm logic hiển thị ảnh phụ tại đây nếu cần --}}
                </div>
            </div>

            {{-- 2. Cột Chi tiết & Mua hàng --}}
            <div class="product-summary">
                <h1 class="product-name">{{ $product->productName ?? 'Tên sản phẩm' }}</h1>

                <div class="product-meta">
                    <p>Thương hiệu: {{ $product->brands->brandName ?? 'Chưa rõ' }}</p>
                    <p>Mã sản phẩm: {{ $product->sku ?? $product->productId }}</p>
                </div>

                <div class="product-price-box">
                    @if(isset($product->old_price) && $product->old_price > $product->price)
                        <span class="old-price">{{ number_format($product->old_price) }} VNĐ</span>
                        <span
                            class="discount-badge">-{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%</span>
                    @endif
                    <span class="current-price" id="productPrice">
                        {{ number_format($variant->price ?? $product->price ?? 0) }} VNĐ
                    </span>

                </div>

                <div class="product-short-description">
                    <h3>Mô tả nổi bật:</h3>
                    <ul>
                        <li>{{ $product->short_description ?? 'Sản phẩm chưa có mô tả nổi bật.' }}</li>
                    </ul>
                </div>

                {{-- ==== CHỌN MÀU ==== --}}
                <div class="variant-group mt-3">
                    <h3>Chọn màu:</h3>
                    <div class="variant-options" id="colorOptions">
                        @php $colors = $product->variants->groupBy('color'); @endphp
                        @foreach($colors as $color => $items)
                            <button type="button" class="variant-btn color-btn" data-color="{{ $color }}">
                                {{ $color }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- ==== CHỌN ROM ==== --}}
                <div class="variant-group mt-3">
                    <h3>Chọn dung lượng:</h3>
                    <div class="variant-options" id="romOptions">
                        @php $roms = $product->variants->groupBy('rom'); @endphp
                        @foreach($roms as $rom => $items)
                            <button type="button" class="variant-btn rom-btn" data-rom="{{ $rom }}">
                                {{ $rom }} GB
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- ==== INPUT ẨN LẤY variantId ==== --}}
                <input type="hidden" id="selectedVariantId" name="variant_id" value="{{ $variant->variantId ?? '' }}">


                <div class="product-status">
                    <p>Tình trạng:
                        <span class="{{ ($variant->stock ?? 0) > 0 ? 'status-in-stock' : 'status-out-stock' }}">
                            {{ ($variant->stock ?? 0) > 0 ? 'CÒN HÀNG' : 'HẾT HÀNG' }}
                        </span>
                        (<span id="productStock">{{ $variant->stock ?? 0 }}</span> sản phẩm)
                    </p>
                </div>

                {{-- Form thêm vào giỏ hàng --}}
                <form action="{{ route('cart.store', ['id' => $product->productId ?? 0]) }}" method="POST"
                    class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant->variantId ?? '' }}">

                    <div class="quantity-control">
                        <button type="button" class="qty-btn" onclick="updateQty(-1)">-</button>

                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            max="{{ $variant->stock ?? 10 }}">

                        <button type="button" class="qty-btn" onclick="updateQty(1)">+</button>
                    </div>

                    {{-- Nút Submit (đã sửa dùng $variant->stock để kiểm tra trạng thái) --}}
                    <button type="submit" class="btn btn-lg btn-buy" {{ ($variant->stock ?? 0) <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart"></i> THÊM VÀO GIỎ
                    </button>

                    <button type="button" class="btn btn-lg btn-wishlist">
                        <i class="far fa-heart"></i> Yêu thích
                    </button>
                </form>
            </div>
        </div>

        <hr class="section-divider">

        {{-- --- KHU VỰC MÔ TẢ CHI TIẾT & THÔNG SỐ --- --}}
        <div class="product-details-tabs">
            <div class="tabs-header">
                <button class="tab-btn active" data-target="#description">MÔ TẢ CHI TIẾT</button>
                <button class="tab-btn" data-target="#specifications">THÔNG SỐ KỸ THUẬT</button>
            </div>

            <div class="tab-content-container">
                <div class="tab-pane active" id="description">
                    {!! $product->productDescription ?? '<p>Sản phẩm này chưa có mô tả chi tiết.</p>' !!}
                </div>
                <div class="tab-pane" id="specifications">
                    {!! $product->specifications ?? '<p>Thông số kỹ thuật đang được cập nhật...</p>' !!}
                </div>
            </div>
        </div>

        <hr class="section-divider">

        {{-- --- KHU VỰC SẢN PHẨM LIÊN QUAN --- --}}
        @if(isset($relatedProducts) && count($relatedProducts) > 0)
            <div class="related-products">
                <h2>Sản phẩm liên quan</h2>
                <div class="related-products-grid">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('product.show', $related->productId) }}" class="related-card">
                            <img src="{{ asset($related->image ?? 'assets/images/placeholder.jpg') }}"
                                alt="{{ $related->productName }}">
                            <div class="info">
                                <h3>{{ $related->productName }}</h3>
                                <p class="price">{{ number_format($related->price) }} VNĐ</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    @include('client.layout.footer')

    <script>
        const variants = @json($product->variants);
    </script>


    <script src="{{ asset('assets/js/productdetail.js') }}"></script>

</body>

</html>