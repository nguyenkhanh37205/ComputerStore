<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'brandId',
        'brandName'
    ];
    protected $primaryKey = 'brandId';
    protected $table = 'brands';
    public function products()
    {
        return $this->hasMany('App\Models\Products');
    }
}