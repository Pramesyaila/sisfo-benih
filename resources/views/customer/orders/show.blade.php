@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold text-primary-dark">Pesanan {{ $order->order_number }}</h1>
    <span class="badge {{ $order->statusBadgeColor() }}">{{ \App\Models\Order::statusLabel($order->status) }}</span>
</div>

<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y">
            @foreach($order->items as $item)
                <div class="p-4 flex items-center gap-4">
                    <div class="h-12 w-12 bg-accent-light rounded-md flex items-center justify-center text-xl flex-shrink-0">🌱</div>
                    <div class="flex-1">
                        <p class="font-semibold">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->qty }} {{ $item->packaging }} &times; {{ $item->formattedPrice() }}</p>
                    </div>
                    <p class="font-semibold text-primary-dark">{{ $item->formattedSubtotal() }}</p>
                </div>
            @endforeach
            <div class="p-4 flex justify-between font-bold text-primary-dark">
                <span>Total</span>
                <span>{{ $order->formattedTotal() }}</span>
            </div>
        </div>

        @if($order->pnbpBill)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h2 class="font-semibold mb-2">Tagihan PNBP</h2>
                <p class="text-sm text-gray-600">No. Tagihan: <strong>{{ $order->pnbpBill->bill_number }}</strong></p>
                <p class="text-sm text-gray-600">Jumlah: <strong>{{ $order->pnbpBill->formattedAmount() }}</strong></p>
                <p class="text-sm text-gray-600">Status: <strong>{{ \App\Models\PnbpBill::statusLabel($order->pnbpBill->status) }}</strong></p>
                <p class="text-sm text-gray-600">Jatuh tempo: {{ optional($order->pnbpBill->due_date)->format('d M Y') }}</p>

                @if(in_array($order->status, ['menunggu_pembayaran', 'pembayaran_ditolak']))
                    <form action="{{ route('orders.uploadProof', $order) }}" method="POST" enctype="multipart/form-data" class="mt-4 flex flex-col sm:flex-row gap-2">
                        @csrf
                        <input type="file" name="file" required class="text-sm">
                        <button class="bg-accent hover:brightness-95 text-primary-dark font-semibold px-4 py-2 rounded-md">Unggah Bukti Pembayaran</button>
                    </form>
                @endif
            </div>
        @endif

        @if($order->pnbpBill)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold mb-2">Tagihan PNBP {{ $order->pnbpBill->bill_number }}</h3>
        <pre class="whitespace-pre-wrap text-xs bg-base p-3 rounded-md border">{{ $order->pnbpBill->content }}</pre>
        <button onclick="window.print()" class="mt-2 text-primary-dark text-sm font-semibold hover:underline">🖨️ Cetak Tagihan</button>
    </div>
@endif

        @if($order->paymentProofs->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h2 class="font-semibold mb-2">Riwayat Bukti Pembayaran</h2>
                <ul class="text-sm divide-y">
                    @foreach($order->paymentProofs as $proof)
                        <li class="py-2 flex justify-between items-center">
                            <a href="{{ asset('storage/'.$proof->file_path) }}" target="_blank" class="text-primary-dark hover:underline">Lihat berkas</a>
                            <span class="badge {{ $proof->status === 'valid' ? 'bg-primary text-white' : ($proof->status === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-accent-light text-primary-dark') }}">
                                {{ ucfirst($proof->status) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($order->contract)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h2 class="font-semibold mb-2">Kontrak</h2>
                <a href="{{ route('orders.show', $order) }}#kontrak" class="text-primary-dark hover:underline text-sm">Lihat kontrak No. {{ $order->contract->contract_number }} di bawah</a>
                <pre id="kontrak" class="mt-3 whitespace-pre-wrap text-xs bg-base p-3 rounded-md border">{{ $order->contract->content }}</pre>
            </div>
        @endif
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="font-semibold mb-3">Status Pesanan</h2>
            <ol class="relative border-l-2 border-accent-light pl-4 space-y-3 text-xs">
                @foreach(['dipesan','diproses','menunggu_pembayaran','menunggu_verifikasi','lunas','faktur_terbit','siap_diambil','selesai'] as $step)
                    <li class="{{ $order->status === $step ? 'text-primary-dark font-bold' : 'text-gray-400' }}">
                        {{ \App\Models\Order::statusLabel($step) }}
                    </li>
                @endforeach
            </ol>
        </div>

        @if($order->invoice)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h2 class="font-semibold mb-2">Faktur Penjualan</h2>
                <p class="text-sm text-gray-600 mb-3">No. {{ $order->invoice->invoice_number }}</p>
                <a href="{{ route('orders.show', $order) }}" onclick="window.print(); return false;" class="text-primary-dark font-semibold hover:underline text-sm">🖨️ Cetak halaman ini</a>
            </div>
        @endif
    </div>
</div>
@endsection
