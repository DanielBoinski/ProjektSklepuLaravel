<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_path',
        'price',
        'stock',
    ];

    /**
     * Relacja: produkt ma wiele pozycji zamówień.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
