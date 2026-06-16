@props([
    'title',
    'subtitle',
    'icon',
    'badge',
    'items',
    'route',
    'routeText',
    'type' => 'annonce',
    'bgColor' => 'var(--bg-section-light)',
    'textColor' => 'var(--color-nav-text)',
    'titleColor' => 'var(--color-primary)',
    'badgeBg' => 'rgba(255,255,255,0.15)',
    'badgeText' => 'var(--color-primary-light)',
])

<section class="py-16" style="background-color: {{ $bgColor }}; transition: background-color 0.3s ease;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-10" data-aos="fade-up">
            <div class="inline-flex items-center px-5 py-2.5 rounded-full mb-4" 
                 style="background: {{ $badgeBg }}; border: 1px solid var(--color-primary); backdrop-filter: blur(10px);">
                <i class="fas fa-{{ $icon }} mr-2" style="color: var(--color-primary);"></i>
                <span class="text-sm font-semibold" style="color: {{ $badgeText }};">{{ $badge }}</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold mb-3" style="color: {{ $textColor }};">
                {{ $title }} <span style="color: {{ $titleColor }};">{{ $subtitle }}</span>
            </h2>
            <p class="text-sm max-w-2xl mx-auto" style="color: {{ $textColor }}; opacity: 0.7;">
                {{ $slot }}
            </p>
            <div class="w-20 h-1 mx-auto mt-4 rounded-full" style="background: linear-gradient(90deg, var(--color-primary), var(--color-primary-dark));"></div>
        </div>

        <!-- Carrousel -->
        <div class="relative" x-data="carousel({{ $items->count() }})" x-init="init()">
            <!-- Conteneur des items -->
            <div class="overflow-hidden rounded-xl">
                <div class="flex transition-transform duration-700 ease-in-out" 
                     x-bind:style="`transform: translateX(-${currentIndex * (100 / itemsPerView)}%);`">
                    @foreach($items as $item)
                        <div class="flex-shrink-0 px-2" 
                             x-bind:style="`width: ${100 / itemsPerView}%;`"
                             x-show="true">
                            @if($type === 'annonce')
                                @include('components.carousel-annonce', ['annonce' => $item])
                            @elseif($type === 'veterinaire')
                                @include('components.carousel-veterinaire', ['veterinaire' => $item])
                            @elseif($type === 'transporteur')
                                @include('components.carousel-transporteur', ['transporteur' => $item])
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Boutons de navigation -->
            @if($items->count() > 3)
                <button @click="prev()" 
                        class="absolute left-0 top-1/2 -translate-y-1/2 -ml-5 z-10 w-12 h-12 rounded-full shadow-2xl transition-all duration-300 hover:scale-110 flex items-center justify-center border-2"
                        style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); color: white; border-color: rgba(255,255,255,0.3);"
                        x-show="currentIndex > 0"
                        x-transition>
                    <i class="fas fa-chevron-left text-lg"></i>
                </button>
                
                <button @click="next()" 
                        class="absolute right-0 top-1/2 -translate-y-1/2 -mr-5 z-10 w-12 h-12 rounded-full shadow-2xl transition-all duration-300 hover:scale-110 flex items-center justify-center border-2"
                        style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); color: white; border-color: rgba(255,255,255,0.3);"
                        x-show="currentIndex < totalPages - 1"
                        x-transition>
                    <i class="fas fa-chevron-right text-lg"></i>
                </button>
            @endif

            <!-- Indicateurs -->
            @if($items->count() > 3)
                <div class="flex justify-center gap-2 mt-6">
                    <template x-for="i in totalPages" :key="i">
                        <button @click="goTo(i - 1)" 
                                class="h-2 rounded-full transition-all duration-300"
                                x-bind:class="{'w-8': currentIndex === i - 1, 'w-2': currentIndex !== i - 1}"
                                x-bind:style="currentIndex === i - 1 ? 'background: var(--color-primary);' : 'background: rgba(255,255,255,0.3);'">
                        </button>
                    </template>
                </div>
            @endif
        </div>

        <!-- Voir tout -->
        @if($items->count() > 0)
            <div class="text-center mt-10">
                <a href="{{ $route }}" 
                   class="inline-flex items-center gap-2 font-semibold transition-all duration-300 group text-base px-6 py-3 rounded-xl border-2"
                   style="color: {{ $textColor }}; border-color: var(--color-primary);"
                   onmouseover="this.style.background='var(--color-primary)'; this.style.color='white'; this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.background='transparent'; this.style.color='{{ $textColor }}'; this.style.transform='translateY(0)'">
                    {{ $routeText }}
                    <i class="fas fa-arrow-right transition-transform duration-300 group-hover:translate-x-2"></i>
                </a>
            </div>
        @endif
    </div>
</section>

<script>
    function carousel(totalItems) {
        return {
            currentIndex: 0,
            itemsPerView: 3,
            totalPages: Math.ceil(totalItems / 3),
            timer: null,
            
            init() {
                this.updateItemsPerView();
                window.addEventListener('resize', () => this.updateItemsPerView());
                this.startAutoPlay();
                this.$el.addEventListener('mouseenter', () => this.stopAutoPlay());
                this.$el.addEventListener('mouseleave', () => this.startAutoPlay());
            },
            
            updateItemsPerView() {
                const width = window.innerWidth;
                if (width < 640) {
                    this.itemsPerView = 1;
                } else if (width < 768) {
                    this.itemsPerView = 2;
                } else {
                    this.itemsPerView = 3;
                }
                this.totalPages = Math.ceil(totalItems / this.itemsPerView);
                if (this.currentIndex >= this.totalPages) {
                    this.currentIndex = this.totalPages - 1;
                }
            },
            
            next() {
                if (this.currentIndex < this.totalPages - 1) {
                    this.currentIndex++;
                } else {
                    this.currentIndex = 0;
                }
            },
            
            prev() {
                if (this.currentIndex > 0) {
                    this.currentIndex--;
                } else {
                    this.currentIndex = this.totalPages - 1;
                }
            },
            
            goTo(index) {
                this.currentIndex = index;
            },
            
            startAutoPlay() {
                this.stopAutoPlay();
                this.timer = setInterval(() => {
                    this.next();
                }, 10000);
            },
            
            stopAutoPlay() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            }
        }
    }
</script>