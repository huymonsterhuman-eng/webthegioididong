@extends('layouts.account')

@section('account_content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b">
        <a href="{{ route('account.orders.index') }}" class="text-gray-400 hover:text-brand-blue transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">
            {{ __('Chi tiết đơn hàng') }} #{{ $order->id }}
        </h2>
    </div>

    <div class="space-y-6">
                    <div class="flex flex-col md:flex-row justify-between border-b dark:border-gray-700 pb-4 mb-4">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Thông tin nhận hàng</h3>
                            <p class="text-sm"><span class="font-medium">Người nhận:</span>
                                {{ $order->shipping_name ?? $order->user->username }}</p>
                            <p class="text-sm"><span class="font-medium">Điện thoại:</span> {{ $order->shipping_phone }}
                            </p>
                            <p class="text-sm"><span class="font-medium">Địa chỉ:</span> {{ $order->shipping_address }}
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0 md:text-right">
                            <h3 class="text-lg font-semibold mb-2">Thông tin thanh toán</h3>
                            <p class="text-sm"><span class="font-medium">Phương thức:</span>
                                {{ $order->payment_method ?? 'Thanh toán khi nhận hàng (COD)' }}</p>
                            <p class="text-sm"><span class="font-medium">Ngày đặt:</span>
                                {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    'shipping' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                    'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                ];
                                $statusLabels = [
                                    'pending' => 'Chờ xử lý',
                                    'confirmed' => 'Đã xác nhận',
                                    'shipping' => 'Đang giao hàng',
                                    'delivered' => 'Đã giao thành công',
                                    'cancelled' => 'Đã hủy',
                                ];
                            @endphp
                            <p class="text-sm mt-2">
                                <span class="font-medium">Trạng thái:</span>
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </p>
                            @if(in_array($order->status, ['pending', 'confirmed']))
                                <form action="{{ route('account.orders.cancel', $order) }}" method="POST" class="mt-4" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không? Quá trình này không thể hoàn tác.');">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded shadow transition-colors text-sm">
                                        <i class="fa-solid fa-xmark mr-1"></i> Hủy đơn hàng
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mb-4">Sản phẩm đã đặt</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Sản phẩm</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Giá</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Số lượng</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tạm tính</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach($order->orderDetails as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($detail->product && $detail->product->image)
                                                        @php
                                                            $img = $detail->product->image;
                                                            if (str_starts_with($img, 'http')) {
                                                                $imageUrl = $img;
                                                            } elseif (str_starts_with($img, 'img/')) {
                                                                $imageUrl = url('storage/' . $img);
                                                            } else {
                                                                $imageUrl = \Illuminate\Support\Facades\Storage::url($img);
                                                            }
                                                        @endphp
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $imageUrl }}"
                                                            alt="{{ $detail->product->name ?? 'Sản phẩm' }}">
                                                    @else
                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                            src="{{ asset('storage/img/placeholder.jpg') }}" alt="Placeholder">
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $detail->product ? $detail->product->name : 'Sản phẩm đã bị xóa' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                            {{ number_format($detail->price_at_purchase, 0, ',', '.') }} ₫
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                            {{ $detail->quantity }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium text-right">
                                            {{ number_format($detail->price_at_purchase * $detail->quantity, 0, ',', '.') }}
                                            ₫
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        Giảm giá (Voucher):
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">
                                        -{{ number_format(floatval($order->discount_amount), 0, ',', '.') }} ₫
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 text-right whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                        Tổng cộng:
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right whitespace-nowrap text-lg font-bold text-red-600 dark:text-red-400">
                                        {{ number_format(floatval($order->total), 0, ',', '.') }} ₫
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-8 pt-6 border-t">
                        <a href="{{ route('account.orders.index') }}"
                            class="text-brand-blue hover:text-blue-800 font-medium flex items-center gap-2">
                            <i class="fa-solid fa-arrow-left text-sm"></i> Quay lại danh sách đơn hàng
                        </a>
                    </div>
                </div>
@endsection