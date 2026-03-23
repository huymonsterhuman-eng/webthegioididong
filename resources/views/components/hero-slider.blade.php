@props(['banners' => collect()])

@php
    // Prepare the banners for Alpine to map out.
    // If no banners exist in the database, we provide a fallback default set just to keep the component working gracefully.
    $slidesData = $banners->isEmpty() ? collect([
        ['image' => asset('storage/banners/banner1.png'), 'link' => null],
        ['image' => asset('storage/banners/banner2.png'), 'link' => null],
        ['image' => asset('storage/banners/banner3.png'), 'link' => null],
    ]) : $banners->map(function ($banner) {
        return [
            'image' => asset('storage/' . $banner->image),
            'link' => $banner->link
        ];
    });
@endphp

<div x-data="{
        activeSlide: 1,
        slides: {{ $slidesData->toJson() }},
        init() {
            if (this.slides.length > 1) {
                setInterval(() => {
                    this.activeSlide = this.activeSlide === this.slides.length ? 1 : this.activeSlide + 1
                }, 5000)
            }
        }
    }" class="relative w-full overflow-hidden mb-8 max-w-7xl mx-auto rounded-lg shadow-sm bg-gray-50 group">
    
    <!-- Invisible placeholder to dynamically set container height based on first slide -->
    <img :src="slides[0].image" class="w-full h-auto block opacity-0 pointer-events-none" alt="Placeholder">

    <!-- Slides -->
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="activeSlide === index + 1" x-transition.opacity.duration.500ms
            class="absolute inset-0 w-full h-full flex items-center justify-center">
            <template x-if="slide.link">
                <a :href="slide.link" class="w-full h-full block">
                    <img :src="slide.image" class="w-full h-full object-contain rounded-lg" alt="Banner">
                </a>
            </template>
            <template x-if="!slide.link">
                <img :src="slide.image" class="w-full h-full object-contain rounded-lg" alt="Banner">
            </template>
        </div>
    </template>

    <!-- Navigation Dots -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2" x-show="slides.length > 1">
        <template x-for="i in slides.length">
            <button @click="activeSlide = i" class="w-3 h-3 rounded-full transition-colors"
                :class="activeSlide === i ? 'bg-brand-blue' : 'bg-gray-300 hover:bg-white'">
            </button>
        </template>
    </div>

    <!-- Navigation Arrows -->
    <button x-show="slides.length > 1" @click="activeSlide = activeSlide === 1 ? slides.length : activeSlide - 1"
        class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/20 hover:bg-black/40 text-white rounded-full flex items-center justify-center transition opacity-0 group-hover:opacity-100">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button x-show="slides.length > 1" @click="activeSlide = activeSlide === slides.length ? 1 : activeSlide + 1"
        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/20 hover:bg-black/40 text-white rounded-full flex items-center justify-center transition opacity-0 group-hover:opacity-100">
        <i class="fa-solid fa-chevron-right"></i>
    </button>
</div>