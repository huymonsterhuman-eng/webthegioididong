@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 lg:px-8 py-8" x-data="{
            localItems: JSON.parse(localStorage.getItem('cart') || '[]'),
            get subtotal() { return this.localItems.reduce((t, i) => t + (i.price * i.quantity), 0); },
            voucherCode: '',
            discountAmount: 0,
            voucherMessage: '',
            voucherError: false,
            shippingMethod: 'standard',
            get shippingFee() { return this.shippingMethod === 'express' ? 50000 : 30000; },
            get total() { return Math.max(0, this.subtotal - this.discountAmount) + this.shippingFee; },
            useSavedAddress: '{{ (isset($addresses) && $addresses->count() > 0) ? "true" : "false" }}',
            addressId: '{{ (isset($addresses) && $addresses->count() > 0) ? $addresses->first()->id : "" }}',
            
            async init() {
                if (this.localItems.length > 0) {
                    try {
                        let ids = this.localItems.map(i => i.id);
                        let response = await fetch('/cart/stock-check', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ product_ids: ids })
                        });
                        let data = await response.json();
                        if (data.success) {
                            let changed = false;
                            let changedNames = [];
                            this.localItems = this.localItems.map(item => {
                                if (data.stocks[item.id] !== undefined) {
                                    item.stock = data.stocks[item.id];
                                    if (item.quantity > item.stock) {
                                        item.quantity = Math.max(0, item.stock);
                                        changed = true;
                                        changedNames.push(item.name);
                                    }
                                }
                                return item;
                            }).filter(item => item.quantity > 0);
                            
                            if (changed) {
                                alert('Số lượng sản phẩm ' + changedNames.join(', ') + ' trong giỏ hàng đã được cập nhật do thay đổi tồn kho.');
                            }
                            localStorage.setItem('cart', JSON.stringify(this.localItems));
                            window.dispatchEvent(new CustomEvent('cart-updated'));
                        }
                    } catch(e) {}
                }
            },

            removeItem(id) {
                this.localItems = this.localItems.filter(item => item.id !== id);
                localStorage.setItem('cart', JSON.stringify(this.localItems));
                if (this.localItems.length === 0) {
                    this.discountAmount = 0;
                    this.voucherCode = '';
                }
                window.dispatchEvent(new CustomEvent('cart-updated'));
            },
            async applyVoucher() {
                if (!this.voucherCode) {
                    this.voucherError = true;
                    this.voucherMessage = 'Vui lòng nhập mã giảm giá';
                    return;
                }
                try {
                    const response = await fetch('{{ route('checkout.apply-voucher') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            voucher_code: this.voucherCode,
                            total: this.subtotal
                        })
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.discountAmount = data.discount_amount;
                        this.voucherError = false;
                        this.voucherMessage = data.message;
                    } else {
                        this.discountAmount = 0;
                        this.voucherError = true;
                        this.voucherMessage = data.message;
                    }
                } catch (error) {
                    this.discountAmount = 0;
                    this.voucherError = true;
                    this.voucherMessage = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Checkout Form -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold mb-4 border-b pb-2">Thông tin khách hàng</h2>
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            @if(isset($addresses) && $addresses->count() > 0)
                            <div class="pb-3 border-b border-gray-100">
                                <label class="flex items-center gap-2 cursor-pointer mb-3">
                                    <input type="radio" value="true" x-model="useSavedAddress" class="text-brand-blue focus:ring-brand-blue">
                                    <span class="font-medium">Chọn địa chỉ đã lưu</span>
                                </label>
                                
                                <div x-show="useSavedAddress === 'true'" class="space-y-3 pl-6">
                                    @foreach($addresses as $address)
                                        <label class="block p-3 border rounded-lg cursor-pointer transition-colors"
                                               :class="addressId == '{{ $address->id }}' ? 'border-brand-blue bg-blue-50/50' : 'hover:bg-gray-50'">
                                            <div class="flex gap-3">
                                                <input type="radio" name="address_id" value="{{ $address->id }}" x-model="addressId" class="mt-1 text-brand-blue focus:ring-brand-blue" :disabled="useSavedAddress !== 'true'">
                                                <div class="flex-grow">
                                                    <div class="flex items-center justify-between">
                                                        <span class="font-bold text-gray-800">{{ $address->name }}</span>
                                                        @if($address->is_default)
                                                            <span class="text-[10px] bg-brand-blue text-white px-2 py-0.5 rounded uppercase font-bold tracking-wider rounded-sm text-center">Mặc định</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-gray-600 mt-1"><i class="fa-solid fa-phone text-gray-400 text-xs w-4"></i> {{ $address->phone }}</p>
                                                    <p class="text-sm text-gray-600 leading-snug mt-1"><i class="fa-solid fa-location-dot text-gray-400 text-xs w-4"></i> {{ $address->address }}</p>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            
                            <label class="flex items-center gap-2 cursor-pointer pt-2">
                                <input type="radio" value="false" x-model="useSavedAddress" class="text-brand-blue focus:ring-brand-blue">
                                <span class="font-medium">Nhập địa chỉ mới</span>
                            </label>
                            @else
                                <div class="bg-blue-50 p-4 rounded-md mb-4 flex items-start gap-3 text-brand-blue border border-blue-100">
                                    <i class="fa-solid fa-circle-info mt-1"></i>
                                    <div>
                                        <p class="text-sm font-bold">Vui lòng nhập địa chỉ nhận hàng</p>
                                        <p class="text-sm">Bạn chưa có địa chỉ lưu sẵn. Vui lòng điền thông tin bên dưới để chúng tôi có thể giao hàng cho bạn.</p>
                                    </div>
                                </div>
                                <input type="hidden" name="use_new_address" value="true">
                            @endif

                            <div x-show="useSavedAddress === 'false'" class="space-y-4 p-4 border border-gray-100 rounded-lg bg-gray-50 mt-3 shadow-inner">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên *</label>
                                    <input type="text" name="name" :required="useSavedAddress === 'false'"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200"
                                        value="{{ auth()->user()->username ?? '' }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                                    <input type="text" name="phone" :required="useSavedAddress === 'false'"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ giao hàng đầy đủ *</label>
                                    <textarea name="address" :required="useSavedAddress === 'false'" rows="3"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200"
                                        placeholder="Ví dụ: Số 20, Đường CMT8, Phường 5, Quận 3, TP.HCM"></textarea>
                                </div>
                                
                                @if(auth()->check())
                                <div class="flex items-center gap-2 pt-1">
                                    <input type="checkbox" id="save_address" name="save_address" value="1" checked class="rounded text-brand-blue focus:ring-brand-blue">
                                    <label for="save_address" class="text-sm text-gray-600 cursor-pointer">Lưu địa chỉ này vào sổ địa chỉ để sử dụng cho lần sau</label>
                                </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phương thức vận chuyển *</label>
                                <div class="space-y-2 mt-2">
                                    <label class="flex items-center justify-between p-3 border rounded cursor-pointer transition-colors"
                                           :class="shippingMethod === 'standard' ? 'border-brand-blue bg-blue-50' : 'hover:bg-gray-50'">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="shipping_method" value="standard" x-model="shippingMethod"
                                                class="text-brand-blue focus:ring-brand-blue">
                                            <div>
                                                <span class="block font-medium text-gray-800">Giao hàng tiêu chuẩn</span>
                                                <span class="block text-xs text-gray-500">Nhận hàng trong 2-3 ngày</span>
                                            </div>
                                        </div>
                                        <span class="font-bold text-gray-800">30.000₫</span>
                                    </label>

                                    <label class="flex items-center justify-between p-3 border rounded cursor-pointer transition-colors"
                                           :class="shippingMethod === 'express' ? 'border-brand-blue bg-blue-50' : 'hover:bg-gray-50'">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="shipping_method" value="express" x-model="shippingMethod"
                                                class="text-brand-blue focus:ring-brand-blue">
                                            <div>
                                                <span class="block font-medium text-gray-800">Giao hàng hỏa tốc 2h</span>
                                                <span class="block text-xs text-brand-blue font-medium">Chỉ áp dụng nội thành tĩnh/TP lớn</span>
                                            </div>
                                        </div>
                                        <span class="font-bold text-gray-800">50.000₫</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán *</label>
                                <div class="space-y-3">
                                    <label
                                        class="flex items-center gap-4 p-4 border hover:border-brand-blue rounded-lg cursor-pointer transition-all duration-200 hover:shadow-sm" 
                                        :class="$el.querySelector('input:checked') ? 'border-brand-blue ring-1 ring-brand-blue bg-blue-50/20' : 'border-gray-200'">
                                        <input type="radio" name="payment_method" value="cod" checked
                                            class="text-brand-blue focus:ring-brand-blue w-5 h-5 mt-0.5 self-start">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-[#2a9dcc] rounded p-2 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-money-bill-transfer text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <span class="block font-bold text-gray-800">Thanh toán khi nhận hàng (COD)</span>
                                                <span class="block text-sm text-gray-500">Khách hàng thanh toán bằng tiền mặt cho nhân viên giao hàng</span>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label
                                        class="flex items-center gap-4 p-4 border hover:border-brand-blue rounded-lg cursor-pointer transition-all duration-200 hover:shadow-sm opacity-90 hover:opacity-100"
                                        :class="$el.querySelector('input:checked') ? 'border-brand-blue ring-1 ring-brand-blue bg-blue-50/20' : 'border-gray-200'">
                                        <input type="radio" name="payment_method" value="vnpay"
                                            class="text-brand-blue focus:ring-brand-blue w-5 h-5 mt-0.5 self-start">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-white border border-gray-100 rounded overflow-hidden flex items-center justify-center shrink-0 p-1">
                                                <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-VNPAY-QR-1.png" alt="VNPAY" class="w-full object-contain">
                                            </div>
                                            <div>
                                                <span class="block font-bold text-gray-800">Thanh toán VNPay</span>
                                                <span class="block text-sm text-gray-500">Thanh toán qua thẻ ATM, Internet Banking</span>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label
                                        class="flex items-center gap-4 p-4 border hover:border-brand-blue rounded-lg cursor-pointer transition-all duration-200 hover:shadow-sm opacity-90 hover:opacity-100"
                                        :class="$el.querySelector('input:checked') ? 'border-brand-blue ring-1 ring-brand-blue bg-blue-50/20' : 'border-gray-200'">
                                        <input type="radio" name="payment_method" value="momo"
                                            class="text-brand-blue focus:ring-brand-blue w-5 h-5 mt-0.5 self-start">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-[#A50064] rounded overflow-hidden flex items-center justify-center shrink-0">
                                                <img src="https://static.mservice.io/img/logo-momo.png" alt="Momo" class="w-full object-contain p-2">
                                            </div>
                                            <div>
                                                <span class="block font-bold text-gray-800">Thanh toán MoMo</span>
                                                <span class="block text-sm text-gray-500">Thanh toán qua ứng dụng ví điện tử MoMo</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="cart_items" :value="JSON.stringify(localItems)">
                        <input type="hidden" name="voucher_code" :value="(!voucherError && discountAmount > 0) ? voucherCode : ''">

                        <button type="submit"
                            class="w-full mt-6 bg-brand-yellow hover:bg-yellow-500 text-brand-dark font-bold py-4 rounded-lg shadow transition-colors text-lg uppercase flex flex-col items-center justify-center border-b-4 border-yellow-600 hover:border-yellow-700 hover:mt-7 hover:mb-[-4px]"
                            @click="setTimeout(() => { localStorage.removeItem('cart'); window.dispatchEvent(new CustomEvent('cart-updated')); }, 1500)">
                            <span>Hoàn tất đặt hàng</span>
                            <span class="text-xs font-normal normal-case mt-0.5 opacity-80">(Xin vui lòng kiểm tra lại đơn hàng trước khi Đặt Mua)</span>
                        </button>
                    </form>
                </div>

                <!-- Order Summary (Alpine hydrated) -->
                <div class="bg-gray-50 rounded-lg shadow-inner border border-gray-200 p-6 self-start">
                    <h2 class="text-lg font-bold mb-4 border-b border-gray-200 pb-2">Tóm tắt đơn hàng</h2>

                    <div class="space-y-4 mb-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="item in localItems" :key="item.id">
                            <div class="flex gap-4 p-2 bg-white border rounded shadow-sm relative pr-8">
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
                                <button type="button" @click="removeItem(item.id)" title="Xóa sản phẩm"
                                    class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition block pb-1 pl-1">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-gray-200 pt-4 space-y-4">
                        <!-- Voucher section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Voucher</label>
                            <div class="flex gap-2">
                                <input type="text" x-model="voucherCode" placeholder="Nhập mã voucher..."
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-blue-200 uppercase">
                                <button type="button" @click="applyVoucher"
                                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition shrink-0">
                                    Áp dụng
                                </button>
                            </div>

                            @if(isset($vouchers) && $vouchers->count() > 0)
                                <div class="mt-3 space-y-2">
                                    <p class="text-sm font-medium text-gray-600">Voucher của bạn (có thể áp dụng):</p>
                                    <div class="flex flex-col gap-2">
                                        @foreach($vouchers as $v)
                                            <div class="px-3 py-2 border border-brand-blue bg-blue-50 text-brand-blue rounded border-dashed text-sm flex items-center justify-between w-full">
                                                <div class="flex flex-col">
                                                    <span class="font-bold uppercase">{{ $v->code }}</span>
                                                    <span class="text-xs text-gray-600">Giảm {{ $v->type === 'percent' ? floatval($v->discount_amount) . '%' : number_format($v->discount_amount, 0, ',', '.') . '₫' }}
                                                    @if($v->min_order_value > 0)
                                                        - Đơn tối thiểu {{ number_format($v->min_order_value, 0, ',', '.') }}₫
                                                    @endif
                                                    </span>
                                                </div>
                                                <button type="button" @click="voucherCode = '{{ $v->code }}'; applyVoucher()" class="text-xs font-bold hover:underline bg-white px-2 py-1 rounded border border-brand-blue transition">Dùng</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Messages -->
                            <p class="text-sm mt-2" 
                               :class="voucherError ? 'text-red-500' : 'text-green-600'" 
                               x-show="voucherMessage !== ''" 
                               x-text="voucherMessage">
                            </p>
                        </div>

                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Tạm tính:</span>
                                <span x-text="formatMoney(subtotal)"></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Phí vận chuyển:</span>
                                <span x-text="formatMoney(shippingFee)"></span>
                            </div>
                            <div class="flex justify-between text-green-600" x-show="discountAmount > 0">
                                <span>Giảm giá:</span>
                                <span x-text="'-' + formatMoney(discountAmount)"></span>
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
    </div>
@endsection