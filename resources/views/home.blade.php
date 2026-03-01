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

        <!-- Tin Công Nghệ (Blog Section) -->
        <div class="mb-12 bg-white rounded-lg p-4 shadow-sm border border-gray-100 mt-8">
            <div class="flex items-center justify-between mb-4 pb-2 border-b-2 border-brand-yellow">
                <h2 class="text-2xl font-bold uppercase text-gray-800">
                    Tin công nghệ
                </h2>
                <a href="/blog" class="text-brand-blue hover:underline text-sm font-medium">Xem tất cả <i
                        class="fa-solid fa-chevron-right text-xs"></i></a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <div
                        class="group border rounded-lg overflow-hidden flex flex-col hover:shadow-lg transition-shadow bg-white">
                        <a href="/blog/{{ $post->slug }}" class="block relative overflow-hidden h-48">
                            <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                        </a>
                        <div class="p-4 flex flex-col flex-grow">
                            <div class="text-xs text-gray-500 mb-2">{{ \Carbon\Carbon::parse($post->date)->format('d/m/Y') }}
                            </div>
                            <h3
                                class="font-bold text-gray-800 mb-2 line-clamp-2 leading-snug group-hover:text-brand-blue transition">
                                <a href="/blog/{{ $post->slug }}">{{ $post->title }}</a>
                            </h3>
                            <p class="text-sm text-gray-600 line-clamp-3 mb-4">{{ $post->excerpt }}</p>
                            <a href="/blog/{{ $post->slug }}"
                                class="mt-auto text-sm text-brand-blue font-medium hover:underline inline-flex items-center gap-1">
                                Đọc tiếp <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection