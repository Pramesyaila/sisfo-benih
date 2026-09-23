<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->q . '%'))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.stock.index', compact('products'));
    }

    public function history(Request $request)
    {
        $transactions = StockTransaction::with(['product', 'user'])
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->product_id))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->to))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $products = Product::orderBy('name')->get();

        return view('admin.stock.history', compact('transactions', 'products'));
    }

    public function storeIn(Request $request, Product $product)
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($product, $data) {
            $before = $product->stock;
            $after = $before + $data['qty'];

            $product->update(['stock' => $after]);

            StockTransaction::create([
                'product_id' => $product->id,
                'type' => 'masuk',
                'qty' => $data['qty'],
                'stock_before' => $before,
                'stock_after' => $after,
                'note' => $data['note'] ?? 'Stok masuk gudang',
                'user_id' => Auth::id(),
            ]);
        });

        return back()->with('success', "Stok {$product->name} berhasil ditambahkan.");
    }

    public function storeOut(Request $request, Product $product)
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        if ($data['qty'] > $product->stock) {
            return back()->with('error', 'Jumlah stok keluar melebihi stok yang tersedia.');
        }

        DB::transaction(function () use ($product, $data) {
            $before = $product->stock;
            $after = $before - $data['qty'];

            $product->update(['stock' => $after]);

            StockTransaction::create([
                'product_id' => $product->id,
                'type' => 'keluar',
                'qty' => $data['qty'],
                'stock_before' => $before,
                'stock_after' => $after,
                'note' => $data['note'] ?? 'Stok keluar / distribusi manual',
                'user_id' => Auth::id(),
            ]);
        });

        return back()->with('success', "Stok {$product->name} berhasil dikurangi.");
    }
}
