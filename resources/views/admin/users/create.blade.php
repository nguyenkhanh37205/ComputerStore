@extends('admin.layout')

@section('content')
<div class="content-header">
    <h1>Thêm Mới Người Dùng</h1>
    <br> <p class="quay"><a href="{{ route('admin.users.index') }}">Danh sách người dùng</a></p>
    <link rel="stylesheet" href="{{ asset('css/add.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/png" />
</div>

<div class="form-container">
    {{-- Form gửi dữ liệu đến UsersController@store --}}
   {{-- ĐÃ SỬA: Form gửi dữ liệu đến UsersController@store --}}
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- ĐÃ XÓA @method('PUT') --}}

        <div class="form-group">
            <label for="username">Tài khoản (Username):</label>
            <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
            @error('username')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="fullName">Tên đầy đủ (Full Name):</label>
            <input type="text" id="fullName" name="fullName" class="form-control @error('fullName') is-invalid @enderror" value="{{ old('fullName') }}">
            @error('fullName')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
            @error('phone')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="role">Quyền (Role):</label>
            <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Khách hàng</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
            </select>
            @error('role')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="image">Ảnh đại diện:</label>
            <input type="file" id="image" name="image" class="form-control-file @error('image') is-invalid @enderror">
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Phần mật khẩu bắt buộc cho chức năng tạo mới --}}
        <div class="form-group">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Xác nhận Mật khẩu:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Tạo Người Dùng</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection