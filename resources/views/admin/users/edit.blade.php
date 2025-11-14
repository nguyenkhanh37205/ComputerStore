@extends('admin.layout')

@section('content')
<div class="content-header">
    <h1>Chỉnh Sửa Người Dùng: {{ $user->username }}</h1>
    <br> <p class="quay"><a href="{{ route('admin.users.index') }}">Danh sách người dùng</a></p>
    <link rel="stylesheet" href="{{ asset('assets/css/admin-css/add.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/png" />
</div>

<div class="form-container">
    {{-- Form gửi dữ liệu đến UsersController@update --}}
    <form action="{{ route('admin.users.update', $user->userId) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST') {{-- Bắt buộc phải có @method('PUT') cho hành động update --}}

        <div class="form-group">
            <label for="username">Tài khoản (Username):</label>
            {{-- Dùng $user->username thay vì old() để hiển thị giá trị hiện tại --}}
            <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
            @error('username')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="fullName">Tên đầy đủ (Full Name):</label>
            <input type="text" id="fullName" name="fullName" class="form-control @error('fullName') is-invalid @enderror" value="{{ old('fullName', $user->fullName) }}">
            @error('fullName')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
            @error('phone')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="role">Quyền (Role):</label>
            <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Khách hàng</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
            </select>
            @error('role')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Ảnh đại diện hiện tại:</label>
            @if ($user->image)
                 <img src="{{ asset('storage/' . $user->image) }}" alt="Ảnh đại diện" style="width: 100px; height: 100px; object-fit: cover; margin-bottom: 10px;">
            @else
                 <p>Chưa có ảnh đại diện.</p>
            @endif
            <label for="image">Thay đổi Ảnh đại diện (Chọn file mới):</label>
            <input type="file" id="image" name="image" class="form-control-file @error('image') is-invalid @enderror">
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Phần mật khẩu là TÙY CHỌN cho chức năng chỉnh sửa --}}
        {{-- <div class="form-group">
            <label for="password">Mật khẩu (Để trống nếu không muốn thay đổi):</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Xác nhận Mật khẩu:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div> --}}

            
            <button type="submit" class="btn btn-update">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
        
    </form>
</div>
@endsection