<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'order_id', 'invoice_number', 'total', 'issued_by',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function formattedTotal(): string
    {
        return 'Rp' . number_format($this->total, 0, ',', '.');
    }
}
