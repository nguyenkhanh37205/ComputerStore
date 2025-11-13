<?php

namespace App\Http\Controllers;

use App\Models\Wishlists;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistsController extends Controller
{
    // Hiển thị danh sách yêu thích của người dùng đang đăng nhập
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để xem danh sách yêu thích.');
        }

        $wishlistItems = Wishlists::with('product')
            ->where('userId', Auth::id())
            ->get();

        return view('frontend.wishlist.index', compact('wishlistItems'));
    }

    // Thêm sản phẩm vào danh sách yêu thích
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập để thêm vào danh sách yêu thích.'], 401);
        }

        $request->validate([
            'productId' => 'required|exists:products,productId',
        ]);

        $userId = Auth::id();
        $productId = $request->productId;

        // Kiểm tra trùng lặp (duy nhất theo userId và productId)
        $existingItem = Wishlists::where('userId', $userId)
            ->where('productId', $productId)
            ->first();

        if ($existingItem) {
            return redirect()->route('wishlist.index')->with('warning', 'Sản phẩm đã có trong danh sách yêu thích.');
        }

        Wishlists::create([
            'userId' => $userId,
            'productId' => $productId,
        ]);

        return redirect()->route('wishlist.index')->with('success', 'Sản phẩm đã được thêm vào danh sách yêu thích.');
    }

    // Xóa sản phẩm khỏi danh sách yêu thích
    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập.'], 401);
        }

        // Xóa dựa trên wishlistId và đảm bảo thuộc về user đang đăng nhập
        $wishlistItem = Wishlists::where('wishlistId', $id)
            ->where('userId', Auth::id())
            ->firstOrFail();

        $wishlistItem->delete();

        return redirect()->route('wishlist.index')->with('success', 'Sản phẩm đã được xóa khỏi danh sách yêu thích.');
    }

    // Xóa sản phẩm yêu thích bằng AJAX
    public function destroyAjax($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập'], 401);
        }

        $wishlistItem = Wishlists::where('wishlistId', $id)
            ->where('userId', Auth::id())
            ->first();

        if (!$wishlistItem) {
            return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
        }

        $wishlistItem->delete();

        return response()->json(['success' => 'Đã xóa sản phẩm khỏi danh sách yêu thích', 'id' => $id]);
    }

}