@extends('layouts.account')

@section('account_content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-white rounded-lg shadow-sm p-6 flex items-center justify-between border-l-4 border-brand-yellow">
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-1">Xin chào, {{ $user->full_name ?? $user->username }}!</h2>
            <p class="text-gray-500 text-sm">Chào mừng bạn trở lại với khu vực dành cho khách hàng.</p>
        </div>
        <div class="hidden sm:block text-brand-yellow opacity-20 text-5xl">
            <i class="fa-solid fa-face-smile"></i>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-brand-blue flex items-center justify-center text-xl">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $user->orders()->count() }}</p>
                <p class="text-gray-500 text-sm">Đơn hàng</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 text-brand-yellow flex items-center justify-center text-xl">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $user->vouchers()->wherePivot('is_used', false)->count() }}</p>
                <p class="text-gray-500 text-sm">Voucher khả dụng</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl">
                <i class="fa-regular fa-star"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $user->reviews()->count() }}</p>
                <p class="text-gray-500 text-sm">Đánh giá</p>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Đơn hàng gần đây</h3>
            <a href="{{ route('account.orders.index') }}" class="text-brand-blue hover:underline text-sm font-medium">Xem tất cả</a>
        </div>
        
        @if($recentOrders->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <i class="fa-solid fa-receipt text-4xl mb-3 opacity-20"></i>
                <p>Bạn chưa có đơn hàng nào.</p>
                <a href="/" class="inline-block mt-4 px-6 py-2 bg-brand-yellow text-brand-dark rounded-md hover:bg-yellow-500 font-medium transition">Mua sắm ngay</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b">
                            <th class="p-4 font-medium">Mã ĐH</th>
                            <th class="p-4 font-medium">Ngày đặt</th>
                            <th class="p-4 font-medium">Sản phẩm</th>
                            <th class="p-4 font-medium">Tổng tiền</th>
                            <th class="p-4 font-medium">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($recentOrders as $order)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-4">
                                <a href="{{ route('account.orders.show', $order) }}" class="text-brand-blue hover:underline font-medium">#{{ $order->id }}</a>
                            </td>
                            <td class="p-4">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="p-4">
                                <div class="max-w-[200px] truncate" title="{{ $order->orderDetails->pluck('product.name')->implode(', ') }}">
                                    {{ $order->orderDetails->pluck('product.name')->implode(', ') }}
                                </div>
                            </td>
                            <td class="p-4 font-medium text-red-600">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                            <td class="p-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'shipping' => 'bg-purple-100 text-purple-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipping' => 'Đang giao hàng',
                                        'delivered' => 'Đã giao',
                                        'cancelled' => 'Đã hủy',
                                    ];
                                    $colorClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                    $label = $statusLabels[$order->status] ?? ucfirst($order->status);
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                                    {{ $label }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
