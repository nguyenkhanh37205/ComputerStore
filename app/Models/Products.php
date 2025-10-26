<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products'; // Tên bảng là 'products'
    protected $primaryKey = 'productId'; // Khóa chính là 'productId'

    // Laravel mặc định tìm 'updated_at', nếu không có, cần tắt timestamps
    public $timestamps = false; // Bỏ auto created_at / updated_at
    const CREATED_AT = 'createdAt'; // Khai báo tên cột created_at

    /**
     * Các thuộc tính có thể gán hàng loạt (Mass Assignable).
     * Phải khớp với tên cột trong DB.
     */
    protected $fillable = [
        'productName',       // Dùng tên này
        'productDescription',// Dùng tên này
        'price',             // Giả định bạn có cột price/stock (chưa có trong ảnh, nhưng cần cho Controller)
        'image',             // Dùng tên này
        'categoryId',        // Dùng tên này
        'brandId',           // Dùng tên này
    ];
    
    // Giả định quan hệ Category và Brand
    public function categories()
    {
        // Khóa ngoại là categoryId, khóa chính của Category là categoryId
        return $this->belongsTo(Categories::class, 'categoryId', 'categoryId'); 
    }

    public function brands()
    {
        // Khóa ngoại là brandId, khóa chính của Brand là brandId
        return $this->belongsTo(Brands::class, 'brandId', 'brandId');
    }
}