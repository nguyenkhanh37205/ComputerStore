@extends('admin.layout')

@section('content')
<div class="content-header">
    <h1>Thêm Mới Danh Mục</h1>
    <br> <p class="quay"><a href="{{ route('admin.categories.index') }}">Danh sách danh mục</a></p>
    <link rel="stylesheet" href="{{ asset('css/add.css') }}">
</div>

<div class="form-container">
    {{-- Form gửi dữ liệu đến CategoriesController@store --}}
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- ĐÃ XÓA @method('PUT') --}}

        <div class="form-group">
            <label for="categoryName">Tên danh mục:</label>
            <input type="text" id="categoryName" name="categoryName" class="form-control @error('categoryName') is-invalid @enderror" value="{{ old('categoryName') }}" required>
            @error('categoryName')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group" >
            <label for="logo">Hình Ảnh</label>
            <input type="file" id="logo" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
            @error('logo')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="categoryDescription">Mô tả:</label>
            <input type="text" id="categoryDescription" name="categoryDescription" class="form-control @error('categoryDescription') is-invalid @enderror" value="{{ old('categoryDescription') }}" required>
            @error('categoryDescription')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
       

        <button type="submit" class="btn btn-primary">Tạo Danh Mục</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection