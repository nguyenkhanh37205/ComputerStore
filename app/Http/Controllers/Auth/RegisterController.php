<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct()
    {
        // Chỉ cho khách chưa đăng nhập vào trang đăng ký
        $this->middleware('guest');
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegistrationForm(): View
    {
        return view('client.register');
    }

    /**
     * Xử lý đăng ký tài khoản
     */
    public function register(Request $request): RedirectResponse
    {
        // Kiểm tra dữ liệu nhập vào
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Tạo người dùng mới (role cố định là 'customer')
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'image' => null,
        ]);


        // Gọi event Registered (dành cho xác thực email, nếu có)
        event(new Registered($user));

        // Đăng nhập luôn sau khi đăng ký
        Auth::login($user);
        $request->session()->regenerate();

        // Phân quyền: admin hay user
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Đăng ký thành công (Admin)!');
        }

        return redirect('/')->with('success', 'Đăng ký thành công!');
    }
}