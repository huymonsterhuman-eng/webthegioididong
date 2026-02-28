@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 lg:px-8 py-8" x-data="{
        localItems: JSON.parse(localStorage.getItem('cart') || '[]'),
        get total() { return this.localItems.reduce((t, i) => t + (i.price * i.quantity), 0); }
    }">
        <div x-show="localItems.length === 0" class="text-center py-20 bg-white rounded-lg shadow max-w-3xl mx-auto">
            <i class="fa-solid fa-cart-arrow-down text-6xl text-gray-300 mb-4"></i>
            <h1 class="text-xl font-bold text-gray-700 mb-2">Giỏ hàng của bạn đang trống!</h1>
            <p class="text-gray-500 mb-6">Hãy thêm sản phẩm vào giỏ hàng trước khi thanh toán.</p>
            <a href="/" class="px-6 py-2 bg-brand-yellow font-bold rounded hover:bg-yellow-500 transition">Quay về trang
                chủ</a>
        </div>

        <div x-show="localItems.length > 0" class="max-w-4xl mx-auto" style="display:none;">
            <h1 class="text-2xl font-bold text-gray-800 uppercase mb-6 text-center">Xác nhận đơn hàng</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 relative" role="alert">
                    <strong class="font-bold">Thành công!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                <!-- Clear cart after successful checkout -->
                <script>
                    localStorage.removeItem('cart');
                    window.location.href = '/';
                </script>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Checkout Form -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold mb-4 border-b pb-2">Thông tin khách hàng</h2>
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên *</label>
                                <input type="text" name="name" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200"
                                    value="{{ auth()->user()->username ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                                <input type="text" name="phone" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ giao hàng *</label>
                                <input type="text" name="address" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phương thức thanh toán</label>
                                <div class="space-y-2 mt-2">
                                    <label
                                        class="flex items-center gap-2 p-3 border rounded cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="cod" checked
                                            class="text-brand-blue focus:ring-brand-blue">
                                        <span class="font-medium text-gray-800">Thanh toán khi nhận hàng (COD)</span>
                                    </label>
                                    <label
                                        class="flex items-center gap-2 p-3 border rounded cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="vnpay"
                                            class="text-brand-blue focus:ring-brand-blue">
                                        <span class="font-medium text-gray-800">Thanh toán VNPay (Sandbox)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full mt-6 bg-brand-blue hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md transition-colors text-lg uppercase"
                            @click="setTimeout(() => { localStorage.removeItem('cart') }, 500)">
                            Hoàn tất đặt hàng
                        </button>
                    </form>
                </div>

                <!-- Order Summary (Alpine hydrated) -->
                <div class="bg-gray-50 rounded-lg shadow-inner border border-gray-200 p-6 self-start">
                    <h2 class="text-lg font-bold mb-4 border-b border-gray-200 pb-2">Tóm tắt đơn hàng</h2>

                    <div class="space-y-4 mb-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="item in localItems" :key="item.id">
                            <div class="flex gap-4 p-2 bg-white border rounded shadow-sm">
                                <div class="w-16 h-16 bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <img :src="item.image" class="w-full h-full object-contain" x-show="item.image">
                                    <i class="fa-solid fa-mobile" x-show="!item.image"></i>
                                </div>
                                <div class="flex-grow">
                                    <h4 class="text-sm font-medium text-gray-800 line-clamp-2 leading-tight mb-1"
                                        x-text="item.name"></h4>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500" x-text="'SL: ' + item.quantity"></span>
                                        <span class="font-bold text-red-600" x-text="formatMoney(item.price)"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-gray-200 pt-4 space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Tạm tính:</span>
                            <span x-text="formatMoney(total)"></span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Phí vận chuyển:</span>
                            <span>0 đ</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200">
                            <span>Tổng cộng:</span>
                            <span class="text-red-600" x-text="formatMoney(total)"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection