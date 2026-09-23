<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $products = Product::with('category')
            ->where('status', 'aktif')
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $request->category));
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            })
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('customer.catalog.index', compact('categories', 'products'));
    }

    public function show(Product $product)
    {
        abort_unless($product->status === 'aktif', 404);

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'aktif')
            ->limit(4)
            ->get();

        return view('customer.catalog.show', compact('product', 'related'));
    }
}
