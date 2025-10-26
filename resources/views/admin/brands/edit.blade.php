@extends('admin.layout')

@section('content')
    <div class="content-header">
        <h1>Chỉnh Sửa Thương Hiệu: {{ $brand->brandName }}</h1>
        <br>
        <p class="quay"><a href="{{ route('admin.brands.index') }}">Danh sách thương hiệu</a></p>
        <link rel="stylesheet" href="{{ asset('css/add.css') }}">
    </div>

    <div class="form-container">
        {{-- Form gửi dữ liệu đến BrandsController@update --}}
        <form action="{{ route('admin.brands.update', $brand->brandId) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Bắt buộc phải có @method('PUT') cho hành động update --}}

            <div class="form-group">
                <label for="brandName">Tên thương hiệu:</label>
                <input type="text" id="brandName" name="brandName"
                    class="form-control @error('brandName') is-invalid @enderror"
                    value="{{ old('brandName', $brand->brandName) }}" required>
                @error('brandName')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="brandDescription">Mô tả:</label>
                <input type="text" id="brandDescription" name="brandDescription"
                    class="form-control @error('brandDescription') is-invalid @enderror"
                    value="{{ old('brandDescription', $brand->brandDescription) }}" required>
                @error('brandDescription')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="logo">Logo:</label>
                <input type="file" id="logo" name="logo" class="form-control @error('logo') is-invalid @enderror"
                    accept="image/*">
                @error('logo')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật Thương Hiệu</button>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection


