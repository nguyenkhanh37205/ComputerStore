<style>
    /* ==== GLOBAL SEARCH STYLES ==== */
    .results-container {
        width: 90%;
        max-width: 1200px;
        margin: 80px auto;
    }

    .results-container h2 {
        text-align: center;
        font-size: 24px; /* Tăng kích thước tiêu đề */
        margin-bottom: 30px; /* Tăng khoảng cách */
        color: #222;
        font-weight: 600;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .no-results {
        text-align: center;
        color: #777;
        font-size: 18px; /* Tăng kích thước */
        padding: 40px 0;
    }

    /* ==== RESULTS GRID ==== */
    .results-grid {
        display: grid;
        /* Đảm bảo khoảng cách 20px */
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); 
        gap: 25px; /* Tăng khoảng cách */
    }

    /* ==== RESULT CARD ==== */
    .result-card {
        background: #fff;
        border-radius: 12px; /* Giảm nhẹ border-radius */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Shadow rõ ràng hơn */
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none; /* Đảm bảo không có gạch chân nếu thẻ là <a> */
        display: flex;
        flex-direction: column;
    }

    .result-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .result-card .image-wrapper {
        height: 200px; /* Tăng chiều cao ảnh */
        overflow: hidden;
    }
    
    .result-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .result-card:hover img {
        transform: scale(1.05); /* Hiệu ứng zoom khi hover */
    }

    .result-card .info {
        padding: 15px;
        text-align: left; /* Căn trái cho tên và giá */
        flex-grow: 1; /* Đảm bảo info chiếm hết không gian còn lại */
        display: flex;
        flex-direction: column;
    }

    .result-card .info h3 {
        font-size: 17px;
        color: #333;
        margin-bottom: 5px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis; /* Cắt bớt tên dài */
    }
    
    .result-card .price {
        font-size: 18px;
        color: #dc3545; /* Đổi màu giá thành màu đỏ/nguy hiểm */
        font-weight: 700;
        margin-bottom: 10px;
    }

    .result-card .meta-data {
        font-size: 13px;
        color: #777;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .btn-view {
        display: block; /* Đặt nút chiếm toàn bộ chiều ngang */
        text-align: center;
        margin-top: auto; /* Đẩy nút xuống cuối */
        padding: 10px 12px;
        background-color: #0d6efd; /* Màu xanh dương Bootstrap primary */
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        transition: background-color 0.2s;
    }

    .btn-view:hover {
        background-color: #0b5ed7;
    }
</style>

@include('client.layout.header')

<div class="results-container">
    <!-- <h2>Kết quả tìm kiếm cho: "**{{ $query ?? '...' }}**"</h2> -->

    @if($products->isEmpty())
        <p class="no-results">
            <i class="fas fa-exclamation-circle"></i> Rất tiếc, không tìm thấy sản phẩm nào phù hợp với từ khóa của bạn.
        </p>
    @else
        <div class="results-grid">
            @foreach($products as $product)
                {{-- Đặt thẻ a bao quanh để toàn bộ card có thể click --}}
                <a href="{{ route('product.show', ['id' => $product->productId]) }}" class="result-card">
                    
                    {{-- Ảnh sản phẩm --}}
                    <div class="image-wrapper">
                        {{-- Bổ sung logic kiểm tra ảnh, nếu không có thì dùng placeholder --}}
                        <img 
                            src="{{ asset($product->image ?? 'assets/images/placeholder.jpg') }}" 
                            alt="{{ $product->productName }}"
                        >
                    </div>

                    <div class="info">
                        {{-- Tên sản phẩm --}}
                        <h3>{{ $product->productName }}</h3>

                        {{-- Giá sản phẩm --}}
                        <p class="price">{{ number_format($product->price) }} VNĐ</p>

                        {{-- Thương hiệu và danh mục (Meta Data) --}}
                        <p class="meta-data">
                            @if($product->brands)
                                <span>Thương hiệu: {{ $product->brands->brandName }}</span><br>
                            @endif
                            @if($product->categories)
                                <span>Danh mục: {{ $product->categories->categoryName }}</span>
                            @endif
                        </p>

                        {{-- Nút xem chi tiết (nếu toàn bộ card không phải là link) --}}
                        {{-- Nếu đã đặt <a> bao quanh card, nút này có thể dư thừa hoặc dùng cho mục đích khác --}}
                        <span class="btn-view">Xem chi tiết</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>

@include('client.layout.footer')