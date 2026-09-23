<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PnbpBill extends Model
{
    protected $table = 'pnbp_bills';

    protected $fillable = [
        'order_id', 'bill_number', 'amount', 'status', 'due_date', 'paid_at', 'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'belum_dibayar' => 'Belum Dibayar',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lunas' => 'Lunas',
            'ditolak' => 'Ditolak',
            default => $status,
        };
    }

    public function formattedAmount(): string
    {
        return 'Rp' . number_format($this->amount, 0, ',', '.');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
