<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // 🚨 Phải là class này

class Orders extends Model // 🚨 Phải kế thừa trực tiếp từ Model
{
    use HasFactory;

    protected $table = 'orders'; 
    protected $primaryKey = 'orderId'; 
    
    public $timestamps = false; 
    protected $guarded = []; // Chặn gán đại trà
    
    protected $fillable = [
        'userId',
        'shippingAddressId',
        'total', 
        'paymentMethod',
        'status',
        'promotionId', 
        'userVoucherId',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'productId', 'productId');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'orderId', 'orderId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }   

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'shippingAddressId', 'addressId');
    }

    public function getOrderCodeAttribute()
    {
        return 'MTC-' . str_pad($this->orderId, 6, '0', STR_PAD_LEFT);
    }

    // 2. Accessor cho Trạng thái hiển thị (status_text)
    public function getStatusTextAttribute()
    {
        // Chuyển đổi giá trị cột 'status' sang ngôn ngữ tiếng Việt
        $statuses = [
            'Pending' => 'Đang chờ xử lý',
            'Processing' => 'Đang giao hàng',
            'Completed' => 'Đã hoàn thành',
            'Cancelled' => 'Đã hủy',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // 3. Accessor cho created_at (Nếu cột trong DB là 'createdAt', nhưng view dùng 'created_at')
    public function getCreatedAtAttribute($value) // <-- ĐÃ THÊM $value
    {
        // $value chính là giá trị raw từ cột 'createdAt' trong DB
        return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}