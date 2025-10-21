<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <style>
    :root {
        --accent: #0d6efd;
        --bg-start: #eef2ff;
        --bg-end: #f8fafc;
        --card-bg: rgba(255, 255, 255, 0.95);
        --text-color: #333;
        --border-color: #d1d5db;
    }

    html,
    body {
        height: 100%;
        background: linear-gradient(135deg, var(--bg-start) 0%, var(--bg-end) 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    .main-header {
        padding: 16px 0;
        text-align: center;
    }

    .login-wrapper {
        min-height: calc(100vh - 96px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .login-card {
        width: 100%;
        max-width: 420px;
        background: var(--card-bg);
        border-radius: 12px;
        padding: 28px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border-color);
        transition: transform 0.3s;
    }

    .login-card:hover {
        transform: translateY(-5px);
    }

    .login-card h2 {
        margin-bottom: 18px;
        font-weight: 700;
        color: var(--text-color);
        text-align: center;
    }

    .form-label {
        font-size: 0.95rem;
        color: var(--text-color);
        display: block;
        margin-bottom: 8px;
    }

    .form-control {
        height: 46px;
        border-radius: 8px;
        padding: 0.6rem .75rem;
        border: 1px solid var(--border-color);
        width: 100%;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: var(--accent);
        outline: none;
        box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
    }

    .btn-primary {
        background: linear-gradient(90deg, var(--accent), #2563eb);
        border: none;
        height: 46px;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        color: white;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
    }

    .btn-primary:hover {
        background: #0056b3;
        transform: scale(1.02);
    }

    .help-text {
        font-size: 0.9rem;
        color: #64748b;
        text-align: center;
        margin-top: 12px;
    }

    .alert {
        border-radius: 8px;
        font-size: 0.95rem;
        color: #721c24;
        background-color: #f8d7da;
        padding: 10px;
        margin-top: 10px;
    }
    </style>
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
                    <label for="password" class="form-label">Mật Khẩu</label>
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