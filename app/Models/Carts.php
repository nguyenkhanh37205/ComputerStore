<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'cartId';

    // Bảng không có updated_at -> tắt timestamps
    public $timestamps = false;

    protected $fillable = [
        'userId',
        'variantId',
        'quantity',
    ];

    // Quan hệ: cart thuộc 1 user
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

    // Quan hệ: cart thuộc một variant sản phẩm
    public function variant()
    {
        return $this->belongsTo(ProductVarian::class, 'variantId', 'variantId');
    }
}