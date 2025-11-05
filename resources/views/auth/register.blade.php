<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
</head>

<body>
    <header class="main-header">
        <div class="container mt-5">
            <h1 class="text-center">Đăng ký tài khoản</h1>
            <form action="{{ route('register.post') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                        required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Đăng ký</button>
                </div>
            </form>
            <p class="text-center mt-3">Đã có tài khoản? <a href="{{ route('login.form') }}">Đăng nhập</a></p>
        </div>
    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>