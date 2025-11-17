<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'orderItems'; 
    protected $primaryKey = 'orderItemId'; 
    public $timestamps = false;

    protected $fillable = [
        'orderId',
        'variantId', 
        'quantity',
        'price',
    ];

    public function variant() 
    {
        return $this->belongsTo(Variant::class, 'variantId', 'variantId');
    }
}