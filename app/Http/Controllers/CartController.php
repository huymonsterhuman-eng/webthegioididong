<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;

class CartController extends Controller
{
    public function index()
    {
        return view('cart');
    }

    public function checkout()
    {
        $vouchers = collect();
        $addresses = collect();
        if (auth()->check()) {
            $vouchers = auth()->user()->vouchers()
                ->wherePivot('is_used', false)
                ->where('is_active', true)
                ->get();
            $addresses = auth()->user()->addresses()->orderByDesc('is_default')->get();
        }
        return view('checkout', compact('vouchers', 'addresses'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable|exists:addresses,id',
            'name' => 'required_without:address_id|nullable|string|max:255',
            'phone' => 'required_without:address_id|nullable|string|max:20',
            'address' => 'required_without:address_id|nullable|string|max:500',
            'payment_method' => 'required|string',
            'cart_items' => 'required|string',
            'voucher_code' => 'nullable|string',
            'shipping_method' => 'required|string|in:standard,express',
        ]);

        $shippingName = $validated['name'] ?? '';
        $shippingPhone = $validated['phone'] ?? '';
        $shippingAddress = $validated['address'] ?? '';

        if (!empty($validated['address_id'])) {
            $address = \App\Models\Address::where('id', $validated['address_id'])->where('user_id', auth()->id())->firstOrFail();
            $shippingName = $address->name;
            $shippingPhone = $address->phone;
            $shippingAddress = $address->address;
        }

        // Save address to address book if requested (only for logged in users)
        if (empty($validated['address_id']) && $request->boolean('save_address') && auth()->check()) {
            auth()->user()->addresses()->create([
                'name' => $shippingName,
                'phone' => $shippingPhone,
                'address' => $shippingAddress,
                'is_default' => auth()->user()->addresses()->count() === 0,
            ]);
        }

        $cartItems = json_decode($validated['cart_items'], true);
        if (!$cartItems || count($cartItems) === 0) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        $total = $subtotal;

        // Calculate Shipping
        $shippingMethod = $validated['shipping_method'];
        $shippingFee = ($shippingMethod === 'express') ? 50000 : 30000;
        
        $total += $shippingFee;

        // Check for Voucher
        $voucher = null;
        $discountAmount = 0;
        if (!empty($validated['voucher_code'])) {
            $voucher = \App\Models\Voucher::where('code', $validated['voucher_code'])->first();
            if ($voucher) {
                $check = $voucher->isValid($total);
                if ($check['valid']) {
                    $discountAmount = $voucher->calculateDiscount($total);
                    $total -= $discountAmount;
                    if ($total < 0) {
                        $total = 0;
                    }
                    $voucher->increment('used_count'); // Increment used count
                    
                    // Mark voucher as used for the user
                    $user = auth()->user();
                    if ($user) {
                        $userVoucher = $user->vouchers()->where('voucher_id', $voucher->id)->first();
                        if ($userVoucher) {
                            $user->vouchers()->updateExistingPivot($voucher->id, ['is_used' => true]);
                        } else {
                            $user->vouchers()->attach($voucher->id, ['is_used' => true]);
                        }
                    }
                } else {
                    $voucher = null; // Don't apply if invalid
                }
            }
        }

        // Validate stock availability before placing order
        foreach ($cartItems as $item) {
            $product = \App\Models\Product::find($item['id']);
            if (!$product || $product->stock < $item['quantity']) {
                $name = $product ? $product->name : 'Sản phẩm không xác định';
                return back()->with('error', "Sản phẩm \"{$name}\" không đủ hàng trong kho. Vui lòng kiểm tra lại giỏ hàng.");
            }
        }

        // Create the Order
        $order = \App\Models\Order::create([
            'user_id'          => auth()->id(),
            'subtotal'         => $subtotal,
            'total'            => $total,
            'shipping_name'    => $shippingName,
            'shipping_address' => $shippingAddress,
            'shipping_phone'   => $shippingPhone,
            'status'           => 'pending',
            'payment_method'   => $validated['payment_method'],
            'payment_status'   => 'unpaid',
            'shipping_method'  => $shippingMethod,
            'shipping_fee'     => $shippingFee,
            'voucher_id'       => $voucher ? $voucher->id : null,
            'discount_amount'  => $discountAmount > 0 ? $discountAmount : null,
        ]);

        // Create Order Details (with product snapshot for history integrity)
        foreach ($cartItems as $item) {
            $product = \App\Models\Product::find($item['id']);
            \App\Models\OrderDetail::create([
                'order_id'         => $order->id,
                'product_id'       => $item['id'],
                'product_name'     => $product ? $product->name : ($item['name'] ?? 'Sản phẩm không rõ'),
                'product_image'    => $product ? $product->image : null,
                'quantity'         => $item['quantity'],
                'price_at_purchase'=> $item['price'],
            ]);

            // Decrement product stock
            \App\Models\Product::where('id', $item['id'])
                ->decrement('stock', $item['quantity']);
        }

        // For momo/vnpay, construct the payment URL and redirect
        if ($validated['payment_method'] === 'vnpay') {
            return redirect()->route('payment.vnpay.return', ['status' => 'success', 'order_id' => $order->id])->with('success', 'Đang chuyển hướng đến VNPay...');
        }

        if ($validated['payment_method'] === 'momo') {
            return redirect()->route('payment.momo.return', ['status' => 'success', 'order_id' => $order->id])->with('success', 'Đang chuyển hướng đến Momo...');
        }

        return redirect()->route('checkout.success', $order)->with('checkout_completed', true);
    }

    public function success(\App\Models\Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!session('checkout_completed')) {
            return redirect()->route('home');
        }

        $order->load('orderDetails.product');
        return view('checkout.success', compact('order'));
    }

    public function vnpayReturn(Request $request)
    {
        // Handle VNPay IPN/Return logic here (check hash, update order status)
        $order = \App\Models\Order::find($request->order_id);
        if($order) {
            $order->update(['payment_status' => 'paid']);
            return redirect()->route('checkout.success', $order)->with('checkout_completed', true);
        }
        return redirect()->route('home')->with('success', 'Thanh toán VNPay thành công!');
    }

    public function momoReturn(Request $request)
    {
        // Handle Momo IPN/Return logic here (check signature, update order status)
        $order = \App\Models\Order::find($request->order_id);
        if($order) {
            $order->update(['payment_status' => 'paid']);
            return redirect()->route('checkout.success', $order)->with('checkout_completed', true);
        }
        return redirect()->route('home')->with('success', 'Thanh toán Momo thành công!');
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'total' => 'required|numeric|min:0'
        ]);

        $voucher = \App\Models\Voucher::where('code', $request->voucher_code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.']);
        }

        $check = $voucher->isValid($request->total);

        if (!$check['valid']) {
            return response()->json(['success' => false, 'message' => $check['message']]);
        }

        $discountAmount = $voucher->calculateDiscount($request->total);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'discount_amount' => $discountAmount,
            'new_total' => max(0, $request->total - $discountAmount)
        ]);
    }

    public function checkStock(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer'
        ]);

        $stocks = \App\Models\Product::whereIn('id', $request->product_ids)
            ->pluck('stock', 'id');

        return response()->json([
            'success' => true,
            'stocks' => $stocks
        ]);
    }
}
