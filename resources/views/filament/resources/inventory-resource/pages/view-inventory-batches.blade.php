@php
    $product = $getRecord();
    $totalStock = $product->stock;
    
    // Fetch all receipt details for this product, ordered by newest first
    $receiptDetails = \App\Models\GoodsReceiptDetail::where('product_id', $product->id)
        ->join('goods_receipts', 'goods_receipts.id', '=', 'goods_receipt_details.goods_receipt_id')
        ->select('goods_receipt_details.*', 'goods_receipts.created_at as receipt_date')
        ->orderBy('goods_receipts.created_at', 'desc')
        ->get();

    $batches = [];
    foreach ($receiptDetails as $detail) {
        $detail->remaining = $detail->remaining_quantity;
        $batches[] = $detail;
    }
    
    // Sort back to oldest first for display
    $batches = array_reverse($batches);

@endphp

<div class="fi-in-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
    <div class="fi-in-section-header mb-6">
        <h3 class="text-lg font-medium tracking-tight text-gray-950 dark:text-white">Chi tiết các lô hàng</h3>
    </div>
    
    <div class="fi-ta-content overflow-x-auto divide-y divide-gray-200 dark:divide-white/5">
        <table class="w-full table-auto divide-y divide-gray-200 text-sm text-left dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white">Mã Phiếu</th>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white">Ngày nhập</th>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white text-right">Giá nhập</th>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white text-center">Số lượng nhập</th>
                    <th class="px-3 py-3 font-semibold text-gray-950 dark:text-white text-center">Còn lại (Remaining)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/5 dark:bg-gray-900">
                @forelse($batches as $batch)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300">
                            #PN{{ str_pad($batch->goods_receipt_id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($batch->receipt_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300 text-right">
                            {{ number_format($batch->import_price, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300 text-center">
                            {{ number_format($batch->quantity, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3 text-center">
                            @if($batch->remaining > 0)
                                <span class="inline-flex items-center gap-x-1.5 rounded-full px-2 py-1 text-xs font-medium text-success-700 bg-success-50 dark:text-success-400 dark:bg-success-400/10">
                                    {{ number_format($batch->remaining, 0, ',', '.') }} (Còn tồn)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-x-1.5 rounded-full px-2 py-1 text-xs font-medium text-danger-700 bg-danger-50 dark:text-danger-400 dark:bg-danger-400/10">
                                    0 (Đã bán hết)
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-6 text-center text-gray-500 dark:text-gray-400">
                            Không có dữ liệu lô hàng nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50 dark:bg-white/5 font-semibold text-gray-950 dark:text-white">
                <tr>
                    <td colspan="3" class="px-3 py-3 text-left">Tổng cộng</td>
                    <td class="px-3 py-3 text-center">
                        {{ number_format(collect($batches)->sum('quantity'), 0, ',', '.') }}
                    </td>
                    <td class="px-3 py-3 text-center">
                        <span class="text-success-600 dark:text-success-400">
                            {{ number_format($totalStock, 0, ',', '.') }}
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
