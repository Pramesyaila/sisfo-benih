@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-base text-gray-500 text-left">
                <tr><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Jumlah Produk</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y">
                @foreach($categories as $cat)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $cat->name }}</td>
                    <td class="px-4 py-3">{{ $cat->products_count }}</td>
                    <td class="px-4 py-3 text-right">
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 h-fit">
        <h2 class="font-semibold mb-3">Tambah Kategori</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Nama Kategori</label>
                <input type="text" name="name" required class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border text-sm"></textarea>
            </div>
            <button class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2 rounded-md">Simpan</button>
        </form>
    </div>
</div>
@endsection
