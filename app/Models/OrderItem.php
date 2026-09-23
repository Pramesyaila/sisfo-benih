<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'packaging', 'price', 'qty', 'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function formattedPrice(): string
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }

    public function formattedSubtotal(): string
    {
        return 'Rp' . number_format($this->subtotal, 0, ',', '.');
    }
}
