<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Invoice;

class PaymentProofController extends Controller
{
    public function index(Request $request)
    {
        $proofs = PaymentProof::with(['order.user'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status), fn ($q) => $q->where('status', 'menunggu'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.payment-proofs.index', compact('proofs'));
    }

    public function verify(Request $request, PaymentProof $paymentProof)
    {
        $data = $request->validate([
            'decision' => ['required', 'in:valid,ditolak'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($paymentProof, $data) {
            $paymentProof->update([
                'status' => $data['decision'],
                'note' => $data['note'] ?? null,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            $order = $paymentProof->order;

            if ($data['decision'] === 'valid') {
                $order->update(['status' => 'lunas']);

                if ($order->pnbpBill) {
                    $order->pnbpBill->update(['status' => 'lunas', 'paid_at' => now()]);
                }

                // Generate faktur otomatis
                if (! $order->invoice) {
                    Invoice::create([
                        'order_id' => $order->id,
                        'invoice_number' => 'INV-' . now()->format('Y') . '-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                        'total' => $order->total,
                        'issued_by' => Auth::id(),
                    ]);
                }

                $order->update(['status' => 'faktur_terbit']);
            } else {
                $order->update(['status' => 'pembayaran_ditolak']);
                if ($order->pnbpBill) {
                    $order->pnbpBill->update(['status' => 'ditolak']);
                }
            }
        });

        return back()->with('success', 'Bukti pembayaran berhasil diverifikasi.');
    }
}
