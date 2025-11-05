<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function indexadmin()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }

        $user = Auth::user();
        $userId = $user->userId; 
        $role = $user->role;

        if ($role === 'admin') {
            $cartItems = Cart::with(['user', 'variant.product'])
                             ->orderBy('createdAt', 'desc')
                             ->get();

            return view('admin.cart.index', compact('cartItems'));
        }

        $cartItems = Cart::where('userId', $userId)
                         ->with(['user', 'variant.product'])
                         ->get();

        $subtotal = $cartItems->sum(fn($item) => $item->quantity * ($item->variant->price ?? 0));

        return view('auth.cart.index', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để thêm vào giỏ.');
        }

        $request->validate([
            'variantId' => 'required|integer|exists:variants,variantId',
            'quantity'  => 'required|integer|min:1',
        ]);

        $userId = Auth::user()->userId;
        $variantId = $request->variantId;
        $quantity = $request->quantity;

        $cartItem = Cart::where('userId', $userId)
                        ->where('variantId', $variantId)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'userId' => $userId,
                'variantId' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập'], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('cartId', $id)
                        ->where('userId', Auth::user()->userId)
                        ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['success' => 'Cập nhật thành công']);
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Bạn cần đăng nhập');
        }

        $cartItem = Cart::where('cartId', $id)
                        ->where('userId', Auth::user()->userId)
                        ->firstOrFail();

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa khỏi giỏ hàng');
    }
}