@props(['veterinaire'])

<div class="group rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl" 
     style="background-color: var(--color-bg-white); box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid var(--color-nav-border);">
    
    <div class="relative h-28 flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
        @php
            $avatar = $veterinaire->avatar ?? null;
        @endphp
        
        @if($avatar && file_exists(public_path($avatar)))
            <img src="{{ asset($avatar) }}" 
                 alt="Photo" 
                 class="w-16 h-16 rounded-full object-cover border-3 shadow-lg"
                 style="border-color: white;">
        @else
            <div class="w-16 h-16 rounded-full flex items-center justify-center border-3 shadow-lg"
                 style="border-color: white; background-color: var(--color-bg-white);">
                <i class="fas fa-user-md text-2xl" style="color: var(--color-primary);"></i>
            </div>
        @endif
        
        <div class="absolute bottom-1 right-2 px-2 py-0.5 rounded-full text-[8px] font-semibold text-white backdrop-blur-md"
             style="background: rgba(0,0,0,0.5);">
            <i class="fas fa-check-circle text-green-400 mr-0.5"></i>
            Disponible
        </div>
    </div>
    
    <div class="p-3">
        <h4 class="text-sm font-bold mb-0.5 text-center" style="color: var(--color-nav-text);">
            Dr. {{ $veterinaire->prenom ?? '' }} {{ Str::limit($veterinaire->nom ?? 'Vétérinaire', 10) }}
        </h4>
        
        <p class="text-[10px] text-center mb-2" style="color: var(--color-nav-text); opacity: 0.6;">
            <i class="fas fa-map-marker-alt mr-1" style="color: var(--color-primary);"></i>
            {{ $veterinaire->ville ?? $veterinaire->commune ?? 'Bénin' }}
        </p>
        
        @if($veterinaire->veterinaire && $veterinaire->veterinaire->specialites)
            <div class="flex items-center gap-1.5 mb-1.5">
                <i class="fas fa-graduation-cap text-[10px]" style="color: var(--color-primary);"></i>
                <span class="text-[10px] line-clamp-1" style="color: var(--color-nav-text); opacity: 0.8;">
                    {{ Str::limit($veterinaire->veterinaire->specialites, 25) }}
                </span>
            </div>
        @endif
        
        <div class="flex items-center gap-1.5 mb-2">
            <i class="fas fa-star text-[10px]" style="color: var(--color-primary);"></i>
            <div class="flex items-center">
                <i class="fas fa-star text-[8px]" style="color: var(--color-primary);"></i>
                <i class="fas fa-star text-[8px]" style="color: var(--color-primary);"></i>
                <i class="fas fa-star text-[8px]" style="color: var(--color-primary);"></i>
                <i class="fas fa-star text-[8px]" style="color: var(--color-primary);"></i>
                <i class="fas fa-star-half-alt text-[8px]" style="color: var(--color-primary);"></i>
                <span class="text-[8px] ml-0.5" style="color: var(--color-nav-text); opacity: 0.6;">(4.5)</span>
            </div>
        </div>
        
        <div class="flex gap-1.5">
            <a href="#" 
               class="flex-1 text-center py-1.5 rounded-lg text-[10px] font-medium transition-all duration-300"
               style="background: var(--color-secondary-light); color: var(--color-primary-dark);"
               onmouseover="this.style.background='var(--color-secondary)'"
               onmouseout="this.style.background='var(--color-secondary-light)'">
                <i class="fas fa-user text-[8px] mr-0.5"></i> Profil
            </a>
            <a href="#" 
               class="flex-1 text-center py-1.5 rounded-lg text-[10px] font-medium transition-all duration-300 text-white"
               style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
               onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'"
               onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'">
                <i class="fas fa-calendar-plus text-[8px] mr-0.5"></i> RDV
            </a>
        </div>
    </div>
</div>