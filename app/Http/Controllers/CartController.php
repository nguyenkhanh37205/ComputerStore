<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ✅ Hiển thị giỏ hàng (phân quyền rõ ràng)
    //Phía người dùng
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }

        $userId = Auth::user()->userId;

        // Lấy giỏ hàng của user hiện tại
        $cartItems = Carts::where('userId', $userId)
            ->with(['user', 'variant.product'])
            ->get();

        // Tính tổng tiền tạm thời (Subtotal)
        $subtotal = $cartItems->sum(function ($item) {
            // Đảm bảo truy cập an toàn (Null Coalescing Operator ?? 0)
            return $item->quantity * ($item->variant->price ?? 0);
        });

        // Trả về view của người dùng
        return view('auth.cart', compact('cartItems', 'subtotal'));
    }

    //phía admin
    public function indexAdmin()
    {
        // 1. Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        // 2. Kiểm tra phân quyền (Nếu route chưa dùng middleware 'admin')
        if ($user->role !== 'admin') {
            // Chuyển hướng người dùng không phải Admin
            return redirect('/')->with('error', 'Bạn không có quyền truy cập trang quản trị giỏ hàng.');
        }

        // Lấy TẤT CẢ giỏ hàng
        $cartItems = Carts::with(['user', 'variant.product'])
            ->orderBy('createdAt', 'desc')
            ->get();

        // Trả về view của Admin
        return view('admin.cart.index', compact('cartItems'));
    }

    // ✅ Thêm sản phẩm vào giỏ hàng
    public function store(Request $request)
    {
        // dd($request->all());
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để thêm vào giỏ.');
        }

        $request->validate([
            // Đã sửa thành variant_id để khớp với input hidden trong Form
            'variant_id' => 'nullable|integer|exists:productvariants,variantId',
            'quantity' => 'required|integer|min:1',
        ]);

        // dd($request->all());

        $userId = Auth::user()->userId;
        // Đã sửa để lấy giá trị variant_id (có gạch dưới)
        $variantId = $request->variant_id;
        $quantity = $request->quantity;

        if (!$variantId) {
            return back()->with('error', 'Sản phẩm này chưa có phiên bản cụ thể.');
        }

        $cartItem = Carts::where('userId', $userId)
            ->where('variantId', $variantId)
            ->first();

        if ($cartItem) {
            // Nếu đã có sản phẩm thì cộng dồn số lượng
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Nếu chưa có thì thêm mới
            // Lưu ý: Tên cột trong DB vẫn là 'variantId' (theo chuẩn Model Carts)
            Carts::create([
                'userId' => $userId,
                'variantId' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // ✅ Cập nhật số lượng
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để cập nhật giỏ hàng.');
        }

        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'quantities' => 'required|array',
        ]);

        $userId = Auth::user()->userId;

        foreach ($request->quantities as $cartId => $quantity) {
            if ($quantity < 1)
                continue; // bỏ qua nếu số lượng không hợp lệ

            $cartItem = Carts::where('cartId', $cartId)
                ->where('userId', $userId)
                ->first();

            if ($cartItem) {
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }
        }

        return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
    }


    // ✅ Xóa sản phẩm khỏi giỏ hàng
    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Bạn cần đăng nhập');
        }

        $cartItem = Carts::where('cartId', $id)
            ->where('userId', Auth::user()->userId)
            ->firstOrFail();

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa khỏi giỏ hàng');
    }

    public function updateQuantity(Request $request)
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Chưa đăng nhập']);
        }

        $cartId = $request->input('cartId');
        $quantity = (int) $request->input('quantity');

        if ($quantity < 1) {
            return response()->json(['success' => false, 'message' => 'Số lượng không hợp lệ']);
        }

        $cartItem = \App\Models\Carts::where('cartId', $cartId)
            ->where('userId', \Illuminate\Support\Facades\Auth::user()->userId)
            ->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ']);
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return response()->json(['success' => true]);
    }


}