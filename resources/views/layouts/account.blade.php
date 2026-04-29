@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6 text-gray-500">
            <a href="/" class="hover:text-brand-blue">Trang chủ</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-medium">Tài khoản của tôi</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b">
                        <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500 text-xl">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs text-gray-500 mb-1">Tài khoản của</p>
                            <p class="font-bold text-gray-800 truncate">{{ auth()->user()->full_name ?? auth()->user()->username }}</p>
                        </div>
                    </div>

                    <nav class="space-y-1">
                        <a href="{{ route('account.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-md transition-colors {{ request()->routeIs('account.index') ? 'bg-brand-blue/10 text-brand-blue font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-blue' }}">
                            <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                            Bảng điều khiển
                        </a>
                        <a href="{{ route('account.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-md transition-colors {{ request()->routeIs('account.profile') ? 'bg-brand-blue/10 text-brand-blue font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-blue' }}">
                            <i class="fa-regular fa-id-badge w-5 text-center"></i>
                            Thông tin tài khoản
                        </a>
                        <a href="{{ route('account.addresses') }}" class="flex items-center gap-3 px-4 py-3 rounded-md transition-colors {{ request()->routeIs('account.addresses') ? 'bg-brand-blue/10 text-brand-blue font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-blue' }}">
                            <i class="fa-solid fa-location-dot w-5 text-center"></i>
                            Sổ địa chỉ
                        </a>
                        <a href="{{ route('account.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-md transition-colors {{ request()->routeIs('account.orders.*') ? 'bg-brand-blue/10 text-brand-blue font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-blue' }}">
                            <i class="fa-solid fa-clipboard-list w-5 text-center"></i>
                            Quản lý đơn hàng
                        </a>
                        <a href="{{ route('account.vouchers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-md transition-colors {{ request()->routeIs('account.vouchers.*') ? 'bg-brand-blue/10 text-brand-blue font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-blue' }}">
                            <i class="fa-solid fa-ticket w-5 text-center"></i>
                            Kho Voucher
                        </a>
                        <a href="{{ route('account.password') }}" class="flex items-center gap-3 px-4 py-3 rounded-md transition-colors {{ request()->routeIs('account.password') ? 'bg-brand-blue/10 text-brand-blue font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-blue' }}">
                            <i class="fa-solid fa-shield-halved w-5 text-center"></i>
                            Bảo mật
                        </a>
                        
                        <div class="h-px bg-gray-200 my-4"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-md transition-colors text-red-600 hover:bg-red-50">
                                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                                Đăng xuất
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="w-full lg:w-3/4">
                @yield('account_content')
            </div>
        </div>
    </div>
</div>
@endsection
