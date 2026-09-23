@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        @include('admin.products.form')
        <button class="bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-2.5 rounded-md">Perbarui Produk</button>
    </form>
</div>
@endsection
