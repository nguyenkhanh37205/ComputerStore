<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    use HasFactory;

    protected $table = 'carts'; // Tên bảng là 'carts'
    protected $primaryKey = 'cartId'; // Khóa chính là 'cartId'

    // Laravel mặc định tìm 'updated_at', nếu không có, cần tắt timestamps
    public $timestamps = false; 
    const CREATED_AT = 'createdAt'; // Khai báo tên cột created_at

    /**
     * Các thuộc tính có thể gán hàng loạt.
     */
    protected $fillable = [
        'userId',
        'variantId',
        'quantity',
    ];
    
    /**
     * Định nghĩa quan hệ (Relationship)
     * Quan hệ: Cart thuộc về một User
     */
    public function user()
    {
        // Khóa ngoại là userId, khóa chính của User là userId
        return $this->belongsTo(Users::class, 'userId', 'userId'); 
    }

    /**
     * Quan hệ: Cart chứa một Product Variant (Biến thể sản phẩm)
     * *Lưu ý: Bạn cần phải có Model Variant hoặc tên tương tự*
     */
    public function variant()
    {
        // Khóa ngoại là variantId, khóa chính của Variant là variantId (giả định)
        return $this->belongsTo(Variants::class, 'variantId', 'variantId'); 
    }
}