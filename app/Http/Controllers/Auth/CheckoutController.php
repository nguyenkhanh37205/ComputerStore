<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Carts;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Nếu chưa đăng nhập thì chuyển về trang đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        // Lấy sản phẩm trong giỏ hàng của user
        $cartItems = Carts::where('userId', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // 👉 Tính tổng tiền
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        // 👉 Lấy địa chỉ mặc định từ bảng shippingAddresses
        $defaultAddress = ShippingAddress::where('userId', Auth::id())
            ->where('isDefault', true)
            ->first();

        // Nếu chưa có địa chỉ mặc định, lấy địa chỉ đầu tiên (nếu có)
        if (!$defaultAddress) {
            $defaultAddress = ShippingAddress::where('userId', Auth::id())->first();
        }

        // Trả dữ liệu cho view
        return view('auth.checkout', compact('cartItems', 'total', 'defaultAddress'));
    }
}
