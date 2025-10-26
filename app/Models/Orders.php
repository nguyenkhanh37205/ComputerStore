<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders'; 
    protected $primaryKey = 'orderId'; 

    // Laravel mặc định tìm 'updated_at', nếu không có, cần tắt timestamps
    public $timestamps = false; 
    const CREATED_AT = 'createdAt'; // Khai báo tên cột created_at

    /**
     * Các thuộc tính có thể gán hàng loạt.
     */
    protected $fillable = [
        'userId',
        'productId',
        'promotionId',
        'userVoucherId',
        'status',
        'total',
        'paymentMethod',
        'shippingAddressId',
    ];

    /**
     * Các thuộc tính được tự động chuyển sang kiểu dữ liệu cụ thể.
     */
    protected $casts = [
        'total' => 'decimal:2',
        'status' => 'string', // Enum được xử lý như string
    ];

    /**
     * Định nghĩa quan hệ (Relationships)
     */
    
    // Đơn hàng thuộc về một User
    public function user()
    {
        return $this->belongsTo(Users::class, 'userId', 'userId'); 
    }

    // Đơn hàng có thể áp dụng một Promotion
    // public function promotion()
    // {
    //     return $this->belongsTo(Promotion::class, 'promotionId', 'promotionId');
    // }
    
    // Đơn hàng có thể áp dụng một Voucher của User (Giả định có Model UserVoucher)
    // public function userVoucher()
    // {
    //     return $this->belongsTo(UserVoucher::class, 'userVoucherId', 'userVoucherId');
    // }
    
    // Đơn hàng có một Shipping Address (Giả định có Model ShippingAddress)
    // public function shippingAddress()
    // {
    //     return $this->belongsTo(ShippingAddress::class, 'shippingAddressId', 'shippingAddressId');
    // }
    
    // Đơn hàng có nhiều chi tiết đơn hàng (Order Items)
//     public function items()
//     {
//         return $this->hasMany(OrderItem::class, 'orderId', 'orderId');
//     }
}