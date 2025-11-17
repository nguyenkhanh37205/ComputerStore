<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Orders; 
// Cần sử dụng ProductVariant nếu chưa import
use App\Models\ProductVariant; 

class OrdersController extends Controller
{
    // Các trạng thái mặc định của ENUM
    const VALID_STATUSES = ['Pending', 'Processing', 'Completed', 'Cancelled', 'Refunded'];
    
    //---------------------------------------------------------

    public function index()
    {
        // Lấy danh sách đơn hàng, kèm thông tin User
        $orders = Orders::with('user')->orderBy('createdAt', 'desc')->get(); 
        
        return view('admin.orders.index', compact('orders'));
    }

    //---------------------------------------------------------

    public function show($id)
    {
        // Lấy thông tin đơn hàng chi tiết, kèm User và Items (Đảm bảo quan hệ items hoạt động)
        $order = Orders::with(['user', 'promotion', 'items.variant.product'])
                      ->findOrFail($id); 
        
        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => self::VALID_STATUSES // Truyền hằng số
        ]);
    }
    
    public function edit($id)
    {
        $order = Orders::findOrFail($id);
        
        return view('admin.orders.edit', [
            'order' => $order,
            'statuses' => self::VALID_STATUSES // Truyền hằng số
        ]);
    }
    
    //---------------------------------------------------------

    public function update(Request $request, $id)
    {
        // 1. Validate the request data
        $request->validate([
            'status' => 'required|in:' . implode(',', self::VALID_STATUSES),
            'paymentMethod' => 'nullable|string|max:50',
        ]);

        $order = Orders::with('items.variant')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Các trạng thái Cần hoàn trả tồn kho
        $revertStatuses = ['Cancelled', 'Refunded'];
        
        // Cờ kiểm tra xem có cần hoàn trả tồn kho không
        $shouldRevertStock = 
            // Nếu trạng thái cũ KHÔNG phải là trạng thái cần hoàn trả
            !in_array($oldStatus, $revertStatuses) && 
            // Và trạng thái mới LÀ trạng thái cần hoàn trả
            in_array($newStatus, $revertStatuses);

        // Bắt đầu Transaction
        DB::beginTransaction();

        try {
            // 2. Cập nhật trạng thái đơn hàng
            $order->status = $newStatus;
            if ($request->filled('paymentMethod')) {
               $order->paymentMethod = $request->paymentMethod;
            }
            $order->save();

            // 3. Xử lý Hoàn trả Tồn kho nếu cần
            if ($shouldRevertStock) {
                // Lặp qua các chi tiết đơn hàng (order items)
                foreach ($order->items as $item) {
                    $variantId = $item->variantId;
                    $quantity = $item->quantity;

                    // Lấy biến thể (variant) bằng lockForUpdate để tránh lỗi đồng thời
                    // Sử dụng lockForUpdate() để đảm bảo tính nhất quán trong Transaction
                    $variant = Variant::lockForUpdate()->find($variantId);

                    if ($variant) {
                        // CỘNG TỒN KHO: Cộng lại số lượng đã bán vào kho
                        $variant->stock += $quantity;
                        $variant->save();
                        
                        // 💡 Tùy chọn: Ghi Log hoàn trả tồn kho nếu bạn có bảng inventoryLogs
                        // InventoryLog::create([...]); 
                    }
                }
                $message = 'Trạng thái đơn hàng đã được cập nhật thành công và Tồn kho đã được HOÀN TRẢ.';
            } else {
                $message = 'Trạng thái đơn hàng đã được cập nhật thành công.';
            }
            
            DB::commit(); // Hoàn tất giao dịch

            return redirect()->route('admin.orders.show', $order->orderId)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack(); // Hoàn tác tất cả các thay đổi
            // Log lỗi $e->getMessage()
            return redirect()->back()->with('error', 'Cập nhật trạng thái thất bại. Vui lòng kiểm tra lại. Lỗi: ' . $e->getMessage());
        }
    }

    //---------------------------------------------------------

    public function destroy($id)
    {
        $order = Orders::findOrFail($id);
        
        // 1. Hoàn trả tồn kho trước khi xóa nếu đơn hàng chưa hủy/hoàn tiền
        if (!in_array($order->status, ['Cancelled', 'Refunded'])) {
            $order->load('items.variant');
             // Bắt đầu Transaction cho việc hoàn trả và xóa
            DB::beginTransaction();
            try {
                foreach ($order->items as $item) {
                    $variant = Variant::lockForUpdate()->find($item->variantId);
                    if ($variant) {
                        $variant->stock += $item->quantity;
                        $variant->save();
                    }
                }
                
                // 2. Xóa đơn hàng và chi tiết
                $order->items()->delete(); 
                $order->delete();
                
                DB::commit();
                return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa và TỒN KHO ĐÃ HOÀN TRẢ.');

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('admin.orders.index')->with('error', 'Lỗi khi xóa đơn hàng và hoàn trả tồn kho.');
            }
        } else {
             // Chỉ xóa nếu đã ở trạng thái hủy/hoàn tiền (tồn kho đã được hoàn trả trước đó)
            $order->items()->delete(); 
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa khỏi hệ thống.');
        }
    }
}