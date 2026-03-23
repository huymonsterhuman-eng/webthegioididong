@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-gray-500 mb-6 font-medium">
            <a href="/" class="hover:text-brand-blue"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">Tìm kiếm: {{ $query }}</span>
        </nav>

        <!-- Search Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Kết quả tìm kiếm cho: <span class="italic">"{{ $query }}"</span>
            </h1>
            <span class="text-gray-500">{{ $products->total() }} sản phẩm</span>
        </div>

        <!-- Product Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-lg shadow-sm">
                <i class="fa-solid fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Không tìm thấy kết quả nào</h3>
                <p class="text-gray-500 font-medium">Rất tiếc, chúng tôi không tìm thấy sản phẩm phù hợp với "{{ $query }}".</p>
                <div class="mt-6">
                    <a href="/"
                        class="inline-block bg-brand-yellow text-black font-semibold px-6 py-2 rounded shadow hover:bg-yellow-500 transition">
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection