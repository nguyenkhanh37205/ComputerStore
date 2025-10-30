<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variants extends Model
{
    use HasFactory;

    protected $table = 'productvariants'; 
    protected $primaryKey = 'variantId'; 

    // Tắt timestamps vì bạn không có cột created_at/updated_at
    public $timestamps = false; 

    /**
     * Các thuộc tính có thể gán hàng loạt.
     */
    protected $fillable = [
        'productId',
        'ram',
        'rom',
        'color',
        'price',
        'stock',
        'image',
    ];

    /**
     * Các thuộc tính được tự động chuyển sang kiểu dữ liệu cụ thể.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Định nghĩa quan hệ (Relationship)
     * Quan hệ: Variant thuộc về một Product
     */
    public function product()
    {
        // Khóa ngoại là productId (trong bảng variants), khóa chính của Product là productId
        return $this->belongsTo(Products::class, 'productId', 'productId'); 
    }
    
    /**
     * Quan hệ: Variant có thể có trong nhiều Carts (Giỏ hàng)
     */
    public function carts()
    {
        // Khóa ngoại là variantId (trong bảng carts), khóa chính của Variant là variantId
        return $this->hasMany(Cart::class, 'variantId', 'variantId');
    }
}