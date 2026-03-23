<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class MyOrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('orderDetails.product')->latest()->paginate(10);
        return view('my-orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('orderDetails.product');
        return view('my-orders.show', compact('order'));
    }

    public function cancel(Order $order, Request $request)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (in_array($order->status, ['shipping', 'delivered', 'cancelled'])) {
            return back()->with('error', 'Đơn hàng này không thể hủy được nữa.');
        }

        // 1. Restore stock
        foreach ($order->orderDetails as $detail) {
            $product = \App\Models\Product::find($detail->product_id);
            if ($product) {
                $product->increment('stock', $detail->quantity);
            }
        }

        // 2. Restore voucher if used
        if ($order->voucher_id) {
            $voucher = \App\Models\Voucher::find($order->voucher_id);
            if ($voucher) {
                if ($voucher->used_count > 0) {
                    $voucher->decrement('used_count');
                }
                
                // Mark voucher as unused for user
                $user = auth()->user();
                $userVoucher = $user->vouchers()->where('voucher_id', $voucher->id)->first();
                if ($userVoucher) {
                    $user->vouchers()->updateExistingPivot($voucher->id, ['is_used' => false]);
                }
            }
        }

        // 3. Update order status
        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Hủy đơn hàng thành công, mã giảm giá và số lượng sản phẩm đã được hoàn lại.');
    }
}
