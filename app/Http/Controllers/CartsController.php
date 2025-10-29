<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Để lấy userId của người dùng đang đăng nhập

class CartsController extends Controller
{
public function indexadmin()
    {
        // 1. Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }

        $user = Auth::user();
        $userId = $user->userId; // Giả định khóa chính là userId
        $role = $user->role; // Lấy vai trò (role) từ Model User

        // 2. Lấy dữ liệu dựa trên vai trò
        if ($role === 'admin') {
            // 🟢 ADMIN: Lấy tất cả giỏ hàng của mọi người dùng
            $cartItems = Carts::with(['user', 'variant.product'])
                             ->orderBy('userId', 'desc')
                             ->get();
            
            // Trả về view Admin
            return view('admin.cart.index', compact('cartItems'));

        } else {
            // 🟡 CUSTOMER: Chỉ lấy giỏ hàng của người dùng hiện tại
            $cartItems = Carts::where('userId', $userId)
                             ->with(['user', 'variant.product'])
                             ->get();
            
            // Tính tổng tiền cho người dùng (Frontend)
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * ($item->variant->price ?? 0);
            });
            
            // Trả về view Frontend
            return view('client.cart.index', compact('cartItems', 'subtotal'));
        }
    }

    //---------------------------------------------------------

    // Hàm thêm sản phẩm vào giỏ hàng
    public function store(Request $request)
    {
        // Giả định người dùng phải đăng nhập
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.'], 401);
        }

        $request->validate([
            'variantId' => 'required|integer|exists:variants,variantId', 
            'quantity' => 'required|integer|min:1', 
        ]);

        $userId = Auth::id();
        $variantId = $request->variantId;
        $quantity = $request->quantity;

        // 1. Kiểm tra xem sản phẩm này đã có trong giỏ hàng chưa
        $cartItem = Carts::where('userId', $userId)
                        ->where('variantId', $variantId)
                        ->first();

        if ($cartItem) {
            // Nếu có, cộng thêm số lượng
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Nếu chưa, tạo item mới
            Carts::create([
                'userId' => $userId,
                'variantId' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng thành công.');
    }

    //---------------------------------------------------------

    // Hàm cập nhật số lượng
    public function update(Request $request, $id)
    {
        // Giả định người dùng phải đăng nhập
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập.'], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1', 
        ]);

        $cartItem = Carts::where('cartId', $id)
                        ->where('userId', Auth::id()) // Đảm bảo chỉ user của mình mới được sửa
                        ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['success' => 'Cập nhật số lượng thành công.']);
    }

    //---------------------------------------------------------

    // Hàm xóa một item khỏi giỏ hàng
    public function destroy($id)
    {
        // Giả định người dùng phải đăng nhập
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập.'], 401);
        }
        
        $cartItem = Carts::where('cartId', $id)
                        ->where('userId', Auth::id()) // Đảm bảo chỉ user của mình mới được xóa
                        ->firstOrFail();

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }
}