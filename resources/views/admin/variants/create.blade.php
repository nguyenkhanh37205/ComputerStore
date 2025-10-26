@extends('admin.layout')

@section('content')
    <div class="content-header">
        <h1>Thêm mới Biến thể Sản phẩm</h1>
        <br>
        <p class="quay"><a href="{{ route('admin.variants.index') }}">Danh sách biến thể</a></p>
        <link rel="stylesheet" href="{{ asset('css/add.css') }}">
    </div>

    <div class="form-container">
        {{-- Form gửi dữ liệu đến ProductsController@store --}}
        <form action="{{ route('admin.variants.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Tên Sản phẩm --}}
            <div class="form-group">
                <label for="productName">Tên Sản phẩm:</label>
                {{-- Dùng old() để giữ lại giá trị nếu validation thất bại --}}
                <input type="text" id="productName" name="productName" class="form-control" value="{{ old('productName') }}"
                    required>
                @error('productName')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            {{-- Giá và Tồn kho (Giả định) --}}
            <div class="form-row">
                <div class="form-group half-width">
                    <label for="price">Giá:</label>
                    <input type="number" id="price" name="price" class="form-control" value="{{ old('price') }}" required
                        min="0">
                    @error('price')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group half-width">
                    <label for="stock">Số lượng tồn kho:</label>
                    <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock') }}" required
                        min="0">
                    @error('stock')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Loại (Category) --}}
            <div class="form-group">
                <label for="categoryId">Loại Sản phẩm:</label>
                <select id="categoryId" name="categoryId" class="form-control" required>
                    <option value="">-- Chọn Loại --</option>
                    {{-- Lặp qua biến $categories được truyền từ Controller --}}
                    @foreach ($categories as $category)
                        <option value="{{ $category->categoryId }}" {{ old('categoryId') == $category->categoryId ? 'selected' : '' }}>
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
                    {{-- Lặp qua biến $brands được truyền từ Controller --}}
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->brandId }}" {{ old('brandId') == $brand->brandId ? 'selected' : '' }}>
                            {{ $brand->brandName }}
                        </option>
                    @endforeach
                </select>
                @error('brandId')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            {{-- Mô tả Sản phẩm --}}
            <div class="form-group">
                <label for="productDescription">Mô tả:</label>
                <textarea id="productDescription" name="productDescription"
                    class="form-control">{{ old('productDescription') }}</textarea>
                @error('productDescription')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            {{-- Hình ảnh --}}
            <div class="form-group">
                <label for="image">Hình ảnh:</label>
                <input type="file" id="image" name="image" class="form-control">
                @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">Thêm Sản phẩm</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection