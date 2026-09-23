@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-lg font-bold">{{ $order->order_number }}</h2>
        <p class="text-sm text-gray-500">{{ $order->user->name }} &middot; {{ $order->created_at->format('d M Y H:i') }}</p>
    </div>
    <span class="badge {{ $order->statusBadgeColor() }}">{{ \App\Models\Order::statusLabel($order->status) }}</span>
</div>

<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y">
            @foreach($order->items as $item)
                <div class="p-4 flex items-center gap-4">
                    <div class="flex-1">
                        <p class="font-semibold">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->qty }} {{ $item->packaging }} &times; {{ $item->formattedPrice() }}
                            @if($item->product)
                                <span class="ml-2 {{ $item->product->isLowStock() ? 'text-accent' : 'text-gray-400' }}">(stok tersedia: {{ $item->product->stock }})</span>
                            @endif
                        </p>
                    </div>
                    <p class="font-semibold text-primary-dark">{{ $item->formattedSubtotal() }}</p>
                </div>
            @endforeach
            <div class="p-4 flex justify-between font-bold text-primary-dark">
                <span>Total</span><span>{{ $order->formattedTotal() }}</span>
            </div>
        </div>

        @if($order->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <p class="text-sm text-gray-500 mb-1">Catatan konsumen</p>
                <p class="text-sm">{{ $order->notes }}</p>
            </div>
        @endif

        @if($order->paymentProofs->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold mb-3">Bukti Pembayaran</h3>
                <ul class="text-sm divide-y">
                    @foreach($order->paymentProofs as $proof)
                        <li class="py-2 flex justify-between items-center">
                            <a href="{{ asset('storage/'.$proof->file_path) }}" target="_blank" class="text-primary-dark hover:underline">Lihat berkas ({{ $proof->created_at->format('d/m/Y H:i') }})</a>
                            <span class="badge {{ $proof->status === 'valid' ? 'bg-primary text-white' : ($proof->status === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-accent-light text-primary-dark') }}">{{ ucfirst($proof->status) }}</span>
                        </li>
                    @endforeach
                </ul>
                @if(auth()->user()->role === 'petugas_layanan')
                    <p class="text-xs text-gray-400 mt-2">Verifikasi bukti pembayaran melalui menu "Verifikasi Pembayaran".</p>
                @endif
            </div>
        @endif

        @if($order->contract)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold mb-2">Kontrak {{ $order->contract->contract_number }}</h3>
                <pre class="whitespace-pre-wrap text-xs bg-base p-3 rounded-md border">{{ $order->contract->content }}</pre>
                <button onclick="window.print()" class="mt-2 text-primary-dark text-sm font-semibold hover:underline">🖨️ Cetak Kontrak</button>
            </div>
        @endif

        @if($order->invoice)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold mb-2">Faktur Penjualan {{ $order->invoice->invoice_number }}</h3>
                <p class="text-sm text-gray-600">Total: {{ $order->invoice->formattedTotal() }}</p>
                <button onclick="window.print()" class="mt-2 text-primary-dark text-sm font-semibold hover:underline">🖨️ Cetak Faktur</button>
            </div>
        @endif
    </div>

    <div class="space-y-4">
        @if(auth()->user()->role === 'petugas_layanan')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-3">
                <h3 class="font-semibold">Aksi Petugas Layanan</h3>

                @if($order->status === 'dipesan')
                    <form action="{{ route('admin.orders.process', $order) }}" method="POST">
                        @csrf
                        <button class="w-full bg-primary text-white py-2 rounded-md text-sm hover:bg-primary-dark">Proses Pesanan (Cek Stok)</button>
                    </form>
                @endif

                @if($order->status === 'diproses' && !$order->contract)
                    <details class="border rounded-md p-3">
                        <summary class="text-sm font-medium cursor-pointer">Buat Kontrak</summary>
                        <form action="{{ route('admin.orders.contract', $order) }}" method="POST" class="mt-3 space-y-2">
                            @csrf
                            <input name="nama" placeholder="Nama" value="{{ $order->user->name }}" required class="w-full rounded-md border-gray-300 px-2 py-1.5 border text-sm">
                            <input name="nik" placeholder="NIK" value="{{ $order->user->nik }}" class="w-full rounded-md border-gray-300 px-2 py-1.5 border text-sm">
                            <input name="domisili" placeholder="Domisili" value="{{ $order->user->domisili }}" class="w-full rounded-md border-gray-300 px-2 py-1.5 border text-sm">
                            <textarea name="alamat" placeholder="Alamat" class="w-full rounded-md border-gray-300 px-2 py-1.5 border text-sm">{{ $order->user->alamat }}</textarea>
                            <button class="w-full bg-primary text-white py-1.5 rounded-md text-sm hover:bg-primary-dark">Generate Kontrak</button>
                        </form>
                    </details>
                @endif

                @if($order->status === 'diproses' && !$order->pnbpBill)
                    <form action="{{ route('admin.orders.bill', $order) }}" method="POST">
                        @csrf
                        <button class="w-full bg-accent text-primary-dark py-2 rounded-md text-sm font-semibold hover:brightness-95">Buat Tagihan PNBP</button>
                    </form>
                @endif

                @if($order->status === 'siap_diambil')
                    <form action="{{ route('admin.orders.markTaken', $order) }}" method="POST">
                        @csrf
                        <button class="w-full bg-primary-dark text-white py-2 rounded-md text-sm hover:brightness-95">Tandai Sudah Diambil</button>
                    </form>
                @endif

                @if(!in_array($order->status, ['selesai','dibatalkan']))
                    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                        @csrf
                        <button class="w-full bg-white border border-red-300 text-red-600 py-2 rounded-md text-sm hover:bg-red-50">Batalkan Pesanan</button>
                    </form>
                @endif
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold mb-3">Alur Status</h3>
            <ol class="relative border-l-2 border-accent-light pl-4 space-y-2 text-xs">
                @foreach(['dipesan','diproses','menunggu_pembayaran','menunggu_verifikasi','lunas','faktur_terbit','siap_diambil','selesai'] as $step)
                    <li class="{{ $order->status === $step ? 'text-primary-dark font-bold' : 'text-gray-400' }}">{{ \App\Models\Order::statusLabel($step) }}</li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
@endsection
