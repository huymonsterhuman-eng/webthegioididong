@extends('layouts.account')

@section('account_content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">
        Đơn hàng của tôi
    </h2>

                    @if($orders->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">Bạn chưa có đơn hàng nào.</p>
                            <a href="{{ route('home') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Mua sắm
                                ngay</a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Mã đơn hàng</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Ngày đặt</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tổng tiền</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Trạng thái</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach($orders as $order)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ 'ORD-' . \Carbon\Carbon::parse($order->created_at)->format('Ymd') . '-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-semibold">
                                                {{ number_format(floatval($order->total), 0, ',', '.') }} ₫
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('account.orders.show', $order) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Xem
                                                    chi tiết</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
    </div>
</div>
@endsection