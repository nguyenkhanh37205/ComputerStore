<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Carts;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use App\Models\Orders;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        // Nếu chưa đăng nhập thì chuyển về trang đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy sản phẩm trong giỏ hàng của user
        $cartItems = Carts::where('userId', $userId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Tính tổng tiền
        $total = 0;
        foreach ($cartItems as $item) {
            $price = $item->product->price ?? 0;
            $total += $price * $item->quantity;
        }

        // Lấy TẤT CẢ địa chỉ đã lưu của user
        $userAddresses = ShippingAddress::where('userId', $userId)->get();

        // Lấy địa chỉ mặc định
        $defaultAddress = $userAddresses->where('isDefault', true)->first();

        // Nếu không có địa chỉ mặc định, lấy địa chỉ đầu tiên (nếu có)
        if (!$defaultAddress) {
            $defaultAddress = $userAddresses->first();
        }

        // Trả dữ liệu cho view
        return view('auth.checkout', compact('cartItems', 'total', 'defaultAddress', 'userAddresses'));
    }

    // ...
    public function placeOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để hoàn tất đơn hàng.');
        }

        $userId = Auth::id();

        DB::beginTransaction();
        try {
            $addressId = $request->input('address_id');
            $shippingAddressIdToUse = null;

            // 2. Xử lý Địa chỉ Giao hàng
            if ($addressId == '0') {
                ShippingAddress::where('userId', $userId)->update(['isDefault' => false]);

                $newAddressData = [
                    'userId' => $userId,
                    'recipientName' => $request->input('newRecipientName'),
                    'phone' => $request->input('newPhone'),
                    'addressLine' => $request->input('newAddressLine'),
                    'ward' => $request->input('newWard'),
                    'district' => $request->input('newDistrict'),
                    'city' => $request->input('newCity'),
                    'isDefault' => true,
                ];

                if (empty($newAddressData['recipientName']) || empty($newAddressData['phone']) || empty($newAddressData['city']) || empty($newAddressData['addressLine'])) {
                    throw new \Exception("Thiếu thông tin bắt buộc khi tạo địa chỉ mới.");
                }

                $newAddress = ShippingAddress::create($newAddressData);
                $shippingAddressIdToUse = $newAddress->addressId;

            } else {
                $shippingAddressIdToUse = $addressId;
            }

            // 3. Xử lý Giỏ hàng và Tổng tiền
            $cartItems = Carts::where('userId', $userId)->with('variant')->get();
            $totalAmount = 0;

            if ($cartItems->isEmpty()) {
                DB::rollBack();
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống. Không thể đặt hàng.');
            }

            foreach ($cartItems as $item) {
                $price = $item->variant->price ?? null;
                if (is_null($price)) {
                    throw new \Exception("Sản phẩm không có giá hoặc không tìm thấy Biến thể (Cart ID: " . $item->cartId . ")");
                }
                $totalAmount += (float) $price * $item->quantity;
            }

            // 4. TẠO ĐƠN HÀNG
            $order = Orders::create([
                'userId' => $userId,
                'shippingAddressId' => $shippingAddressIdToUse,
                'total' => $totalAmount, // 🚨 ĐÃ SỬA: Dùng key 'total' thay vì 'totalAmount'
                'paymentMethod' => $request->input('payment'),
                'status' => 'Pending',
            ]);

            // 5. Tạo Chi tiết Đơn hàng (OrderDetails) và xóa CartItems
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'orderId' => $order->orderId,
                    'variantId' => $item->variantId, // Đã sửa tên cột
                    'quantity' => $item->quantity,
                    'price' => (float) ($item->variant->price ?? 0),
                ]);
            }

            // Xóa tất cả sản phẩm trong giỏ hàng
            Carts::where('userId', $userId)->delete();

            DB::commit();

            return redirect()->route('order.success', ['id' => $order->orderId])
                ->with('success', 'Đơn hàng của bạn đã được đặt thành công! Mã đơn hàng: ' . $order->orderId);

        } catch (\Exception $e) {
            // DB::rollBack();
            // // DỪNG VÀ IN LỖI CHI TIẾT
            // dd([
            //     'Lỗi Backend Nghiêm Trọng' => $e->getMessage(),
            //     'File' => $e->getFile(),
            //     'Line' => $e->getLine(),
            //     'AddressId' => $request->input('address_id'),
            //     'NewAddressData' => isset($newAddressData) ? $newAddressData : 'N/A',
            //     'Fix_Steps_Applied' => 'Ensure all Models have correct $table and $fillable properties.',
            // ]);

            // return redirect()->back()->with('error', 'Lỗi đặt hàng không xác định. Vui lòng thử lại.');
        }
    }
    //...
}