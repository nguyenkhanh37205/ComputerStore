<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shippingAddresses'; // Tên bảng đúng trong CSDL
    protected $primaryKey = 'addressId';
    public $timestamps = false;

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
