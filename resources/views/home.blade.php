@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 lg:px-8 pt-4 pb-12">
        <!-- Hero Slider component -->
        <x-hero-slider />

        <!-- Hot Products Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-4 pb-2 border-b-2 border-brand-yellow">
                <h2 class="text-2xl font-bold uppercase text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-fire text-red-500"></i> Sản phẩm nổi bật
                </h2>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($hotProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>

        <!-- Category Grids -->
        @foreach($categories as $category)
            @if(isset($categoryProducts[$category->id]) && $categoryProducts[$category->id]->count() > 0)
                <div class="mb-12 bg-white rounded-lg p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                        <h2 class="text-xl font-bold uppercase text-gray-800">
                            {{ $category->name }}
                        </h2>
                        <a href="/categories/{{ $category->slug }}" class="text-brand-blue hover:underline text-sm font-medium">Xem
                            tất cả <i class="fa-solid fa-chevron-right text-xs"></i></a>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                        @foreach($categoryProducts[$category->id] as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection