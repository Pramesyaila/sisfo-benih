<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PnbpBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PnbpController extends Controller
{
    public function index(Request $request)
    {
        $bills = PnbpBill::with('order.user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $waitingForBill = Order::with('user', 'contract')
            ->where('status', 'diproses')
            ->whereHas('contract')
            ->whereDoesntHave('pnbpBill')
            ->latest()
            ->get();

        return view('admin.pnbp.index', compact('bills', 'waitingForBill'));
    }

    // Petugas PNBP menerbitkan tagihan resmi (mirip surat perjanjian, tapi dalam bentuk tagihan)
    public function store(Request $request, Order $order)
    {
        if (! $order->contract) {
            return back()->with('error', 'Surat perjanjian untuk pesanan ini belum dibuat oleh Petugas Layanan.');
        }

        if ($order->pnbpBill) {
            return back()->with('error', 'Tagihan PNBP untuk pesanan ini sudah ada.');
        }

        $data = $request->validate([
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $billNumber = 'PNBP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        $dueDate = \Carbon\Carbon::parse($data['due_date']);

        $itemsText = $order->items->map(
            fn ($i) => "- {$i->product_name} ({$i->packaging}) x {$i->qty} = {$i->formattedSubtotal()}"
        )->implode("\n");

        $content = "SURAT TAGIHAN PENERIMAAN NEGARA BUKAN PAJAK (PNBP)\n\n" .
            "Nomor Tagihan  : {$billNumber}\n" .
            "Nomor Pesanan  : {$order->order_number}\n" .
            "Nomor Kontrak  : {$order->contract->contract_number}\n" .
            "Tanggal Terbit : " . now()->format('d F Y') . "\n\n" .
            "Kepada Yth.\n{$order->user->name}\n\n" .
            "Sehubungan dengan surat perjanjian jual beli benih/bibit di atas, dengan ini diterbitkan " .
            "tagihan Penerimaan Negara Bukan Pajak (PNBP) dengan rincian sebagai berikut:\n\n" .
            "{$itemsText}\n\n" .
            "Total Tagihan  : Rp" . number_format($order->total, 0, ',', '.') . "\n" .
            "Jatuh Tempo    : {$dueDate->format('d F Y')}\n\n" .
            (! empty($data['notes']) ? "Catatan: {$data['notes']}\n\n" : '') .
            "Mohon segera melakukan pembayaran sebelum tanggal jatuh tempo, lalu unggah bukti pembayaran melalui sistem.";

        PnbpBill::create([
            'order_id' => $order->id,
            'bill_number' => $billNumber,
            'amount' => $order->total,
            'status' => 'belum_dibayar',
            'due_date' => $dueDate,
            'content' => $content,
            'notes' => $data['notes'] ?? null,
            'created_by' => Auth::id(),
        ]);

        $order->update(['status' => 'menunggu_pembayaran']);

        return back()->with('success', 'Tagihan PNBP berhasil diterbitkan dan diteruskan ke Petugas Layanan untuk diberikan ke konsumen.');
    }
}