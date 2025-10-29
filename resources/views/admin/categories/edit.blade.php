@extends('admin.layout')

@section('content')
    <div class="content-header">
        <h1>Chỉnh Sửa Danh Mục: {{ $category->categoryName }}</h1>
        <br>
        <p class="quay"><a href="{{ route('admin.categories.index') }}">Danh sách danh mục</a></p>
        <link rel="stylesheet" href="{{ asset('css/add.css') }}">
    </div>

    <div class="form-container">
        {{-- ĐÂY LÀ FORM CHÍNH: categories.update --}}
        <form action="{{ route('admin.categories.update', $category->categoryId) }}" 
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('POST') {{-- BẮT BUỘC --}}

            {{-- Tên danh mục --}}
            <div class="form-group">
                <label for="categoryName">Tên danh mục:</label>
                <input type="text" id="categoryName" name="categoryName"
                    class="form-control @error('categoryName') is-invalid @enderror"
                    value="{{ old('categoryName', $category->categoryName) }}" required>
                @error('categoryName')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mô tả --}}
            <div class="form-group">
                <label for="categoryDescription">Mô tả:</label>
                <input type="text" id="categoryDescription" name="categoryDescription"
                    class="form-control @error('categoryDescription') is-invalid @enderror"
                    value="{{ old('categoryDescription', $category->categoryDescription) }}" required>
                @error('categoryDescription')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- LOGO / IMAGE (Đã sửa name="logo" thành name="image") --}}
            <div class="form-group">
                <label>Ảnh hiện tại:</label>
                @if ($category->image)
                    <img src="{{ asset($category->image) }}" alt="Ảnh hiện tại"
                        style="width: 100px; display: block; margin-bottom: 10px;">
                @else
                    <p>Không có ảnh.</p>
                @endif
                
                {{-- SỬA: name="logo" thành name="image" để khớp với Controller --}}
                <label for="image">Ảnh Danh mục:</label>
                <input type="file" id="image" name="image" 
                    class="form-control @error('image') is-invalid @enderror" 
                    accept="image/*">
                @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- NÚT BẤM (Đã loại bỏ Form lồng, sử dụng nút BẤM TRỰC TIẾP) --}}
            <button type="submit" class="btn btn-update">Cập nhật danh mục</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
            
        </form>
    </div>
@endsection