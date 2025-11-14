@extends('admin.layout')

@section('content')
<div class="content-header">
    <h1>Thêm Mới Thương Hiệu</h1>
    <br> <p class="quay"><a href="{{ route('admin.brands.index') }}">Danh sách thương hiệu</a></p>
    <link rel="stylesheet" href="{{ asset('assets/css/admin-css/add.css') }}">
</div>

<div class="form-container">
    
    {{-- Form gửi dữ liệu đến BrandsController@store --}}
    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- ĐÃ XÓA @method('PUT') --}}

        <div class="form-group">
            <label for="brandName">Tên thương hiệu:</label>
            <input type="text" id="brandName" name="brandName" class="form-control @error('brandName') is-invalid @enderror" value="{{ old('brandName') }}" required>
            @error('brandName')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="brandDescription">Mô tả:</label>
            <input type="text" id="brandDescription" name="brandDescription" class="form-control @error('brandDescription') is-invalid @enderror" value="{{ old('brandDescription') }}" required>
            @error('brandDescription')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="logo">Logo:</label>
            <input type="file" id="logo" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
            @error('logo')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary" enctype="multipart/form-data">Tạo Thương Hiệu</button>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection