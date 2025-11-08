<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/client-css/login.css') }}">
    <title>Đăng Nhập</title>
</head>

<body>
    <header class="main-header">
        <div class="header-top">
            <a href="/" class="logo">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo MTC" style="height: 56px;">
            </a>
        </div>
    </header>

    <div class="login-wrapper">
        <div class="login-card">
            <h2>Đăng Nhập</h2>
            <form action="{{ route('login.post') }}" method="POST" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input value="{{ old('email') }}" type="email" name="email" id="email" class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Đăng Nhập</button>
            </form>

            @if ($errors->any())
            <div class="alert" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="help-text">
                Chưa có tài khoản? <a href="{{ route('register.form') }}">Đăng ký</a>
            </div>
        </div>
    </div>
</body>

</html>