<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Tên bảng là 'categories'
    protected $primaryKey = 'categoryId'; // Khóa chính là 'categoryId'

    // Tắt timestamps vì bạn không có cột created_at/updated_at trong bảng Categories
    public $timestamps = false; 

    /**
     * Các thuộc tính có thể gán hàng loạt.
     */
    protected $fillable = [
        'categoryName',
        'categoryDescription',
    ];

    /**
     * Định nghĩa quan hệ (Relationship)
     * Quan hệ: Một Category có nhiều Products
     */
    public function products()
    {
        // Khóa ngoại trong bảng products là categoryId, khóa chính của Categories là categoryId
        return $this->hasMany(Product::class, 'categoryId', 'categoryId');
    }
}