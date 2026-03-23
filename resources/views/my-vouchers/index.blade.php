@extends('layouts.app')

@section('content')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kho Voucher của tôi') }}
            </h2>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($vouchers->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">Bạn chưa lưu mã giảm giá nào.</p>
                            <a href="{{ route('home') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Săn
                                voucher ngay</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($vouchers as $voucher)
                                <div class="border rounded-lg p-4 relative overflow-hidden flex flex-col {{ $voucher->pivot->is_used || (!$voucher->is_active) || ($voucher->expires_at && $voucher->expires_at->isPast()) ? 'bg-gray-50 border-gray-200 opacity-75' : 'bg-white border-brand-blue shadow-sm' }}">
                                    <!-- Status Badge -->
                                    <div class="absolute top-0 right-0 px-2 py-1 text-xs font-bold text-white rounded-bl-lg {{ $voucher->pivot->is_used ? 'bg-gray-500' : 'bg-green-500' }}">
                                        {{ $voucher->pivot->is_used ? 'Đã sử dụng' : 'Có thể dùng' }}
                                    </div>
                                    
                                    @if((!$voucher->is_active) || ($voucher->expires_at && $voucher->expires_at->isPast() && !$voucher->pivot->is_used))
                                        <div class="absolute top-0 right-0 px-2 py-1 text-xs font-bold text-white rounded-bl-lg bg-red-500">
                                            Hết hiệu lực
                                        </div>
                                    @endif

                                    <h3 class="text-lg font-bold text-brand-blue mb-2 uppercase">{{ $voucher->code }}</h3>
                                    <p class="text-sm text-gray-700 mb-1">
                                        Giảm định mức: <span class="font-semibold text-red-600">
                                            {{ $voucher->type === 'percent' ? floatval($voucher->discount_amount) . '%' : number_format($voucher->discount_amount, 0, ',', '.') . '₫' }}
                                        </span>
                                    </p>
                                    @if($voucher->min_order_value > 0)
                                        <p class="text-sm text-gray-600 mb-1">Đơn tối thiểu: {{ number_format($voucher->min_order_value, 0, ',', '.') }}₫</p>
                                    @endif
                                    @if($voucher->type === 'percent' && $voucher->max_discount)
                                        <p class="text-sm text-gray-600 mb-1">Giảm tối đa: {{ number_format($voucher->max_discount, 0, ',', '.') }}₫</p>
                                    @endif
                                    
                                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                                        <span>Thu thập lúc: {{ $voucher->pivot->created_at->format('d/m/Y') }}</span>
                                        @if($voucher->expires_at)
                                            <span class="{{ $voucher->expires_at->isPast() ? 'text-red-500' : '' }}">HSD: {{ $voucher->expires_at->format('d/m/Y') }}</span>
                                        @else
                                            <span>Không thời hạn</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
