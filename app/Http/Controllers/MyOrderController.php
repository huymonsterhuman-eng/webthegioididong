<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class MyOrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('orderDetails.product')->latest()->paginate(10);
        return view('account.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('orderDetails.product');
        return view('account.orders.show', compact('order'));
    }

    public function cancel(Order $order, Request $request)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (in_array($order->status, ['shipping', 'delivered', 'cancelled'])) {
            return back()->with('error', 'Đơn hàng này không thể hủy được nữa.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Hủy đơn hàng thành công, mã giảm giá và số lượng sản phẩm đã được hoàn lại.');
    }
}
