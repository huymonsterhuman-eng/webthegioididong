@php
    $goodsIssue = $getRecord();
    $details = $goodsIssue->details()->with(['product', 'goodsReceiptDetail'])->get();
    
    $totalProductsTypes = $details->unique('product_id')->count();
    $totalItems = $details->sum('quantity');
    $totalCogs = $goodsIssue->total_cogs;
@endphp

<div class="fi-in-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
    <div class="fi-in-section-header mb-6">
        <h3 class="text-lg font-medium tracking-tight text-gray-950 dark:text-white">Chi tiết sản phẩm xuất kho</h3>
    </div>
    
    <div class="fi-ta-content overflow-x-auto divide-y divide-gray-200 dark:divide-white/5">
        <table class="w-full table-auto divide-y divide-gray-200 text-sm text-left dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white">STT</th>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white">Sản phẩm</th>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white text-center">Số lượng</th>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white text-center">Lô hàng (Mã phiếu nhập)</th>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white text-right">Giá nhập (Tại lô)</th>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/5 dark:bg-gray-900">
                @forelse($details as $index => $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300">
                            {{ $item->product ? $item->product->name : 'N/A' }}
                        </td>
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300 text-center">
                            {{ number_format($item->quantity, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300 text-center">
                            @if($item->goodsReceiptDetail)
                                #PN{{ str_pad($item->goodsReceiptDetail->goods_receipt_id, 3, '0', STR_PAD_LEFT) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300 text-right">
                            {{ number_format($item->import_price, 0, ',', '.') }} ₫
                        </td>
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300 text-right">
                            {{ number_format($item->total_price, 0, ',', '.') }} ₫
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-6 text-center text-gray-500 dark:text-gray-400">
                            Không có sản phẩm nào được xuất trong phiếu này.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex flex-col gap-2 text-sm text-gray-700 dark:text-gray-300 p-4 bg-gray-50 dark:bg-white/5 rounded-lg border border-gray-200 dark:border-white/10">
        <div class="flex justify-between">
            <span class="font-medium text-gray-950 dark:text-white">Tổng số lượng mặt hàng (Loại SP):</span>
            <span>{{ $totalProductsTypes }} loại</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-gray-950 dark:text-white">Tổng số lượng sản phẩm:</span>
            <span>{{ number_format($totalItems, 0, ',', '.') }} món</span>
        </div>
        <div class="flex justify-between border-t border-gray-200 dark:border-white/10 pt-2 mt-2">
            <span class="font-bold text-base text-gray-950 dark:text-white">Tổng giá trị xuất kho (COGS):</span>
            <span class="font-bold text-base text-danger-600 dark:text-danger-400">{{ number_format($totalCogs, 0, ',', '.') }} ₫</span>
        </div>
    </div>
</div>
