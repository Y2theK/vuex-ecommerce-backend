<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->hasOne(Category::class);
    }
    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withPivot('quantity');
    }
}
