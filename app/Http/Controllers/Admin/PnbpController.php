<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PnbpBill;
use Illuminate\Http\Request;

class PnbpController extends Controller
{
    public function index(Request $request)
    {
        $bills = PnbpBill::with('order.user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pnbp.index', compact('bills'));
    }
}
