@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 lg:px-8 py-8">
        <div class="mb-8 border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-800 uppercase">Tin công nghệ</h1>
            <p class="text-gray-500 mt-2">Cập nhật tin tức mới nhất về các sản phẩm công nghệ.</p>
        </div>

        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-lg transition-shadow">
                        <a href="/blog/{{ $post->slug }}" class="block relative overflow-hidden h-56">
                            <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                                class="object-cover w-full h-full hover:scale-105 transition-transform duration-300">
                        </a>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-3 uppercase font-medium tracking-wide">
                                <span class="bg-gray-100 px-2 py-1 rounded">{{ $post->category ?? 'Tin tức' }}</span>
                                <span>{{ optional($post->published_at)->format('d/m/Y') ?? 'Chưa đăng' }}</span>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2 hover:text-brand-blue transition">
                                <a href="/blog/{{ $post->slug }}">{{ $post->title }}</a>
                            </h2>
                            <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">{{ $post->excerpt }}</p>
                            <a href="/blog/{{ $post->slug }}"
                                class="mt-auto font-bold text-brand-blue hover:underline flex items-center gap-1">
                                Xem chi tiết <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-lg shadow-sm">
                <i class="fa-regular fa-newspaper text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-medium">Chưa có bài viết nào.</p>
            </div>
        @endif
    </div>
@endsection