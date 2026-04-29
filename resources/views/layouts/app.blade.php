<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TheGioiDiDong Clone') }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Hệ thống bán lẻ điện thoại, laptop, smartwatch, tablet chính hãng uy tín nhất.">
    <meta name="keywords" content="điện thoại, laptop, thegioididong, tablet, apple, samsung">
    <meta property="og:title" content="The Gioi Di Dong Clone - Mua sắm trực tuyến">
    <meta property="og:description" content="Hệ thống bán lẻ thiết bị công nghệ hàng đầu Việt Nam.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Tailwind CSS (CDN fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            yellow: '#fed700',
                            dark: '#333333',
                            blue: '#288ad6',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js (CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- FontAwesome (Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
        }

        .tgdd-yellow {
            background-color: #fed700;
        }

        .tgdd-yellow-text {
            color: #fed700;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden relative" x-data="cartStore()">

    <!-- Header -->
    <header class="tgdd-yellow text-black sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-4 lg:px-8 h-16 flex items-center justify-between">
            <!-- Logo & Mobile Menu -->
            <div class="flex items-center gap-4" x-data="{ mobileMenuOpen: false }">
                <button @click="mobileMenuOpen = true" class="lg:hidden text-2xl pr-2">
                    <i class="fa-solid fa-bars"></i>
                </button>
                
                <!-- Mobile Slide-out Menu -->
                <div x-show="mobileMenuOpen" class="fixed inset-0 z-[100] lg:hidden" style="display: none;">
                    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50"></div>
                    <div x-show="mobileMenuOpen" 
                         x-transition:enter="transition ease-out duration-300 transform" 
                         x-transition:enter-start="-translate-x-full" 
                         x-transition:enter-end="translate-x-0" 
                         x-transition:leave="transition ease-in duration-300 transform" 
                         x-transition:leave-start="translate-x-0" 
                         x-transition:leave-end="-translate-x-full" 
                         class="fixed inset-y-0 left-0 w-80 max-w-[80vw] bg-white text-gray-800 shadow-2xl flex flex-col h-full z-10 overflow-y-auto">
                         
                        <div class="p-4 border-b flex justify-between items-center bg-brand-yellow">
                            <span class="font-bold text-lg">Danh mục sản phẩm</span>
                            <button @click="mobileMenuOpen = false" class="text-xl px-2">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        
                        <div class="py-2">
                            @php
                                $mobileCollections = \App\Models\Collection::where('is_active', true)
                                    ->whereNull('parent_id')
                                    ->with(['children' => function($q) {
                                        $q->where('is_active', true)->orderBy('sort_order');
                                    }])
                                    ->orderBy('sort_order')
                                    ->get();
                            @endphp
                            
                            @foreach($mobileCollections as $col)
                                <div class="border-b border-gray-100" x-data="{ open: false }">
                                    <div class="flex items-center justify-between px-4 py-3">
                                        <a href="/bo-suu-tap/{{ $col->slug }}" class="font-medium hover:text-brand-blue flex-grow">{{ $col->name }}</a>
                                        @if($col->children->count() > 0)
                                            <button @click="open = !open" class="p-2 text-gray-500 hover:text-brand-blue">
                                                <i class="fa-solid fa-chevron-down text-sm transition-transform duration-200" :class="{'rotate-180': open}"></i>
                                            </button>
                                        @endif
                                    </div>
                                    @if($col->children->count() > 0)
                                        <div x-show="open" x-collapse style="display: none;" class="bg-gray-50 px-6 py-2 pb-4">
                                            <div class="flex flex-col space-y-3">
                                                @foreach($col->children as $child)
                                                    <a href="/bo-suu-tap/{{ $child->slug }}" class="text-sm text-gray-600 hover:text-brand-blue block">
                                                        {{ $child->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a href="/"
                    class="flex-shrink-0 inline-flex items-center justify-center hover:scale-105 transition-transform duration-300 bg-[#ffcc00] text-black font-bold px-2 py-1 rounded"
                    style="font-family: Arial, sans-serif; font-size: 40px; line-height: 1;">
                    <img src="{{ asset('images/logo.png') }}" alt="The Gioi Di Dong"
                        class="h-[40px] w-auto object-contain"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                    <span style="display:none;">TheGioiDiDong</span>
                </a>
            </div>

            <!-- Mega Search Bar -->
            <div class="hidden lg:block relative flex-grow max-w-xl mx-8">
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Bạn tìm gì..." value="{{ request('q') }}"
                        class="w-full rounded-full py-2 pl-4 pr-10 border-0 focus:ring-2 focus:ring-brand-blue outline-none text-sm text-gray-800 shadow-inner">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            <!-- Actions: Hotline, Auth, Cart -->
            <div class="flex items-center gap-6 text-sm font-medium">
                <a href="#" class="hidden lg:flex items-center gap-2 hover:bg-black/10 px-2 py-1 rounded transition">
                    <i class="fa-solid fa-phone-volume text-lg"></i>
                    <span>Gọi mua hàng<br>1800.1060</span>
                </a>

                @auth
                    <div class="relative group cursor-pointer hidden sm:block">
                        <div class="flex items-center gap-2 hover:bg-black/10 px-2 py-1 rounded transition">
                            <i class="fa-regular fa-user text-lg"></i>
                            <span>{{ Auth::user()->username }}</span>
                        </div>
                        <div class="absolute right-0 top-full pt-2 hidden group-hover:block w-48 z-50">
                            <div class="bg-white rounded shadow-lg border text-gray-800 overflow-hidden text-sm w-full">
                                <a href="{{ route('account.index') }}" class="block px-4 py-2.5 hover:bg-gray-50 hover:text-brand-blue font-medium border-b flex items-center gap-2">
                                    <i class="fa-regular fa-circle-user w-4"></i> Tài khoản của tôi
                                </a>
                                <a href="{{ route('account.orders.index') }}" class="block px-4 py-2 hover:bg-gray-50 flex items-center gap-2">
                                    <i class="fa-solid fa-clipboard-list w-4"></i> Đơn mua
                                </a>
                                <a href="{{ route('account.vouchers.index') }}" class="block px-4 py-2 hover:bg-gray-50 flex items-center gap-2">
                                    <i class="fa-solid fa-ticket w-4"></i> Kho Voucher
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">Đăng
                                        xuất</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-2 hover:bg-black/10 px-2 py-1 rounded transition hidden sm:flex">
                        <i class="fa-regular fa-user text-lg"></i>
                        <span>Đăng nhập</span>
                    </a>
                @endauth

                <button @click="openCart = true"
                    class="flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-brand-dark px-3 py-1.5 rounded-lg transition relative">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span class="hidden sm:inline">Giỏ hàng</span>
                    <span x-text="itemCount" x-show="itemCount > 0"
                        class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">0</span>
                </button>
            </div>
        </div>

        <!-- Desktop Navigation Categories -->
        <div class="bg-brand-dark text-white hidden lg:block" x-data="{ hoverCat: null }">
            <div class="container mx-auto px-8 flex items-center gap-8 text-sm font-medium h-10 relative">
                @php
                    $navCollections = \App\Models\Collection::where('is_active', true)
                        ->whereNull('parent_id')
                        ->with(['children' => function($q) {
                            $q->where('is_active', true)->orderBy('sort_order');
                        }])
                        ->orderBy('sort_order')
                        ->take(8)
                        ->get();
                @endphp
                @foreach($navCollections as $col)
                    <div class="h-full flex items-center relative group" @mouseenter="hoverCat = {{ $col->id }}" @mouseleave="hoverCat = null">
                        <a href="/bo-suu-tap/{{ $col->slug }}"
                            class="hover:text-brand-yellow transition flex items-center gap-1 py-2 cursor-pointer relative z-20">
                            {{ $col->name }}
                            @if($col->children->count() > 0)
                                <i class="fa-solid fa-sort-down text-[10px] mb-1 opacity-70"></i>
                            @endif
                        </a>
                        
                        @if($col->children->count() > 0)
                            <!-- Hover Dropdown -->
                            <div x-show="hoverCat === {{ $col->id }}"
                                 x-transition.opacity.duration.200ms
                                 class="absolute top-10 left-0 bg-white text-gray-800 shadow-xl rounded-b px-6 py-4 z-50 min-w-[200px] border-t-2 border-brand-yellow {{ $col->children->count() > 6 ? 'w-[400px]' : '' }}"
                                 style="display: none;"
                                 @mouseenter="hoverCat = {{ $col->id }}"
                                 @mouseleave="hoverCat = null">
                                 
                                <div class="{{ $col->children->count() > 6 ? 'grid grid-cols-2 gap-x-6 gap-y-3' : 'flex flex-col space-y-3' }}">
                                    @foreach($col->children as $child)
                                        <a href="/bo-suu-tap/{{ $child->slug }}" class="hover:text-brand-blue py-1 transition flex items-center text-[13px]">
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12 py-10 text-sm text-gray-600">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-brand-blue">Tích điểm Quà tặng VIP</a></li>
                    <li><a href="#" class="hover:text-brand-blue">Lịch sử mua hàng</a></li>
                    <li><a href="#" class="hover:text-brand-blue">Tìm hiểu về mua trả góp</a></li>
                    <li><a href="#" class="hover:text-brand-blue">Chính sách bảo hành</a></li>
                </ul>
            </div>
            <div>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-brand-blue">Giới thiệu công ty (MWG.vn)</a></li>
                    <li><a href="#" class="hover:text-brand-blue">Tuyển dụng</a></li>
                    <li><a href="#" class="hover:text-brand-blue">Gửi góp ý, khiếu nại</a></li>
                    <li><a href="#" class="hover:text-brand-blue">Tìm siêu thị (3.000+ shop)</a></li>
                </ul>
            </div>
            <div>
                <p class="font-bold text-gray-800 mb-2">Tổng đài hỗ trợ (Miễn phí gọi)</p>
                <ul class="space-y-1">
                    <li>Gọi mua: <span class="font-bold text-brand-blue">1800.1060</span> (7:30 - 22:00)</li>
                    <li>Kỹ thuật: <span class="font-bold text-brand-blue">1800.1763</span> (7:30 - 22:00)</li>
                    <li>Khiếu nại: <span class="font-bold text-brand-blue">1800.1062</span> (8:00 - 21:30)</li>
                    <li>Bảo hành: <span class="font-bold text-brand-blue">1800.1064</span> (8:00 - 21:00)</li>
                </ul>
            </div>
            <div>
                <p class="font-bold text-gray-800 mb-2">Kết nối với chúng tôi</p>
                <div class="flex gap-4 mb-4">
                    <a href="#" class="text-blue-600 text-3xl"><i class="fa-brands fa-square-facebook"></i></a>
                    <a href="#" class="text-red-600 text-3xl"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Cart Flyout (Slide Over) -->
    @include('components.cart-flyout')

    <!-- Alpine.js Global State -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartStore', () => ({
                openCart: false,
                items: JSON.parse(localStorage.getItem('cart') || '[]'),
                stockWarning: '',

                init() {
                    this.$watch('openCart', value => {
                        if (value === true && this.items.length > 0) {
                            this.fetchLatestStock();
                        }
                    });
                },

                async fetchLatestStock() {
                    try {
                        let ids = this.items.map(i => i.id);
                        let response = await fetch('/cart/stock-check', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ product_ids: ids })
                        });
                        let data = await response.json();
                        if (data.success) {
                            let changed = false;
                            let changedNames = [];
                            this.items = this.items.map(item => {
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
                                this.showWarning("Số lượng sản phẩm " + changedNames.join(', ') + " trong giỏ hàng đã được cập nhật do thay đổi tồn kho.");
                            }
                            this.saveCart();
                        }
                    } catch (e) {
                        console.error("Lỗi cập nhật tồn kho", e);
                    }
                },

                get itemCount() {
                    return this.items.reduce((total, item) => total + item.quantity, 0);
                },

                get cartTotal() {
                    return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                addToCart(product) {
                    let existing = this.items.find(i => i.id === product.id);
                    if (existing) {
                        if (existing.quantity + 1 > product.stock) {
                            this.showWarning("Rất tiếc chúng tôi chỉ còn " + product.stock + " sản phẩm");
                            this.openCart = true;
                            return;
                        }
                        existing.quantity++;
                        existing.stock = product.stock;
                    } else {
                        if (product.stock < 1) {
                            this.showWarning("Sản phẩm đã hết hàng");
                            return;
                        }
                        this.items.push({ ...product, quantity: 1 });
                    }
                    this.saveCart();
                    this.openCart = true;
                },

                updateQuantity(id, delta) {
                    let index = this.items.findIndex(i => i.id === id);
                    if (index !== -1) {
                        let newQty = this.items[index].quantity + delta;
                        if (newQty > this.items[index].stock && delta > 0) {
                            this.items[index].quantity = this.items[index].stock;
                            this.showWarning("Rất tiếc chúng tôi chỉ còn " + this.items[index].stock + " sản phẩm");
                        } else {
                            this.items[index].quantity = newQty;
                        }
                        
                        if (this.items[index].quantity <= 0) {
                            this.items.splice(index, 1);
                        }
                    }
                    this.saveCart();
                },

                validateQuantity(id) {
                    let index = this.items.findIndex(i => i.id === id);
                    if (index !== -1) {
                        let qty = parseInt(this.items[index].quantity);
                        if (isNaN(qty) || qty < 1) {
                            qty = 1;
                        }
                        if (qty > this.items[index].stock) {
                            qty = this.items[index].stock;
                            this.showWarning("Rất tiếc chúng tôi chỉ còn " + this.items[index].stock + " sản phẩm");
                        }
                        this.items[index].quantity = qty;
                    }
                    this.saveCart();
                },

                showWarning(msg) {
                    this.stockWarning = msg;
                    setTimeout(() => { this.stockWarning = ''; }, 5000);
                },

                formatMoney(amount) {
                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
                },

                saveCart() {
                    localStorage.setItem('cart', JSON.stringify(this.items));
                },

                checkout() {
                    // Update stock before checkout page redirect just in case
                    this.fetchLatestStock().then(() => {
                        if (this.items.length === 0) return alert('Giỏ hàng trống!');
                        window.location.href = '/checkout';
                    });
                }
            }))
        })
    </script>
</body>

</html>