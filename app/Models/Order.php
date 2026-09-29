<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'order_number', 'user_id', 'status', 'total', 'notes',
    'pickup_date', 'pickup_location', 'processed_by', 'taken_at',
    ];

    protected $casts = [
        'taken_at' => 'datetime',
        'pickup_date' => 'date',
    ];

    public function pickupLocationLabel(): string
    {
     return match ($this->pickup_location) {
        'brmp_penerapan' => 'BRMP Penerapan',
        'ip2mp_cipaku' => 'IP2MP Cipaku',
        'dikirim' => 'Dikirim ke alamat pemohon',
        default => '-',
        };
    }
    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'dipesan' => 'Dipesan',
            'diproses' => 'Diproses',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi Pembayaran',
            'pembayaran_ditolak' => 'Pembayaran Ditolak',
            'lunas' => 'Lunas',
            'faktur_terbit' => 'Faktur Terbit',
            'siap_diambil' => 'Siap Diambil',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => $status,
        };
    }

    public function statusBadgeColor(): string
    {
        return match ($this->status) {
            'dipesan', 'diproses' => 'bg-[#FEF9C3] text-[#166534]',
            'menunggu_pembayaran', 'menunggu_verifikasi' => 'bg-[#EAB308] text-white',
            'pembayaran_ditolak', 'dibatalkan' => 'bg-red-100 text-red-700',
            'lunas', 'faktur_terbit', 'siap_diambil' => 'bg-[#16A34A] text-white',
            'selesai' => 'bg-[#166534] text-white',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function formattedTotal(): string
    {
        return 'Rp' . number_format($this->total, 0, ',', '.');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function pnbpBill()
    {
        return $this->hasOne(PnbpBill::class);
    }

    public function paymentProofs()
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function latestPaymentProof()
    {
        return $this->hasOne(PaymentProof::class)->latestOfMany();
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
