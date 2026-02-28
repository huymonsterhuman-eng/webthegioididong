<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart');
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        // Just simulating the checkout completion since we don't have VNPay integration keys
        // or a fully populated Cart session yet. We assume it's entirely client-side for Phase 3 prototype.

        return redirect()->route('home')->with('success', 'Đơn hàng của bạn đã được đặt thành công!');
    }
}
