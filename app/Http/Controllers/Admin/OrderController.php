<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Order;
use App\Models\PnbpBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('order_number', 'like', '%' . $request->q . '%')
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', '%' . $request->q . '%'));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'user', 'contract', 'pnbpBill', 'paymentProofs.verifiedBy', 'invoice']);

        return view('admin.orders.show', compact('order'));
    }

    // Petugas Layanan memproses pesanan (cek stok tersedia) & lanjut ke tahap kontrak+tagihan
    public function process(Order $order)
    {
        if ($order->status !== 'dipesan') {
            return back()->with('error', 'Pesanan sudah diproses sebelumnya.');
        }

        foreach ($order->items as $item) {
            if ($item->product && $item->qty > $item->product->stock) {
                return back()->with('error', "Stok {$item->product_name} tidak mencukupi untuk memproses pesanan ini.");
            }
        }

        $order->update([
            'status' => 'diproses',
            'processed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Pesanan sedang diproses. Silakan buat kontrak & tagihan PNBP.');
    }

    // Buat kontrak otomatis dari template + data konsumen
    public function generateContract(Request $request, Order $order)
    {
        if ($order->contract) {
            return back()->with('error', 'Kontrak untuk pesanan ini sudah dibuat.');
        }

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'max:50'],
            'domisili' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
        ]);

        $itemsText = $order->items->map(fn ($i) => "- {$i->product_name} ({$i->packaging}) x {$i->qty}")->implode("\n");

        $content = "Yang bertanda tangan di bawah ini:\n\n" .
            "Nama       : {$data['nama']}\n" .
            "NIK        : " . ($data['nik'] ?? '-') . "\n" .
            "Domisili   : " . ($data['domisili'] ?? '-') . "\n" .
            "Alamat     : " . ($data['alamat'] ?? '-') . "\n\n" .
            "Selanjutnya disebut sebagai PIHAK PERTAMA / Pemesan, menyatakan telah melakukan pemesanan benih/bibit " .
            "dengan nomor pesanan {$order->order_number} sebagai berikut:\n\n{$itemsText}\n\n" .
            "Total nilai pesanan: Rp" . number_format($order->total, 0, ',', '.') . "\n\n" .
            "Kontrak ini dibuat sebagai bagian dari proses administrasi pengelolaan dan penjualan benih/bibit.";

        Contract::create([
            'order_id' => $order->id,
            'contract_number' => 'KTR-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
            'nama' => $data['nama'],
            'nik' => $data['nik'] ?? null,
            'domisili' => $data['domisili'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'content' => $content,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Kontrak berhasil dibuat.');
    }

    // Buat tagihan PNBP untuk pesanan (dipakai Petugas Layanan / Petugas PNBP)
    public function generateBill(Order $order)
    {
        if ($order->pnbpBill) {
            return back()->with('error', 'Tagihan PNBP untuk pesanan ini sudah ada.');
        }

        PnbpBill::create([
            'order_id' => $order->id,
            'bill_number' => 'PNBP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
            'amount' => $order->total,
            'status' => 'belum_dibayar',
            'due_date' => now()->addDays(7),
            'created_by' => Auth::id(),
        ]);

        $order->update(['status' => 'menunggu_pembayaran']);

        return back()->with('success', 'Tagihan PNBP berhasil dibuat. Konsumen dapat melakukan pembayaran.');
    }

    public function markTaken(Order $order)
    {
        if ($order->status !== 'siap_diambil') {
            return back()->with('error', 'Pesanan belum siap untuk diambil / sudah selesai.');
        }

        $order->update([
            'status' => 'selesai',
            'taken_at' => now(),
        ]);

        return back()->with('success', 'Pesanan ditandai sudah diambil oleh konsumen.');
    }

    public function cancel(Order $order)
    {
        if (in_array($order->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        $order->update(['status' => 'dibatalkan']);

        return back()->with('success', 'Pesanan dibatalkan.');
    }
}
