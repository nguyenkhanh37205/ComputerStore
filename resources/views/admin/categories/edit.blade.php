@extends('admin.layout')

@section('content')
    <div class="content-header">
        <h1>Chỉnh Sửa Danh Mục: {{ $category->categoryName }}</h1>
        <br>
        <p class="quay"><a href="{{ route('admin.categories.index') }}">Danh sách danh mục</a></p>
        <link rel="stylesheet" href="{{ asset('css/add.css') }}">
    </div>

    <div class="form-container">
        {{-- Form gửi dữ liệu đến CategoriesController@update --}}
        <form action="{{ route('admin.categories.update', $category->categoryId) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Bắt buộc phải có @method('PUT') cho hành động update --}}

            <div class="form-group">
                <label for="categoryName">Tên danh mục:</label>
                <input type="text" id="categoryName" name="categoryName"
                    class="form-control @error('categoryName') is-invalid @enderror"
                    value="{{ old('categoryName', $category->categoryName) }}" required>
                @error('categoryName')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoryDescription">Mô tả:</label>
                <input type="text" id="categoryDescription" name="categoryDescription"
                    class="form-control @error('categoryDescription') is-invalid @enderror"
                    value="{{ old('categoryDescription', $category->categoryDescription) }}" required>
                @error('categoryDescription')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật Danh Mục</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection