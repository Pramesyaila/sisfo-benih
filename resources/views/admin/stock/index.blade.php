@extends('layouts.admin')

@section('title', 'Stok Masuk / Keluar')

@section('content')
<form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border text-sm">
    <button class="bg-base border border-gray-200 px-3 py-2 rounded-md text-sm hover:border-primary">Cari</button>
    <a href="{{ route('admin.stock.history') }}" class="ml-auto bg-primary text-white px-4 py-2 rounded-md text-sm hover:bg-primary-dark">Lihat Riwayat Stok &rarr;</a>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-base text-gray-500 text-left">
            <tr>
                <th class="px-4 py-3">Produk</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Stok Saat Ini</th>
                <th class="px-4 py-3 w-72">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($products as $product)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                <td class="px-4 py-3">{{ $product->category->name }}</td>
                <td class="px-4 py-3 {{ $product->isLowStock() ? 'text-accent font-semibold' : '' }}">{{ $product->stock }} {{ $product->packaging_unit }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <form action="{{ route('admin.stock.in', $product) }}" method="POST" class="flex items-center gap-1">
                            @csrf
                            <input type="number" name="qty" min="1" placeholder="Qty" required class="w-20 rounded-md border-gray-300 px-2 py-1 border text-xs">
                            <button class="bg-primary text-white px-2 py-1.5 rounded-md text-xs hover:bg-primary-dark">Stok Masuk</button>
                        </form>
                        <form action="{{ route('admin.stock.out', $product) }}" method="POST" class="flex items-center gap-1">
                            @csrf
                            <input type="number" name="qty" min="1" placeholder="Qty" required class="w-20 rounded-md border-gray-300 px-2 py-1 border text-xs">
                            <button class="bg-accent text-primary-dark px-2 py-1.5 rounded-md text-xs hover:brightness-95">Stok Keluar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
