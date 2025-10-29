@extends('admin.layout')

@section('content')
<div class="content-header">
    {{-- Hiển thị tên sản phẩm đang chỉnh sửa --}}
    <h1>Chỉnh sửa Sản phẩm: {{ $product->productName }}</h1>
    <a class="quay" href="{{ route('admin.products.index') }}">Quay lại danh sách</a>
    <link rel="stylesheet" href="{{ asset('css/add.css') }}">
</div>

<div class="form-container">
    {{-- Form gửi dữ liệu đến ProductsController@update --}}
    <form action="{{ route('admin.products.update', $product->productId) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Bắt buộc phải có @method('PUT') cho hành động update --}}

        {{-- Tên Sản phẩm --}}
        <div class="form-group">
            <label for="productName">Tên Sản phẩm:</label>
            {{-- Dùng old() hoặc giá trị $product hiện tại --}}
            <input type="text" id="productName" name="productName" class="form-control" 
                   value="{{ old('productName', $product->productName) }}" required>
            @error('productName')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mô tả Sản phẩm --}}
        <div class="form-group">
            <label for="productDescription">Mô tả:</label>
            <textarea id="productDescription" name="productDescription" class="form-control">{{ old('productDescription', $product->productDescription) }}</textarea>
            @error('productDescription')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Giá và Tồn kho (Giả định) --}}
        <div class="form-row">
            <div class="form-group half-width">
                <label for="price">Giá:</label>
                <input type="number" id="price" name="price" class="form-control" 
                       value="{{ old('price', $product->price) }}" required min="0">
                @error('price')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

        </div>

        {{-- Loại (Category) --}}
        <div class="form-group">
            <label for="categoryId">Loại Sản phẩm:</label>
            <select id="categoryId" name="categoryId" class="form-control" required>
                <option value="">-- Chọn Loại --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->categoryId }}" 
                        {{ old('categoryId', $product->categoryId) == $category->categoryId ? 'selected' : '' }}>
                        {{ $category->categoryName }}
                    </option>
                @endforeach
            </select>
            @error('categoryId')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Thương hiệu (Brand) --}}
        <div class="form-group">
            <label for="brandId">Thương hiệu:</label>
            <select id="brandId" name="brandId" class="form-control" required>
                <option value="">-- Chọn Thương hiệu --</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->brandId }}" 
                        {{ old('brandId', $product->brandId) == $brand->brandId ? 'selected' : '' }}>
                        {{ $brand->brandName }}
                    </option>
                @endforeach
            </select>
            @error('brandId')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Hình ảnh hiện tại và mới --}}
        <div class="form-group">
            <label>Hình ảnh hiện tại:</label>
            @if ($product->image)
                <img src="{{ asset($product->image) }}" alt="Ảnh hiện tại" style="width: 100px; display: block; margin-bottom: 10px;">
            @else
                <p>Không có ảnh.</p>
            @endif
            
            <label for="image">Chọn ảnh mới (để thay đổi):</label>
            <input type="file" id="image" name="image" class="form-control">
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

            <button type="submit" class="btn btn-update">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
  
        
    </form>
</div>
@endsection