<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlists extends Model
{
    use HasFactory;

    protected $table = 'wishlists';
    protected $primaryKey = 'wishlistId';

    public $timestamps = true;
    const UPDATED_AT = 'updatedAt'; // Khai báo tên cột updated_at

    protected $fillable = [
        'userId',
        'productId',
    ];

    /**
     * Định nghĩa khóa tổng hợp (unique index)
     */
    protected $touches = ['user', 'product']; // Không cần thiết nhưng có thể dùng

    /**
     * Quan hệ: Wishlist thuộc về một User
     */
    public function user()
    {
        // Khóa ngoại là userId, khóa chính của User là userId
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

    /**
     * Quan hệ: Wishlist liên kết với một Product
     */
    public function product()
    {
        // Khóa ngoại là productId, khóa chính của Product là productId
        return $this->belongsTo(Products::class, 'productId', 'productId');
    }
}