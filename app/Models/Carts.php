<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    use HasFactory;

    protected $table = 'cart'; 
    protected $primaryKey = 'cartId';
    public $timestamps = false;

    protected $fillable = [
        'userId',
        'variantId',
        'quantity',
    ];

    // Cart thuộc 1 user
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

    // Cart thuộc 1 biến thể sản phẩm
    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variantId', 'variantId');
    }

    // Giúp lấy trực tiếp tên sản phẩm
    public function product()
    {
        return $this->hasOneThrough(
            Products::class,
            Variant::class,
            'variantId',
            'productId',
            'variantId',
            'productId'
        );
    }
}