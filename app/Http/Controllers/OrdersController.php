<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders; 

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
        // Lấy thông tin đơn hàng chi tiết, kèm User và Items
        $order = Orders::with(['user', 'promotion', 'items.variant.product'])
                      ->findOrFail($id); 
        
        // SỬA CÚ PHÁP TRUYỀN DỮ LIỆU VÀO VIEW TẠI ĐÂY
        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => self::VALID_STATUSES // Truyền hằng số
        ]);
    }
    
    public function edit($id)
    {
        $order = Orders::findOrFail($id);
        
        // SỬA CÚ PHÁP TRUYỀN DỮ LIỆU VÀO VIEW TẠI ĐÂY
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
            // Các trường khác chỉ nên được chỉnh sửa tự động hoặc qua quá trình phức tạp hơn
        ]);

        $order = Orders::findOrFail($id);
        
        // 2. Cập nhật trạng thái đơn hàng
        $order->status = $request->status;
        if ($request->filled('paymentMethod')) {
             $order->paymentMethod = $request->paymentMethod;
        }
        $order->save();

        return redirect()->route('admin.orders.show', $order->orderId)->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công.');
    }

    //---------------------------------------------------------

    public function destroy($id)
    {
        $order = Orders::findOrFail($id);
        
        // Cân nhắc: Đơn hàng thường không được xóa hẳn mà chỉ chuyển trạng thái (Cancelled/Refunded)
        // Nếu thực sự muốn xóa:
        
        // 1. Xóa tất cả Order Items liên quan (nếu bạn tạo bảng OrderItem)
        // $order->items()->delete(); 
        
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa khỏi hệ thống (KHÔNG NÊN LÀM TRONG THỰC TẾ).');
    }
}