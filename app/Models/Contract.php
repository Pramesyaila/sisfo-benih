<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'order_id', 'contract_number', 'nama', 'nik', 'domisili', 'alamat', 'content', 'created_by',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
