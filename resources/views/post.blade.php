@extends('layouts.app')

@push('styles')
<style>
    .blog-content { line-height: 1.75; font-size: 1.05rem; color: #1f2937; }
    .blog-content p { margin: 0 0 1.1em; }
    .blog-content h1, .blog-content h2, .blog-content h3, .blog-content h4 {
        font-weight: 700; color: #111827; margin: 1.4em 0 0.6em; line-height: 1.3;
    }
    .blog-content h1 { font-size: 1.9rem; }
    .blog-content h2 { font-size: 1.55rem; }
    .blog-content h3 { font-size: 1.3rem; }
    .blog-content h4 { font-size: 1.15rem; }
    .blog-content strong, .blog-content b { font-weight: 700; color: #111827; }
    .blog-content em, .blog-content i { font-style: italic; }
    .blog-content a { color: #288ad6; text-decoration: underline; }
    .blog-content a:hover { color: #1976b0; }
    .blog-content ul, .blog-content ol { margin: 0 0 1.1em 1.5em; padding-left: 1em; }
    .blog-content ul { list-style: disc; }
    .blog-content ol { list-style: decimal; }
    .blog-content li { margin-bottom: 0.4em; }
    .blog-content li > ul, .blog-content li > ol { margin-top: 0.4em; margin-bottom: 0.4em; }
    .blog-content blockquote {
        border-left: 4px solid #fed700; background: #fffbeb;
        padding: 0.8em 1.2em; margin: 1.2em 0; font-style: italic; color: #4b5563;
    }
    .blog-content img {
        max-width: 100%; height: auto; border-radius: 8px;
        margin: 1.2em 0; display: block;
    }
    .blog-content table {
        width: 100%; border-collapse: collapse; margin: 1.2em 0;
    }
    .blog-content table th, .blog-content table td {
        border: 1px solid #e5e7eb; padding: 0.6em 0.9em; text-align: left;
    }
    .blog-content table th { background: #f9fafb; font-weight: 600; }
    .blog-content code {
        background: #f3f4f6; padding: 0.15em 0.4em; border-radius: 4px;
        font-family: ui-monospace, monospace; font-size: 0.9em;
    }
    .blog-content pre {
        background: #1f2937; color: #f9fafb; padding: 1em; border-radius: 8px;
        overflow-x: auto; margin: 1.2em 0;
    }
    .blog-content pre code { background: transparent; color: inherit; padding: 0; }
    .blog-content hr { border: 0; border-top: 1px solid #e5e7eb; margin: 2em 0; }
</style>
@endpush

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

            <div class="prose prose-lg max-w-none text-gray-800 blog-content">
                {!! $post->content !!}
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