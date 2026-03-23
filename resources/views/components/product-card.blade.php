@props(['product'])

<div
    class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow border border-gray-100 p-4 group relative flex flex-col h-full">
    <!-- Image -->
    <a href="{{ route('product.show', ['categorySlug' => $product->category ? $product->category->slug : 'san-pham', 'productSlug' => $product->slug]) }}"
        class="block relative overflow-hidden h-48 mb-4 group-hover:-translate-y-1 transition-transform">

        @php
            $imgSrc = empty($product->image)
                ? asset('storage/img/placeholder.jpg')
                : (Str::startsWith($product->image, 'http') ? $product->image : Storage::url($product->image));
        @endphp
        <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="object-contain w-full h-full {{ $product->stock <= 0 ? 'opacity-40 grayscale' : '' }}" loading="lazy">

        @if($product->stock <= 0)
            <div class="absolute inset-0 bg-gray-800/60 flex items-center justify-center">
                <span class="text-white font-bold text-base px-3 py-1 bg-gray-700/70 rounded">Tạm hết hàng</span>
            </div>
        @elseif($product->sale_price && $product->sale_price < $product->price)
            <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
            </div>
        @endif
    </a>

    <!-- Details -->
    <div class="flex-grow flex flex-col justify-between">
        <div>
            <!-- Tags/Spec summary -->
            @if($product->screen || $product->chip)
                <div class="flex flex-wrap gap-1 mb-2">
                    @if($product->screen) <span
                        class="bg-gray-100 text-gray-600 text-[10px] px-2 py-0.5 rounded">{{ Str::limit($product->screen, 15) }}</span>
                    @endif
                    @if($product->chip) <span
                        class="bg-gray-100 text-gray-600 text-[10px] px-2 py-0.5 rounded">{{ Str::limit($product->chip, 15) }}</span>
                    @endif
                </div>
            @endif

            <h3 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2 min-h-[40px]">
                <a href="{{ route('product.show', ['categorySlug' => $product->category ? $product->category->slug : 'san-pham', 'productSlug' => $product->slug]) }}"
                    class="hover:text-brand-blue">{{ $product->name }}</a>
            </h3>

            <!-- Price -->
            <div class="mb-3">
                @if($product->sale_price && $product->sale_price < $product->price)
                    <div class="text-red-600 font-bold text-lg leading-tight">
                        {{ number_format($product->sale_price, 0, ',', '.') }}₫
                    </div>
                    <div class="text-gray-400 line-through text-sm mt-0.5">
                        {{ number_format($product->price, 0, ',', '.') }}₫
                    </div>
                @else
                    <div class="text-red-600 font-bold text-lg leading-tight">
                        {{ number_format($product->price, 0, ',', '.') }}₫
                    </div>
                @endif
            </div>

            <!-- Fake Rating -->
            <div class="flex items-center text-yellow-400 text-xs mb-3">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star-half-stroke"></i>
                <span class="text-gray-400 ml-1">(12)</span>
            </div>
        </div>

        <!-- Add to cart button using Alpine global state -->
        @if($product->stock > 0)
            <button @click.prevent="addToCart({
                    id: {{ $product->id }},
                    name: '{{ addslashes($product->name) }}',
                    price: {{ $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price }},
                    image: '{{ $product->image ? Storage::url($product->image) : Storage::url("img/placeholder.jpg") }}'
                })"
                class="w-full py-2 border border-brand-blue text-brand-blue rounded hover:bg-brand-blue hover:text-white transition font-medium text-sm mt-auto">
                Thêm vào giỏ
            </button>
        @else
            <button disabled
                class="w-full py-2 border border-gray-300 text-gray-400 rounded font-medium text-sm mt-auto cursor-not-allowed bg-gray-50">
                Tạm hết hàng
            </button>
        @endif
    </div>
</div>