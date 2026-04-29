@extends('layouts.app')

@section('content')
    <!-- Breadcrumbs -->
    <div class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-4 lg:px-8 py-4">
            <nav class="flex text-sm text-gray-500 font-medium">
                <a href="/" class="hover:text-brand-blue"><i class="fa-solid fa-house"></i></a>
                <span class="mx-2">›</span>
                <a href="/blog" class="hover:text-brand-blue">Tin công nghệ</a>
                <span class="mx-2">›</span>
                <span class="text-gray-800 line-clamp-1">{{ $post->title }}</span>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 py-8 md:py-12 max-w-4xl">
        <article class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-10">
            <header class="mb-8">
                <div class="flex items-center gap-3 text-sm text-gray-500 mb-4 font-medium uppercase tracking-wider">
                    <span class="bg-brand-blue text-white px-3 py-1 rounded-full">{{ $post->category ?? 'Tin tức' }}</span>
                    <span><i class="fa-regular fa-clock bg-gray-50 text-gray-400 p-1 rounded-full mr-1"></i>
                        {{ optional($post->published_at)->format('d/m/Y - H:i') ?? 'Chưa đăng' }}</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-6">
                    {{ $post->title }}
                </h1>
                <div class="text-lg text-gray-600 font-medium leading-relaxed italic border-l-4 border-brand-yellow pl-4">
                    {{ $post->excerpt }}
                </div>
            </header>

            <figure class="mb-8 rounded-lg overflow-hidden border border-gray-100">
                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                    class="w-full h-auto max-h-[500px] object-cover" loading="lazy">
            </figure>

            <div class="prose prose-lg max-w-none text-gray-800">
                {!! nl2br(e($post->content)) !!}
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <h3 class="font-bold text-lg mb-4">Chia sẻ bài viết:</h3>
                <div class="flex gap-3">
                    <button
                        class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition"><i
                            class="fa-brands fa-facebook-f"></i></button>
                    <button
                        class="bg-blue-400 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-500 transition"><i
                            class="fa-brands fa-twitter"></i></button>
                    <button
                        class="bg-gray-800 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-900 transition"><i
                            class="fa-solid fa-link"></i></button>
                </div>
            </div>
        </article>
    </div>
@endsection