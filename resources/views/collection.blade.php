@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-gray-500 mb-6 font-medium">
            <a href="/" class="hover:text-brand-blue"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">{{ $collection->name }}</span>
        </nav>

        <!-- Collection Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 uppercase">{{ $collection->name }}</h1>
            <span class="text-gray-500">{{ $products->total() }} sản phẩm</span>
        </div>
        
        @if($collection->description)
        <div class="bg-blue-50 text-blue-800 p-4 rounded mb-6 text-sm">
            {{ $collection->description }}
        </div>
        @endif

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-4 items-center justify-between">
            <!-- Brand Filter -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 scrollbar-hide flex-grow">
                <span class="font-bold text-gray-700 whitespace-nowrap hidden sm:inline">Hãng:</span>
                <a href="?brand="
                    class="px-4 py-1.5 border rounded-full text-sm hover:border-brand-blue hover:text-brand-blue transition {{ !request('brand') ? 'border-brand-blue text-brand-blue bg-blue-50' : 'text-gray-600' }}">Tất
                    cả</a>
                @foreach($brands as $brand)
                    <a href="?brand={{ $brand->slug }}{{ request('sort') ? '&sort=' . request('sort') : '' }}"
                        class="px-4 py-1.5 border rounded-full text-sm hover:border-brand-blue hover:text-brand-blue transition {{ request('brand') == $brand->slug ? 'border-brand-blue text-brand-blue bg-blue-50' : 'text-gray-600' }}">
                        {{ $brand->name }}
                    </a>
                @endforeach
            </div>

            <!-- Sort Select -->
            <div class="flex-shrink-0">
                <form action="" method="GET" class="flex items-center gap-2">
                    @if(request('brand'))
                        <input type="hidden" name="brand" value="{{ request('brand') }}">
                    @endif
                    <select name="sort" onchange="this.form.submit()"
                        class="border-gray-300 rounded text-sm text-gray-700 py-1.5 focus:ring-brand-blue focus:border-brand-blue">
                        <option value="">Sắp xếp: Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến cao
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến thấp
                        </option>
                    </select>
                </form>
            </div>
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
                <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-medium">Không có sản phẩm nào trong bộ sưu tập này.</p>
            </div>
        @endif
    </div>
@endsection
