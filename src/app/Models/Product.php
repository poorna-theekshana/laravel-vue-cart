<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'pdct_name',
        'pdct_description',
        'pdct_price',
        'pdct_qty',
        'image',
    ];

    public function getImageURL()
    {
        if ($this->image) {
            return url('storage/' . $this->image);
        }
        return $this->pdct_name;
    }

    public function displayDetails(){
        return "Name: {this->pdct_name}";
    }
}