<div x-data="{
        activeSlide: 1,
        slides: [
            '/images/banner1.jpg',
            '/images/banner2.jpg',
            '/images/banner3.jpg'
        ],
        init() {
            setInterval(() => {
                this.activeSlide = this.activeSlide === this.slides.length ? 1 : this.activeSlide + 1
            }, 5000)
        }
    }" class="relative w-full overflow-hidden mb-8 max-w-7xl mx-auto rounded-lg shadow-sm"
    style="aspect-ratio: 1200 / 300; background-color: #f1f2f6;">
    <!-- Slides -->
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="activeSlide === index + 1" x-transition.opacity.duration.500ms
            class="absolute inset-0 w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 text-2xl font-bold">
            <!-- Simulated Banner since we don't have actual banner images yet -->
            <img x-show="false" :src="slide" class="w-full h-full object-cover">
            <span x-text="'Mega Banner ' + (index + 1) + ' (Placeholder)'"></span>
        </div>
    </template>

    <!-- Navigation Dots -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
        <template x-for="i in slides.length">
            <button @click="activeSlide = i" class="w-3 h-3 rounded-full transition-colors"
                :class="activeSlide === i ? 'bg-white' : 'bg-white/50 hover:bg-white/80'">
            </button>
        </template>
    </div>

    <!-- Navigation Arrows -->
    <button @click="activeSlide = activeSlide === 1 ? slides.length : activeSlide - 1"
        class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/20 hover:bg-black/40 text-white rounded-full flex items-center justify-center transition">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button @click="activeSlide = activeSlide === slides.length ? 1 : activeSlide + 1"
        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/20 hover:bg-black/40 text-white rounded-full flex items-center justify-center transition">
        <i class="fa-solid fa-chevron-right"></i>
    </button>
</div>