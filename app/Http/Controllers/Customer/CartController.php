<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->cartWithProducts($request);

        return view('customer.cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('qty', 1));

        $cart = session('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $qty;
        session(['cart' => $cart]);

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('qty', 1));

        $cart = session('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id] = $qty;
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public static function cartWithProducts(Request $request)
    {
        $raw = session('cart', []);
        if (empty($raw)) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($raw))->get()->keyBy('id');

        return collect($raw)->map(function ($qty, $productId) use ($products) {
            $product = $products->get($productId);
            if (! $product) {
                return null;
            }
            return [
                'product' => $product,
                'qty' => $qty,
                'subtotal' => $product->price * $qty,
            ];
        })->filter();
    }
}
