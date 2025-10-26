@extends('admin.layout')

@section('content')
<div class="content-header">
    <h1>Danh sách Biến thể Sản phẩm</h1>
    {{-- Liên kết đến trang thêm mới biến thể --}}
    <a href="{{ route('admin.variants.create') }}" class="add-btn">Thêm mới</a>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</div>

{{-- Hiển thị thông báo thành công hoặc lỗi (nếu có) --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>SẢN PHẨM GỐC</th>
                <th>RAM</th>
                <th>ROM</th>
                <th>MÀU SẮC</th>
                <th>GIÁ</th>
                <th>TỒN KHO</th>
                <th>HÌNH ẢNH</th>
                <th>HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            {{-- Bắt đầu vòng lặp qua danh sách biến thể ($variants) --}}
            @forelse ($variants as $variant)
                <tr>
                    {{-- ID --}}
                    <td>{{ $variant->variantId }}</td> 
                    
                    {{-- SẢN PHẨM GỐC (Sử dụng quan hệ product) --}}
                    <td>{{ $variant->product->productName ?? 'Sản phẩm đã xóa' }}</td> 
                    
                    {{-- RAM --}}
                    <td>{{ $variant->ram }}</td> 
                    
                    {{-- ROM --}}
                    <td>{{ $variant->rom }}</td> 
                    
                    {{-- MÀU SẮC --}}
                    <td>{{ $variant->color }}</td> 
                    
                    {{-- GIÁ --}}
                    <td>{{ number_format($variant->price, 0, ',', '.') }} đ</td> 

                    {{-- TỒN KHO --}}
                    <td>{{ $variant->stock }}</td> 
                    
                    {{-- HÌNH ẢNH --}}
                    <td>
                        @if ($variant->image)
                            {{-- Giả định ảnh được lưu trong thư mục 'variants' của storage/app/public --}}
                            <img src="{{ asset('storage/' . $variant->image) }}" alt="Ảnh biến thể" style="width: 50px; height: auto;">
                        @else
                            Không ảnh
                        @endif
                    </td>

                    {{-- HÀNH ĐỘNG --}}
                    <td>
                        {{-- Nút SỬA --}}
                        <a href="{{ route('admin.variants.edit', $variant->variantId) }}" class="btn-action edit">Sửa</a>
                        
                        {{-- Nút XÓA (Sử dụng form để gửi request DELETE) --}}
                        <form action="{{ route('admin.variants.destroy', $variant->variantId) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action delete" onclick="return confirm('Bạn có chắc chắn muốn xóa biến thể ID {{ $variant->variantId }} không?');">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Không có biến thể nào được tìm thấy.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection