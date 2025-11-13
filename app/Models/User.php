<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Giữ nguyên tên Class là User
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'userId';

    // Bật timestamps (Laravel sẽ tự động dùng created_at và updated_at)
    public $timestamps = true;

    /**
     * Các thuộc tính có thể gán hàng loạt.
     * Thêm 'image' vào danh sách fillable.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'fullName',
        'address',
        'phone',
        'image',
        'role',
    ];

    /**
     * Các thuộc tính nên bị ẩn khi chuyển đổi model thành array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Định nghĩa quan hệ với bảng Orders.
     */
    public function orders()
    {
        // Cần đảm bảo Model Orders đã được đổi tên thành Order (hoặc tên chính xác của bạn)
        // và khóa ngoại trong bảng 'orders' là 'user_id'
        return $this->hasMany('App\Models\Orders', 'user_id', 'userId');
    }

    // Phương thức truy cập để lấy URL ảnh đại diện
    public function getAvatarUrlAttribute()
    {
        return $this->image
            ? asset('uploads/' . $this->image)
            : asset('images/img-user' . $this->image);
    }


}