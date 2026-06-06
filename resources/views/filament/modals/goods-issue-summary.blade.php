{{-- Modal tóm tắt phiếu xuất kho — hiển thị trong trang Đơn hàng (bên bán) --}}
@if ($goodsIssue)
    <div class="space-y-4 p-2">

        {{-- Thông tin chung --}}
        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500 dark:text-gray-400">Mã phiếu</p>
                <p class="font-semibold">#PX-{{ str_pad($goodsIssue->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400">Trạng thái</p>
                @php
                    $statusLabel = match($goodsIssue->status) {
                        'pending'   => 'Chờ kho duyệt',
                        'completed' => 'Đã bàn giao ĐVVC',
                        'cancelled' => 'Đã hủy',
                        default     => $goodsIssue->status,
                    };
                    $statusColor = match($goodsIssue->status) {
                        'pending'   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                        default     => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400">Ngày tạo</p>
                <p class="font-semibold">{{ $goodsIssue->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <hr class="border-gray-200 dark:border-gray-700">

        {{-- Danh sách sản phẩm --}}
        <div>
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Danh sách sản phẩm</p>

            @if ($goodsIssue->details && $goodsIssue->details->count() > 0)
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <th class="pb-2 font-medium">Sản phẩm</th>
                            <th class="pb-2 font-medium text-center">Số lượng</th>
                            <th class="pb-2 font-medium text-right">Giá vốn</th>
                            <th class="pb-2 font-medium text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($goodsIssue->details as $detail)
                            <tr>
                                <td class="py-2 pr-4">
                                    {{ $detail->product?->name ?? 'Sản phẩm #' . $detail->product_id }}
                                </td>
                                <td class="py-2 text-center">{{ $detail->quantity }}</td>
                                <td class="py-2 text-right text-gray-500">
                                    {{ $detail->import_price > 0 ? number_format($detail->import_price, 0, ',', '.') . ' ₫' : '—' }}
                                </td>
                                <td class="py-2 text-right font-medium">
                                    {{ $detail->total_price > 0 ? number_format($detail->total_price, 0, ',', '.') . ' ₫' : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-sm text-gray-400 italic">Chưa có sản phẩm nào.</p>
            @endif
        </div>

        {{-- Tổng COGS --}}
        @if ($goodsIssue->total_cogs > 0)
            <div class="flex justify-end pt-2 border-t border-gray-200 dark:border-gray-700">
                <div class="text-right">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tổng giá vốn (COGS)</p>
                    <p class="text-base font-bold text-gray-900 dark:text-white">
                        {{ number_format($goodsIssue->total_cogs, 0, ',', '.') }} ₫
                    </p>
                </div>
            </div>
        @endif

    </div>
@else
    <p class="text-sm text-gray-400 italic p-4">Không tìm thấy phiếu xuất kho liên quan.</p>
@endif
