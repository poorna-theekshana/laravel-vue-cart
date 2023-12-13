<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'user_id',
        'product_id',
        'quantity',
        'price',
        'product_name',
        'description',
        'status',
        'session_id',
        'payload',
    ];
    public function products()
    {
        return $this->hasMany(Product::class, 'product_id', 'id');
    }
}
