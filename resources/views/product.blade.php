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
                    @php
                        $avgRating = round($product->averageRating());
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $avgRating)
                            <i class="fa-solid fa-star"></i>
                        @else
                            <i class="fa-regular fa-star"></i>
                        @endif
                    @endfor
                    <a href="#reviews" class="text-brand-blue ml-2 font-medium">{{ $product->reviews()->where('is_hidden', false)->count() }} Đánh giá</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left Column: Images & Overview -->
        <div class="col-span-1 lg:col-span-7 space-y-8">
            <div
                class="bg-white rounded-lg p-8 border border-gray-100 shadow-sm flex items-center justify-center min-h-[400px]">
                @php
                    if ($product->primaryImage) {
                        $p_imagePath = $product->primaryImage->path;
                    } else {
                        $p_imagePath = $product->image;
                    }
                    
                    $p_imgSrc = empty($p_imagePath)
                        ? asset('storage/img/placeholder.jpg')
                        : (Str::startsWith($p_imagePath, 'http') ? $p_imagePath : Storage::url($p_imagePath));
                @endphp

                @if(!empty($p_imagePath))
                    <img src="{{ $p_imgSrc }}" alt="{{ $product->name }}"
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

                <div class="mb-4">
                    @if($product->stock > 0)
                        <span class="inline-flex items-center gap-1 text-green-600 font-medium bg-green-50 px-3 py-1 rounded-full text-sm border border-green-200">
                            <i class="fa-solid fa-check-circle"></i> Còn {{ $product->stock }} sản phẩm
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-red-600 font-medium bg-red-50 px-3 py-1 rounded-full text-sm border border-red-200">
                            <i class="fa-solid fa-xmark-circle"></i> Hết hàng
                        </span>
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

                <button 
                    @if($product->stock > 0)
                        @click.prevent="addToCart({
                            id: {{ $product->id }},
                            name: '{{ addslashes($product->name) }}',
                            price: {{ $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price }},
                            image: '{{ $p_imgSrc }}',
                            stock: {{ $product->stock }}
                        })"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-lg shadow uppercase text-lg transition"
                    @else
                        disabled
                        class="w-full bg-gray-400 text-white font-bold py-4 rounded-lg shadow uppercase text-lg cursor-not-allowed"
                    @endif
                >
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

    <!-- REVIEWS SECTION -->
    <div id="reviews" class="container mx-auto px-4 lg:px-8 py-8 mb-8 border-t border-gray-200">
        <div class="bg-white rounded-lg p-6 lg:p-10 shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Đánh giá sản phẩm</h2>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Left: Overall Rating & Form -->
                <div class="lg:col-span-4 border-b lg:border-b-0 lg:border-r border-gray-100 pr-0 lg:pr-8 pb-8 lg:pb-0">
                    <div class="text-center mb-8">
                        <div class="text-5xl font-black text-brand-blue mb-2">{{ number_format($product->averageRating(), 1) }}</div>
                        <div class="text-yellow-500 text-xl space-x-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($product->averageRating()))
                                    <i class="fa-solid fa-star"></i>
                                @else
                                    <i class="fa-regular fa-star text-gray-300"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="text-sm text-gray-500">{{ $product->reviews()->where('is_hidden', false)->count() }} đánh giá</div>
                    </div>

                    @auth
                        @if(!$product->reviews()->where('user_id', auth()->id())->exists())
                            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                                <h3 class="font-bold text-gray-800 mb-4">Gửi đánh giá của bạn</h3>
                                <form action="{{ route('reviews.store', $product) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Star Rating Input -->
                                    <div class="mb-4" x-data="{ rating: 0, hoverRating: 0 }">
                                        <p class="text-sm text-gray-600 mb-2">Bạn chấm sản phẩm này bao nhiêu sao?</p>
                                        <div class="flex space-x-2 text-2xl text-gray-300 cursor-pointer">
                                            <template x-for="i in 5">
                                                <i class="fa-solid fa-star transition-colors"
                                                   :class="{'text-yellow-500': i <= (hoverRating || rating)}"
                                                   @mouseenter="hoverRating = i"
                                                   @mouseleave="hoverRating = 0"
                                                   @click="rating = i"></i>
                                            </template>
                                        </div>
                                        <input type="hidden" name="rating" :value="rating" required>
                                        @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Comment -->
                                    <div class="mb-4">
                                        <textarea name="comment" rows="3" placeholder="Xin mời chia sẻ một số cảm nhận về sản phẩm..." class="w-full rounded-md border-gray-300 focus:border-brand-blue focus:ring-brand-blue" required></textarea>
                                        @error('comment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Image Upload -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Đính kèm ảnh (tùy chọn)</label>
                                        <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-blue file:text-white hover:file:bg-blue-600">
                                        @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <button type="submit" class="w-full bg-brand-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                        Gửi Đánh Giá
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-blue-50 text-brand-blue p-4 rounded-lg text-center font-medium border border-blue-100">
                                Bạn đã đánh giá sản phẩm này. Cảm ơn phản hồi của bạn!
                            </div>
                        @endif
                    @else
                        <div class="bg-gray-50 p-6 rounded-lg text-center border border-gray-200">
                            <p class="text-gray-600 mb-4">Vui lòng đăng nhập để gửi đánh giá cho sản phẩm này.</p>
                            <a href="{{ route('login') }}" class="inline-block bg-brand-blue text-white px-6 py-2 rounded font-medium hover:bg-blue-700 transition">Đăng nhập</a>
                        </div>
                    @endauth
                </div>

                <!-- Right: List of Reviews -->
                <div class="lg:col-span-8 space-y-6">
                    @php
                        $reviews = $product->reviews()->where('is_hidden', false)->latest()->get();
                    @endphp

                    @if($reviews->count() > 0)
                        @foreach($reviews as $review)
                            <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                                <div class="flex items-center mb-2">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-500 mr-3">
                                        {{ substr($review->user->username, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $review->user->username }}</div>
                                        <div class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                
                                <div class="text-yellow-500 text-sm mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fa-solid fa-star"></i>
                                        @else
                                            <i class="fa-regular fa-star text-gray-300"></i>
                                        @endif
                                    @endfor
                                </div>

                                <p class="text-gray-700 mb-3">{{ $review->comment }}</p>

                                @if($review->images && is_array($review->images) && count($review->images) > 0)
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($review->images as $img)
                                            <img src="{{ Storage::url($img) }}" 
                                                 class="h-24 w-24 object-cover rounded shadow-sm border border-gray-200 hover:scale-110 transition cursor-pointer" 
                                                 onclick="window.open(this.src, '_blank')">
                                        @endforeach
                                    </div>
                                @endif

                                @if($review->admin_reply)
                                    <div class="bg-gray-50 p-4 rounded-lg mt-3 border-l-4 border-brand-blue relative">
                                        <i class="fa-solid fa-reply absolute top-4 right-4 text-gray-300 text-xl"></i>
                                        <p class="font-bold text-sm text-gray-800 mb-1">QTV Thế Giới Di Động phản hồi:</p>
                                        <p class="text-sm text-gray-600">{{ $review->admin_reply }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <i class="fa-regular fa-comment-dots text-5xl mb-3 text-gray-300"></i>
                            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                            <p class="text-sm">Hãy là người đầu tiên chia sẻ cảm nhận!</p>
                        </div>
                    @endif
                </div>
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