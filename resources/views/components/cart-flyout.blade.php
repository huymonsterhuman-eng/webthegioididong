<div x-show="openCart" class="fixed inset-0 z-[100] bg-black/50 backdrop-blur-sm shadow-2xl" x-transition.opacity
    style="display: none;">
    <div @click.away="openCart = false" x-show="openCart" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-xl flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h2 class="text-lg font-bold text-gray-800">Giỏ hàng của bạn (<span x-text="itemCount"></span>)</h2>
            <button @click="openCart = false"
                class="text-gray-500 hover:text-red-500 hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Items -->
        <div class="flex-grow overflow-y-auto p-4 space-y-4 bg-gray-50">
            <template x-if="items.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-4">
                    <i class="fa-solid fa-basket-shopping text-6xl opacity-30"></i>
                    <p>Giỏ hàng chưa có sản phẩm nào</p>
                    <button @click="openCart = false"
                        class="px-6 py-2 bg-brand-yellow text-brand-dark rounded font-medium shadow-sm hover:shadow-md transition">Tiếp
                        tục mua sắm</button>
                </div>
            </template>

            <template x-for="item in items" :key="item.id">
                <div class="bg-white border rounded-lg p-3 flex gap-3 shadow-sm">
                    <div
                        class="w-20 h-20 bg-gray-100 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                        <template x-if="item.image">
                            <img :src="item.image" class="w-full h-full object-contain">
                        </template>
                        <template x-if="!item.image">
                            <i class="fa-solid fa-mobile text-gray-300 text-2xl"></i>
                        </template>
                    </div>
                    <div class="flex-grow flex flex-col justify-between">
                        <h3 class="text-sm font-medium text-gray-800 line-clamp-2 leading-snug" x-text="item.name"></h3>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-red-600 font-bold" x-text="formatMoney(item.price)"></span>
                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-2">
                                <div class="flex items-center border rounded">
                                    <button @click="updateQuantity(item.id, -1)"
                                        class="w-7 h-7 flex items-center justify-center bg-gray-50 hover:bg-gray-200 text-gray-600">-</button>
                                    <span class="w-8 text-center text-sm font-medium" x-text="item.quantity"></span>
                                    <button @click="updateQuantity(item.id, 1)"
                                        class="w-7 h-7 flex items-center justify-center bg-gray-50 hover:bg-gray-200 text-gray-600">+</button>
                                </div>
                                <button @click="updateQuantity(item.id, -item.quantity)"
                                    class="text-gray-400 hover:text-red-500 transition px-1" title="Xóa sản phẩm">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer Checkout -->
        <div class="border-t p-4 bg-white" x-show="items.length > 0">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-600 font-medium">Tổng tiền:</span>
                <span class="text-red-600 font-bold text-xl" x-text="formatMoney(cartTotal)"></span>
            </div>
            <button @click="checkout"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg shadow-md transition-colors">
                ĐẶT HÀNG
            </button>
        </div>
    </div>
</div>