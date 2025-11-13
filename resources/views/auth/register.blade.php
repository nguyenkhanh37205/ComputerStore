<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth-css/register.css') }}">
</head>

<body>
    <header class="main-header">
        <div class="container mt-5">
            <h1 class="text-center">Đăng ký tài khoản</h1>
            <form action="{{ route('register.post') }}" method="POST" class="mt-4">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại:</label>
                    <input type="text" id="phone" name="phone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ:</label>
                    <div class="address-group">
                        <select id="province" class="form-select">
                            <option value="">Chọn tỉnh</option>
                        </select>
                        <select id="district" class="form-select">
                            <option value="">Chọn quận</option>
                        </select>
                        <select id="ward" class="form-select">
                            <option value="">Chọn phường</option>
                        </select>
                    </div>
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

            <p class="text-center mt-3">Đã có tài khoản?
                <a href="{{ route('login.form') }}">Đăng nhập</a>
            </p>
        </div>
    </header>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script tải địa chỉ --}}
    <script src="{{ asset('assets/js/register.js') }}"></script>
</body>

</html>