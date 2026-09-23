<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorizeOwner($order);

        $order->load(['items', 'pnbpBill', 'paymentProofs', 'contract', 'invoice']);

        return view('customer.orders.show', compact('order'));
    }

    public function uploadProof(Request $request, Order $order)
    {
        $this->authorizeOwner($order);

        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        if (! in_array($order->status, ['menunggu_pembayaran', 'pembayaran_ditolak'])) {
            return back()->with('error', 'Pesanan ini belum bisa menerima bukti pembayaran.');
        }

        $path = $request->file('file')->store('payment-proofs', 'public');

        PaymentProof::create([
            'order_id' => $order->id,
            'pnbp_bill_id' => optional($order->pnbpBill)->id,
            'file_path' => $path,
            'status' => 'menunggu',
        ]);

        $order->update(['status' => 'menunggu_verifikasi']);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah, menunggu verifikasi petugas.');
    }

    protected function authorizeOwner(Order $order): void
    {
        abort_unless($order->user_id === Auth::id(), 403);
    }
}
