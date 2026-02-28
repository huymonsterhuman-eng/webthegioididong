<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TheGioiDiDong Clone') }}</title>

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
            <div class="flex items-center gap-4">
                <button class="lg:hidden text-2xl pr-2">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="/" class="text-2xl font-black tracking-tighter">
                    TGDĐ<span class="text-sm font-normal">.com</span>
                </a>
            </div>

            <!-- Mega Search Bar -->
            <div class="hidden lg:block relative flex-grow max-w-xl mx-8">
                <form action="#" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Bạn tìm gì..."
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
                        <div class="absolute right-0 top-full pt-2 hidden group-hover:block w-48">
                            <div class="bg-white rounded shadow-lg border text-gray-800 overflow-hidden text-sm">
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Đơn hàng của tôi</a>
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
        <div class="bg-brand-dark text-white hidden lg:block">
            <div class="container mx-auto px-8 flex items-center gap-6 text-sm font-medium h-10">
                @php
                    $navCategories = \App\Models\Category::take(8)->get();
                @endphp
                @foreach($navCategories as $cat)
                    <a href="/categories/{{ $cat->slug }}" class="hover:text-brand-yellow transition">{{ $cat->name }}</a>
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

                get itemCount() {
                    return this.items.reduce((total, item) => total + item.quantity, 0);
                },

                get cartTotal() {
                    return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                addToCart(product) {
                    let existing = this.items.find(i => i.id === product.id);
                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.items.push({ ...product, quantity: 1 });
                    }
                    this.saveCart();
                    this.openCart = true;
                },

                updateQuantity(id, delta) {
                    let index = this.items.findIndex(i => i.id === id);
                    if (index !== -1) {
                        this.items[index].quantity += delta;
                        if (this.items[index].quantity <= 0) {
                            this.items.splice(index, 1);
                        }
                    }
                    this.saveCart();
                },

                formatMoney(amount) {
                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
                },

                saveCart() {
                    // Save to localstorage for now (Phase 3 spec mentions Alpine AJAX cart)
                    localStorage.setItem('cart', JSON.stringify(this.items));
                },

                checkout() {
                    if (this.items.length === 0) return alert('Giỏ hàng trống!');
                    window.location.href = '/checkout';
                }
            }))
        })
    </script>
</body>

</html>