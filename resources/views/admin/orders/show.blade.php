@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-lg font-bold">{{ $order->order_number }}</h2>
        <p class="text-sm text-gray-500">{{ $order->user->name }} &middot; {{ $order->created_at->format('d M Y H:i') }}</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.print.permohonan', $order) }}" target="_blank" class="text-sm bg-white border border-gray-200 px-3 py-1.5 rounded-md hover:border-primary">🖨️ Cetak Surat Permohonan</a>
        <span class="badge {{ $order->statusBadgeColor() }}">{{ \App\Models\Order::statusLabel($order->status) }}</span>
    </div>
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
                <a href="{{ route('admin.orders.print.kontrak', $order) }}" target="_blank" class="inline-block mt-2 text-primary-dark text-sm font-semibold hover:underline">🖨️ Cetak Surat Perjanjian</a>    </div>
             </div>
        @endif

        @if($order->pnbpBill)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold mb-2">Tagihan PNBP {{ $order->pnbpBill->bill_number }}</h3>
        <pre class="whitespace-pre-wrap text-xs bg-base p-3 rounded-md border">{{ $order->pnbpBill->content }}</pre>
        <button onclick="window.print()" class="mt-2 text-primary-dark text-sm font-semibold hover:underline">🖨️ Cetak Tagihan</button>
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
                <form action="{{ route('admin.orders.contract', $order) }}" method="POST">
                    @csrf
                    <button class="w-full bg-primary text-white py-2 rounded-md text-sm hover:bg-primary-dark">Buat Surat Perjanjian (Otomatis)</button>
                </form>
                <p class="text-xs text-gray-400">Data NIK, domisili, dan alamat diambil otomatis dari data konsumen saat checkout.</p>
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

      @if(auth()->user()->role === 'petugas_pnbp')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-3">
        <h3 class="font-semibold">Aksi Petugas PNBP</h3>

        @if($order->contract && !$order->pnbpBill && $order->status === 'diproses')
            <details class="border rounded-md p-3">
                <summary class="text-sm font-medium cursor-pointer">Buat Tagihan PNBP</summary>
                <div class="mt-3 mb-2 text-xs text-gray-500 space-y-1">
                    @foreach($order->items as $item)
                        <div>{{ $item->product_name }} ({{ $item->packaging }}) x {{ $item->qty }} = {{ $item->formattedSubtotal() }}</div>
                    @endforeach
                    <div class="font-semibold text-primary-dark pt-1 border-t">Total: {{ $order->formattedTotal() }}</div>
                </div>
                <form action="{{ route('admin.pnbp.store', $order) }}" method="POST" class="space-y-2">
                    @csrf
                    <label class="text-xs text-gray-500">Jatuh Tempo Pembayaran</label>
                    <input type="date" name="due_date" value="{{ now()->addDays(7)->format('Y-m-d') }}" required class="w-full rounded-md border-gray-300 px-2 py-1.5 border text-sm">
                    <label class="text-xs text-gray-500">Catatan (opsional)</label>
                    <textarea name="notes" placeholder="Catatan tambahan..." class="w-full rounded-md border-gray-300 px-2 py-1.5 border text-sm"></textarea>
                    <button class="w-full bg-accent text-primary-dark py-1.5 rounded-md text-sm font-semibold hover:brightness-95">Terbitkan Tagihan</button>
                </form>
            </details>
        @elseif(!$order->contract)
            <p class="text-xs text-gray-400">Menunggu surat perjanjian dari Petugas Layanan.</p>
        @elseif($order->pnbpBill)
            <p class="text-xs text-gray-400">Tagihan sudah diterbitkan: {{ $order->pnbpBill->bill_number }}</p>
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
