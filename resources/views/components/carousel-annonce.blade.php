@props(['annonce'])

@php
    // Déterminer la route en fonction du type d'annonce
    $route = match($annonce->type) {
        'animal' => route('annonce.animal.show', $annonce->id),
        'nourriture' => route('annonce.aliment.show', $annonce->id),
        'accessoire' => route('annonce.accessoire.show', $annonce->id),
        'escrement' => route('annonce.escrement.show', $annonce->id),
        default => route('annonces.show', $annonce->id),
    };
    
    // Déterminer si l'utilisateur est le propriétaire
    $isOwner = Auth::check() && Auth::id() === $annonce->id_user;
@endphp

<div class="group rounded-xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:scale-[1.02]" 
     style="background-color: var(--color-bg-white); box-shadow: 0 4px 20px rgba(0,0,0,0.1); border: 1px solid var(--color-nav-border);">
    
    <div class="relative h-44 overflow-hidden">
        @php
            $imagePiece = $annonce->piecesJointes->where('est_principale', true)->first() ?? $annonce->piecesJointes->first();
        @endphp
        
        @if($imagePiece && $imagePiece->chemin_stockage && file_exists(storage_path('app/public/' . $imagePiece->chemin_stockage)))
            <img src="{{ asset('storage/' . $imagePiece->chemin_stockage) }}" 
                 alt="{{ $annonce->titre }}"
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
        @else
            <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-secondary), var(--color-secondary-light));">
                @if($annonce->type === 'animal')
                    <i class="fas fa-paw text-6xl" style="color: var(--color-primary); opacity: 0.4;"></i>
                @elseif($annonce->type === 'nourriture')
                    <i class="fas fa-apple-alt text-6xl" style="color: var(--color-primary); opacity: 0.4;"></i>
                @elseif($annonce->type === 'accessoire')
                    <i class="fas fa-tools text-6xl" style="color: var(--color-primary); opacity: 0.4;"></i>
                @else
                    <i class="fas fa-leaf text-6xl" style="color: var(--color-primary); opacity: 0.4;"></i>
                @endif
            </div>
        @endif
        
        <!-- Badge Prix -->
        <div class="absolute bottom-3 right-3 px-4 py-1.5 rounded-xl font-bold text-white shadow-xl text-sm"
             style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            {{ number_format($annonce->prix, 0, ',', ' ') }} FCFA
        </div>
        
        <!-- Badge Type -->
        <div class="absolute top-3 left-3">
            <span class="px-3 py-1 rounded-full text-[10px] font-semibold text-white backdrop-blur-md"
                  style="background: rgba(0,0,0,0.7);">
                @if($annonce->type === 'animal' && $annonce->animal && $annonce->animal->race)
                    {{ $annonce->animal->race->nom ?? 'Animal' }}
                @elseif($annonce->type === 'nourriture' && $annonce->nourriture)
                    {{ ucfirst($annonce->nourriture->type ?? 'Aliment') }}
                @elseif($annonce->type === 'accessoire' && $annonce->accessoire)
                    {{ ucfirst($annonce->accessoire->categorie ?? 'Accessoire') }}
                @elseif($annonce->type === 'escrement' && $annonce->escrement)
                    {{ ucfirst($annonce->escrement->nom ?? 'Fumier') }}
                @else
                    {{ ucfirst($annonce->type) }}
                @endif
            </span>
        </div>
        
        <!-- Badge Quantité -->
        @if($annonce->quantite)
            <div class="absolute top-3 right-3">
                <span class="px-3 py-1 rounded-full text-[10px] font-semibold text-white backdrop-blur-md"
                      style="background: rgba(0,0,0,0.7);">
                    <i class="fas fa-box mr-1"></i>
                    {{ $annonce->quantite }}
                </span>
            </div>
        @endif
        
        <!-- Badge Propriétaire -->
        @if($isOwner)
            <div class="absolute bottom-3 left-3">
                <span class="px-3 py-1 rounded-full text-[10px] font-semibold text-white backdrop-blur-md"
                      style="background: rgba(0,0,0,0.7);">
                    <i class="fas fa-user-check mr-1"></i>
                    Votre annonce
                </span>
            </div>
        @endif
    </div>
    
    <div class="p-4">
        <!-- Titre -->
        <h4 class="text-base font-bold mb-1.5 line-clamp-1 group-hover:text-primary transition cursor-pointer"
            style="color: var(--color-nav-text);"
            onclick="window.location='{{ $route }}'">
            {{ $annonce->titre }}
        </h4>
        
        <!-- Description -->
        <p class="text-xs mb-3 line-clamp-2" style="color: var(--color-nav-text); opacity: 0.7;">
            {{ Str::limit(strip_tags($annonce->description), 80) }}
        </p>
        
        <!-- Infos Vendeur -->
        <div class="flex items-center justify-between pt-3 border-t" style="border-color: var(--color-nav-border);">
            <div class="flex items-center text-xs" style="color: var(--color-nav-text); opacity: 0.6;">
                <div class="w-6 h-6 rounded-full flex items-center justify-center mr-2" style="background: var(--color-secondary-light);">
                    <i class="fas fa-user text-[8px]" style="color: var(--color-primary);"></i>
                </div>
                {{ Str::limit($annonce->auteur->prenom ?? '' . ' ' . ($annonce->auteur->nom ?? 'Vendeur'), 15) }}
            </div>
            
            <span class="text-[10px]" style="color: var(--color-nav-text); opacity: 0.5;">
                <i class="far fa-clock mr-1"></i>
                {{ $annonce->created_at->diffForHumans() }}
            </span>
        </div>
        
        <!-- Boutons d'action -->
        <div class="grid grid-cols-3 gap-1.5 mt-3">
            <!-- Bouton Voir -->
            <a href="{{ $route }}" 
               class="flex items-center justify-center gap-1 py-1.5 rounded-lg text-[10px] font-medium transition-all duration-300"
               style="background: var(--color-secondary-light); color: var(--color-primary-dark);"
               onmouseover="this.style.background='var(--color-secondary)'; this.style.transform='translateY(-1px)'"
               onmouseout="this.style.background='var(--color-secondary-light)'; this.style.transform='translateY(0)'">
                <i class="fas fa-eye text-[8px]"></i>
                Voir
            </a>
            
            <!-- Bouton Discuter -->
            @auth
                @if(!$isOwner)
                    <button onclick="openContactModal({{ $annonce->id }})" 
                            class="flex items-center justify-center gap-1 py-1.5 rounded-lg text-[10px] font-medium transition-all duration-300 text-white"
                            style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                            onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'; this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'; this.style.transform='translateY(0)'">
                        <i class="fas fa-comment text-[8px]"></i>
                        Discuter
                    </button>
                @else
                    <div class="flex items-center justify-center py-1.5 rounded-lg text-[10px] font-medium"
                         style="background: var(--color-bg-gray); color: var(--color-nav-text); opacity: 0.5;">
                        <i class="fas fa-user-check text-[8px] mr-1"></i>
                        Vous
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" 
                   class="flex items-center justify-center gap-1 py-1.5 rounded-lg text-[10px] font-medium transition-all duration-300 text-white"
                   style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                   onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'; this.style.transform='translateY(-1px)'"
                   onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'; this.style.transform='translateY(0)'">
                    <i class="fas fa-comment text-[8px]"></i>
                    Discuter
                </a>
            @endauth
            
            <!-- Bouton Commander -->
            @auth
                @if(!$isOwner && $annonce->statut === 'active')
                    <a href="#" 
                       class="flex items-center justify-center gap-1 py-1.5 rounded-lg text-[10px] font-medium transition-all duration-300 text-white"
                       style="background: linear-gradient(135deg, var(--color-secondary-dark), var(--color-secondary));"
                       onmouseover="this.style.background='linear-gradient(135deg, var(--color-secondary), var(--color-secondary-light))'; this.style.transform='translateY(-1px)'"
                       onmouseout="this.style.background='linear-gradient(135deg, var(--color-secondary-dark), var(--color-secondary))'; this.style.transform='translateY(0)'">
                        <i class="fas fa-shopping-cart text-[8px]"></i>
                        Commander
                    </a>
                @elseif($isOwner)
                    <div class="flex items-center justify-center py-1.5 rounded-lg text-[10px] font-medium"
                         style="background: var(--color-bg-gray); color: var(--color-nav-text); opacity: 0.5;">
                        <i class="fas fa-store text-[8px] mr-1"></i>
                        Votre annonce
                    </div>
                @elseif($annonce->statut !== 'active')
                    <div class="flex items-center justify-center py-1.5 rounded-lg text-[10px] font-medium"
                         style="background: #FEF2F2; color: #DC2626;">
                        <i class="fas fa-clock text-[8px] mr-1"></i>
                        Indisponible
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" 
                   class="flex items-center justify-center gap-1 py-1.5 rounded-lg text-[10px] font-medium transition-all duration-300 text-white"
                   style="background: linear-gradient(135deg, var(--color-secondary-dark), var(--color-secondary));"
                   onmouseover="this.style.background='linear-gradient(135deg, var(--color-secondary), var(--color-secondary-light))'; this.style.transform='translateY(-1px)'"
                   onmouseout="this.style.background='linear-gradient(135deg, var(--color-secondary-dark), var(--color-secondary))'; this.style.transform='translateY(0)'">
                    <i class="fas fa-shopping-cart text-[8px]"></i>
                    Commander
                </a>
            @endauth
        </div>
    </div>
</div>