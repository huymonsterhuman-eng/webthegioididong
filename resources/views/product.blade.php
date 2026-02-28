@extends('layouts.app')

@section('content')
    <div class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-4 lg:px-8 py-4">
            <nav class="flex text-sm text-gray-500 font-medium">
                <a href="/" class="hover:text-brand-blue"><i class="fa-solid fa-house"></i></a>
                <span class="mx-2">›</span>
                @if($product->category)
                    <a href="/categories/{{ $product->category->slug }}"
                        class="hover:text-brand-blue">{{ $product->category->name }}</a>
                    <span class="mx-2">›</span>
                @endif
                <span class="text-gray-800">{{ $product->name }}</span>
            </nav>
            <div class="mt-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
                <div class="flex items-center text-yellow-500 text-sm">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                        class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                    <a href="#reviews" class="text-brand-blue ml-2 font-medium">12 Đánh giá</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left Column: Images & Overview -->
        <div class="col-span-1 lg:col-span-7 space-y-8">
            <!-- Main Image -->
            <div
                class="bg-white rounded-lg p-8 border border-gray-100 shadow-sm flex items-center justify-center min-h-[400px]">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                        class="w-full max-w-md object-contain">
                @else
                    <i class="fa-solid fa-mobile-screen text-9xl text-gray-200"></i>
                @endif
            </div>

            <!-- Description -->
            <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm content-prose">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Đặc điểm nổi bật</h2>
                @if($product->description)
                    <div class="text-gray-700 leading-relaxed space-y-4">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                @else
                    <p class="text-gray-500 italic">Chưa có bài viết đánh giá chi tiết cho sản phẩm này.</p>
                @endif
            </div>
        </div>

        <!-- Right Column: Price, Specs, CTA -->
        <div class="col-span-1 lg:col-span-5 space-y-6">

            <!-- Price Block -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-inner">
                <div class="mb-4">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <div class="flex items-end gap-3 mb-1">
                            <span
                                class="text-red-600 font-bold text-3xl">{{ number_format($product->sale_price, 0, ',', '.') }}₫</span>
                            <span
                                class="bg-red-100 text-red-600 text-sm font-bold px-2 py-0.5 rounded">-{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%</span>
                        </div>
                        <div class="text-gray-400 line-through text-lg">{{ number_format($product->price, 0, ',', '.') }}₫</div>
                    @else
                        <div class="text-red-600 font-bold text-3xl">{{ number_format($product->price, 0, ',', '.') }}₫</div>
                    @endif
                </div>

                <!-- Fake Promos -->
                <div class="border border-brand-blue border-dashed rounded bg-white p-3 mb-6">
                    <p class="text-sm font-bold text-brand-blue mb-2 uppercase">Khuyến mãi & Ưu đãi</p>
                    <ul class="text-sm text-gray-700 space-y-2 list-disc pl-4">
                        <li>Giảm thêm tới 300K khi thanh toán qua VNPay.</li>
                        <li>Thu cũ đổi mới trợ giá 2 Triệu.</li>
                        <li>Mua kèm phụ kiện giảm 50%.</li>
                    </ul>
                </div>

                <button @click.prevent="addToCart({
                        id: {{ $product->id }},
                        name: '{{ addslashes($product->name) }}',
                        price: {{ $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price }},
                        image: '{{ $product->image ? Storage::url($product->image) : '' }}'
                    })"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-lg shadow uppercase text-lg transition">
                    Mua ngay
                    <span class="block text-xs font-normal Normal">Giao hàng miễn phí hoặc nhận tại shop</span>
                </button>
            </div>

            <!-- Specifications block -->
            <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold mb-4">Cấu hình {{ $product->name }}</h2>
                <ul class="text-sm border rounded">
                    @if($product->screen)
                        <li class="flex py-2 px-3 border-b bg-gray-50"><span class="w-1/3 text-gray-500">Màn hình:</span><span
                                class="w-2/3 font-medium">{{ $product->screen }}</span></li>
                    @endif
                    @if($product->os)
                        <li class="flex py-2 px-3 border-b"><span class="w-1/3 text-gray-500">Hệ điều hành:</span><span
                                class="w-2/3 font-medium">{{ $product->os }}</span></li>
                    @endif
                    @if($product->cameraorsensors)
                        <li class="flex py-2 px-3 border-b bg-gray-50"><span class="w-1/3 text-gray-500">Camera:</span><span
                                class="w-2/3 font-medium">{{ $product->cameraorsensors }}</span></li>
                    @endif
                    @if($product->chip)
                        <li class="flex py-2 px-3 border-b"><span class="w-1/3 text-gray-500">Chip:</span><span
                                class="w-2/3 font-medium">{{ $product->chip }}</span></li>
                    @endif
                    @if($product->battery)
                        <li class="flex py-2 px-3 bg-gray-50"><span class="w-1/3 text-gray-500">Pin:</span><span
                                class="w-2/3 font-medium">{{ $product->battery }}</span></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
        <div class="container mx-auto px-4 lg:px-8 pb-12">
            <h2 class="text-xl font-bold uppercase text-gray-800 mb-6">Sản phẩm tương tự</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </div>
    @endif
@endsection