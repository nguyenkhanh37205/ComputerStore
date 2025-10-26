<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    use HasFactory;

    protected $table = 'brands'; // Tên bảng là 'brands'
    protected $primaryKey = 'brandId'; // Khóa chính là 'brandId'

    // Tắt timestamps vì bạn không có cột created_at/updated_at trong bảng Brands
    public $timestamps = false; 

    /**
     * Các thuộc tính có thể gán hàng loạt.
     */
    protected $fillable = [
        'brandName',
        'brandDescription',
        'logo',
    ];

    /**
     * Định nghĩa quan hệ (Relationship)
     * Quan hệ: Một Brand có nhiều Products
     */
    public function products()
    {
        // Khóa ngoại trong bảng products là brandId, khóa chính của Brands là brandId
        return $this->hasMany(Product::class, 'brandId', 'brandId');
    }
}