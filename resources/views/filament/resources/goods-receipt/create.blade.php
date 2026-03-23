<x-filament-panels::page>
    <div class="flex gap-5" style="height: calc(100vh - 12rem);">

        {{-- LEFT PANEL: Product Table --}}
        <div class="flex-1 flex flex-col rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">

            {{-- Header + Search --}}
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 shrink-0">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Danh sách sản phẩm</h2>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="🔍 Tìm kiếm sản phẩm..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-primary-500 focus:border-primary-500"
                />
            </div>

            {{-- Scrollable Table Body --}}
            <div class="overflow-y-auto flex-1">
                <table class="w-full text-sm border-collapse">
                    <thead class="sticky top-0 bg-gray-100 dark:bg-gray-800 z-10">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sản phẩm</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20">Tồn</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Giá bán</th>
                            <th class="px-4 py-2 w-20"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($this->products as $product)
                            <tr class="hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors {{ $product->stock <= 0 ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                <td class="px-4 py-2.5 text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $product->name }}
                                    @if($product->stock <= 0)
                                        <span class="ml-1 text-[10px] bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 px-1.5 py-0.5 rounded font-semibold">Hết</span>
                                    @elseif($product->stock <= 5)
                                        <span class="ml-1 text-[10px] bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 px-1.5 py-0.5 rounded font-semibold">Sắp hết</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-center font-mono text-sm {{ $product->stock <= 0 ? 'text-red-500 font-bold' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $product->stock }}
                                </td>
                                <td class="px-4 py-2.5 text-right text-gray-500 dark:text-gray-400 tabular-nums">
                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <button
                                        wire:click="addToCart({{ $product->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-primary-600 hover:bg-primary-700 text-white transition"
                                    >
                                        + Thêm
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500 text-sm">
                                    Không tìm thấy sản phẩm nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="w-96 shrink-0 flex flex-col gap-4 overflow-y-auto">

            {{-- Supplier + Note --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-4 shrink-0">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nhà cung cấp</label>
                <select wire:model="supplier_id"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                    <option value="">-- Chọn nhà cung cấp --</option>
                    @foreach($this->suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>

                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-3 mb-1">Ghi chú</label>
                <textarea wire:model="note" rows="2" placeholder="Ghi chú (không bắt buộc)..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-primary-500 focus:border-primary-500 resize-none">
                </textarea>
            </div>

            {{-- Cart --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm flex flex-col flex-1 overflow-hidden min-h-0">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-between shrink-0">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Giỏ nhập hàng</span>
                    <span class="text-xs bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-400 px-2 py-0.5 rounded-full font-bold">{{ count($cart) }} sp</span>
                </div>

                <div class="overflow-y-auto flex-1 divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($cart as $index => $item)
                        <div class="p-3">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-100 leading-snug flex-1 line-clamp-2">{{ $item['product_name'] }}</span>
                                <button wire:click="removeFromCart({{ $index }})"
                                    class="text-gray-300 hover:text-red-500 dark:text-gray-600 dark:hover:text-red-400 text-sm leading-none shrink-0 transition">✕</button>
                            </div>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-2">
                                Niêm yết: <span class="font-semibold text-gray-500 dark:text-gray-400">{{ number_format($item['retail_price'], 0, ',', '.') }}₫</span>
                            </p>
                            <div class="flex gap-2">
                                <div class="flex-1">
                                    <label class="text-[10px] text-gray-400 dark:text-gray-500 block mb-0.5">Giá nhập (₫)</label>
                                    <input type="number" wire:model.live="cart.{{ $index }}.import_price" min="0"
                                        class="w-full rounded border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-xs py-1 px-2 focus:ring-primary-500 focus:border-primary-500" />
                                </div>
                                <div class="w-20">
                                    <label class="text-[10px] text-gray-400 dark:text-gray-500 block mb-0.5">Số lượng</label>
                                    <input type="number" wire:model.live="cart.{{ $index }}.quantity" min="1"
                                        class="w-full rounded border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-xs py-1 px-2 focus:ring-primary-500 focus:border-primary-500" />
                                </div>
                            </div>
                            <p class="text-right text-xs font-bold text-primary-600 dark:text-primary-400 mt-1 tabular-nums">
                                = {{ number_format((float)($item['quantity'] ?? 0) * (float)($item['import_price'] ?? 0), 0, ',', '.') }}₫
                            </p>
                        </div>
                    @empty
                        <div class="py-10 text-center text-gray-400 dark:text-gray-500 text-sm">
                            Chưa có sản phẩm nào.<br>
                            <span class="text-xs">Nhấn "+ Thêm" ở bảng bên trái.</span>
                        </div>
                    @endforelse
                </div>

                {{-- Total + Save --}}
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 shrink-0">
                    <div class="flex justify-between items-baseline mb-3">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-300">Tổng tiền:</span>
                        <span class="text-xl font-bold text-primary-700 dark:text-primary-400 tabular-nums">
                            {{ number_format($this->grandTotal, 0, ',', '.') }}₫
                        </span>
                    </div>
                    <button
                        wire:click="saveReceipt"
                        wire:loading.attr="disabled"
                        class="w-full py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white text-sm font-semibold transition"
                    >
                        <span wire:loading.remove wire:target="saveReceipt">💾 Lưu Phiếu Nhập</span>
                        <span wire:loading wire:target="saveReceipt">Đang lưu...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page>
