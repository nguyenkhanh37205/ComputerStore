@extends('admin.layout')

@section('content')
    <div class="content-header">
        <h1>Danh sách sản phẩm</h1>
        {{-- Liên kết đến trang thêm mới sản phẩm --}}
        <a href="{{ route('admin.products.create') }}" class="add-btn">Thêm mới</a>
        <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>HÌNH ẢNH</th>
                    <th>TÊN</th>
                    <th>LOẠI</th>
                    <th>THƯƠNG HIỆU</th>
                    <th>GIÁ</th>
                    <th>HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                {{-- Bắt đầu vòng lặp qua danh sách sản phẩm --}}
                @forelse ($products as $product)
                    <tr>
                        {{-- ID (Sử dụng productId) --}}
                        <td>{{ $product->productId }}</td>

                        {{-- HÌNH ẢNH --}}
                        <td>
                            @if ($product->image)
                                {{-- Giả định ảnh được lưu trong thư mục 'products' của storage/app/public --}}
                                <img src="{{ asset($product->image) }}" alt="{{ $product->productName }}" style="max-width: 100px;">
                            @else
                                Không ảnh
                            @endif
                        </td>

                        {{-- TÊN (Sử dụng productName) --}}
                        <td>{{ $product->productName }}</td>

                        {{-- LOẠI (Lấy từ quan hệ category) --}}
                        <td>{{ $product->categories->categoryName ?? 'N/A' }}</td>

                        {{-- THƯƠNG HIỆU (Lấy từ quan hệ brand) --}}
                        <td>{{ $product->brands->brandName ?? 'N/A' }}</td>

                        {{-- GIÁ (Lấy từ cột 'price' - đã giả định trong Model Products) --}}
                        <td>{{ number_format($product->price ?? 0, 0, ',', '.') }} đ</td>

                        {{-- HÀNH ĐỘNG --}}
                        <td>

                            {{-- Nút SỬA --}}
                            <a href="{{ route('admin.products.edit', $product->productId) }}" class="btn-action edit">Sửa</a>

                            {{-- Nút XÓA (Sử dụng form để gửi request DELETE) --}}
                            <form action="{{ route('admin.products.destroy', $product->productId) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action delete"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm {{ $product->productName }} không?');">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Không có sản phẩm nào được tìm thấy.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
            
    </div>
    <script src="{{ asset('js/notifications.js') }}"></script>
     <script>
        @if (Session::has('success'))
            // Gọi hàm JS từ file notification.js
            showToast('success', "{{ Session::get('success') }}");
        @endif

        @if (Session::has('error'))
            showToast('error', "{{ Session::get('error') }}");
        @endif
    </script>
@endsection