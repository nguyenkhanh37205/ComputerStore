@extends('auth.home')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="mb-4 text-center text-primary">Đổi Email</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('change.email.post') }}">
        @csrf
        <div class="mb-3">
            <label>Email mới</label>
            <input type="email" name="new_email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Gửi mã xác minh</button>
    </form>

    @if (session('email_otp'))
    <form method="POST" action="{{ route('verify.email.otp') }}" class="mt-4">
        @csrf
        <div class="mb-3">
            <label>Nhập mã OTP:</label>
            <input type="text" name="otp" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Xác nhận đổi email</button>
    </form>
    @endif
</div>
@endsection
