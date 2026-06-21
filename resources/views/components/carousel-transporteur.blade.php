@props(['transporteur'])

<div class="group rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl" 
     style="background-color: var(--color-bg-white); box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid var(--color-nav-border);">
    
    <div class="relative h-28 flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-secondary), var(--color-secondary-light));">
        @php
            $avatar = $transporteur->avatar ?? null;
        @endphp
        
        @if($avatar && file_exists(public_path($avatar)))
            <img src="{{ asset($avatar) }}" 
                 alt="Photo" 
                 class="w-16 h-16 rounded-full object-cover border-3 shadow-lg"
                 style="border-color: var(--color-primary);">
        @else
            <div class="w-16 h-16 rounded-full flex items-center justify-center border-3 shadow-lg"
                 style="border-color: var(--color-primary); background-color: var(--color-bg-white);">
                <i class="fas fa-truck text-2xl" style="color: var(--color-primary);"></i>
            </div>
        @endif
    </div>
    
    <div class="p-3">
        <h4 class="text-sm font-bold mb-0.5 text-center" style="color: var(--color-nav-text);">
            {{ $transporteur->prenom ?? '' }} {{ Str::limit($transporteur->nom ?? 'Transporteur', 10) }}
        </h4>
        
        <p class="text-[10px] text-center mb-2" style="color: var(--color-nav-text); opacity: 0.6;">
            <i class="fas fa-map-marker-alt mr-1" style="color: var(--color-primary);"></i>
            {{ $transporteur->transporteur->zone_intervention ?? $transporteur->ville ?? $transporteur->commune ?? 'Bénin' }}
        </p>
        
        <div class="space-y-1 mb-2">
            <div class="flex items-center justify-between text-[10px]">
                <span style="color: var(--color-nav-text); opacity: 0.6;">
                    <i class="fas fa-truck mr-1" style="color: var(--color-primary);"></i>
                    Véhicule
                </span>
                <span class="font-semibold" style="color: var(--color-nav-text);">
                    {{ Str::limit($transporteur->transporteur->type_vehicule ?? 'N/A', 12) }}
                </span>
            </div>
            <div class="flex items-center justify-between text-[10px]">
                <span style="color: var(--color-nav-text); opacity: 0.6;">
                    <i class="fas fa-weight-hanging mr-1" style="color: var(--color-primary);"></i>
                    Capacité
                </span>
                <span class="font-semibold" style="color: var(--color-nav-text);">
                    {{ $transporteur->transporteur->capacite_transport ?? 'N/A' }} kg
                </span>
            </div>
        </div>
        
        <a href="#" 
           class="flex items-center justify-center gap-1.5 w-full py-1.5 rounded-lg text-[10px] font-medium transition-all duration-300 text-white"
           style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
           onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'; this.style.transform='translateY(-1px)'"
           onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'; this.style.transform='translateY(0)'">
            <i class="fas fa-phone-alt text-[8px]"></i>
            Contacter
        </a>
    </div>
</div>