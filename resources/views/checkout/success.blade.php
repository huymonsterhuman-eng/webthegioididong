@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 lg:px-8 py-12">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm border border-gray-100 p-8 text-center">
        <!-- Success Icon -->
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
            <i class="fa-solid fa-check text-4xl text-green-600"></i>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Đặt hàng thành công!</h1>
        <p class="text-gray-600 mb-8">Cảm ơn bạn đã mua sắm tại WebTheGioiDiDong. Đơn hàng của bạn đã được tiếp nhận và đang chờ xử lý.</p>

        <!-- Order Information Box -->
        <div class="bg-gray-50 rounded-lg p-6 text-left mb-8 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Mã đơn hàng</p>
                    <p class="font-bold text-lg text-brand-blue uppercase">{{ 'ORD-' . \Carbon\Carbon::parse($order->created_at)->format('Ymd') . '-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Ngày đặt</p>
                    <p class="font-bold text-gray-800">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Phương thức thanh toán</p>
                    <p class="font-bold text-gray-800">
                        @if($order->payment_method == 'vnpay')
                            Thanh toán VNPay
                        @elseif($order->payment_method == 'momo')
                            Thanh toán Momo
                        @else
                            Thanh toán khi nhận hàng (COD)
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tổng thanh toán</p>
                    <p class="font-bold text-red-600 text-lg">{{ number_format(floatval($order->total), 0, ',', '.') }} ₫</p>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-2">Thông tin người nhận</p>
                <p class="font-semibold text-gray-800">{{ $order->shipping_name }}</p>
                <p class="text-gray-600">{{ $order->shipping_phone }}</p>
                <p class="text-gray-600 mt-1"><i class="fa-solid fa-location-dot text-gray-400 mr-1"></i> {{ $order->shipping_address }}</p>
            </div>
        </div>

        <!-- Next Steps -->
        <h3 class="text-lg font-bold text-gray-800 mb-4">Bạn có thể làm gì tiếp theo?</h3>
        <p class="text-gray-600 mb-8">Chúng tôi sẽ gửi một email xác nhận đến địa chỉ email của bạn cùng với thông tin chi tiết của đơn hàng này. Việc giao hàng dự kiến diễn ra từ 2-3 ngày làm việc đối với giao hàng tiêu chuẩn.</p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('account.orders.show', $order) }}" class="w-full sm:w-auto px-6 py-3 border-2 border-brand-blue text-brand-blue font-bold rounded-lg hover:bg-blue-50 transition-colors uppercase text-sm">
                Theo dõi đơn hàng
            </a>
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 bg-brand-yellow text-brand-dark font-bold rounded-lg hover:bg-yellow-500 transition-colors uppercase text-sm">
                Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<!-- Clear Cart Since Order Placed -->
<script>
    localStorage.removeItem('cart');
    window.dispatchEvent(new CustomEvent('cart-updated'));
</script>
@endsection
