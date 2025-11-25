<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
    ];

    // relacja do użytkownika (opcjonalna, ale przydatna)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relacja do pozycji zamówienia
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
