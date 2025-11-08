<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $redirectTo = '/';

    public function __construct()
    {
        // Chỉ cho khách truy cập vào trang login, trừ khi họ đang đăng nhập
        $this->middleware('guest')->except('logout');
    }

    /** 
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('client.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        // Kiểm tra dữ liệu nhập
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4',
        ]);

        // Thử đăng nhập
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // Phân quyền
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->intended('/')->with('success', 'Đăng nhập thành công!');
            }
        }

        // Sai thông tin
        throw ValidationException::withMessages([
            // 'email' => ['Email hoặc mật khẩu không đúng.'],
            'password' => ['Email hoặc mật khẩu không đúng.'],
        ]);
    }

    /**
     * Đăng xuất người dùng
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đăng xuất thành công!');
    }
}