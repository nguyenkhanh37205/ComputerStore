<link rel="stylesheet" href="{{ asset('assets/css/auth-css/search.css') }}">
@include('client.layout.header')

<div class="results-container">
    <h2>Kết quả tìm kiếm cho: "{{ $query ?? '' }}"</h2>

    @if($products->isEmpty())
        <p class="no-results">Không tìm thấy sản phẩm nào phù hợp.</p>
    @else
        <div class="results-grid">
            @foreach($products as $product)
                <div class="result-card">
                    {{-- Ảnh sản phẩm --}}
                    <img src="{{ asset($product->image) }}" alt="{{ $product->productName }}">

                    <div class="info">
                        {{-- Tên sản phẩm --}}
                        <h3>{{ $product->productName }}</h3>

                        {{-- Giá sản phẩm --}}
                        <p>{{ number_format($product->price) }} VNĐ</p>

                        {{-- Thương hiệu và danh mục --}}
                        <p style="font-size: 14px; color: #555;">
                            @if($product->brands)
                                Thương hiệu: {{ $product->brands->brandName }}
                            @endif
                            @if($product->categories)
                                | Danh mục: {{ $product->categories->categoryName }}
                            @endif
                        </p>

                        {{-- Nút xem chi tiết --}}
                        <a href="{{ route('product.show', ['id' => $product->productId]) }}"
                           class="btn-view">Xem chi tiết</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@include('client.layout.footer')

