<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
    @if($settings->logo && file_exists(public_path($settings->logo)))
        <img src="{{ asset($settings->logo) }}" alt="{{ config('app.name', 'AgriHub') }}" class="w-20 h-20 object-contain">
    @else
        <div class="w-20 h-20 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <i class="fas fa-seedling text-white text-3xl"></i>
        </div>
    @endif
</div>