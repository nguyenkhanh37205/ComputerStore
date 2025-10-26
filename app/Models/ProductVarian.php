<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVarian extends Model
{
    use HasFactory;

    protected $table = 'productvarians';
    protected $primaryKey = 'varianId';

    // Tắt auto created_at / updated_at vì bảng không có hai cột này
    public $timestamps = false;

    /**
     * Các thuộc tính có thể gán hàng loạt (Mass Assignable).
     * Phải khớp với tên cột trong DB.
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

    // Quan hệ với Products
    public function product()
    {
        return $this->belongsTo(Products::class, 'productId', 'productId');
    }
} 