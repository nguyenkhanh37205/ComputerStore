<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model 
{
    use HasFactory;

    // Tên bảng: shippingAddresses
    protected $table = 'shippingAddresses'; 
    protected $primaryKey = 'addressId'; 
    public $timestamps = false; // Bảng này không có created_at/updated_at

    // CÁC CỘT ĐƯỢC PHÉP LƯU (QUAN TRỌNG KHI TẠO ĐỊA CHỈ MỚI)
    protected $fillable = [
        'userId',
        'recipientName',
        'phone',
        'addressLine',
        'city',
        'district',
        'ward',
        'isDefault',
    ];
}