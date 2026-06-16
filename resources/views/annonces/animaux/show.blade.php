<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-primary to-primary-dark py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('annonces.animaux.index') }}" class="text-white hover:text-secondary-light transition">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ $annonce->titre }}</h1>
                        <p class="text-white/80 text-sm mt-1">
                            <i class="fas fa-user mr-1"></i>
                            {{ $annonce->auteur->prenom ?? '' }} {{ $annonce->auteur->nom ?? 'Vendeur' }}
                            <span class="mx-2">•</span>
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $annonce->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--color-bg-body);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Colonne de gauche: Images -->
                <div class="lg:col-span-2">
                    <div class="rounded-xl overflow-hidden shadow-lg" style="background-color: var(--color-bg-white);">
                        <!-- Image principale -->
                        <div class="relative h-96 overflow-hidden">
                            @php
                                $imagePrincipale = $annonce->piecesJointes->where('est_principale', true)->first() ?? $annonce->piecesJointes->first();
                            @endphp
                            
                            @if($imagePrincipale && $imagePrincipale->chemin_stockage && file_exists(storage_path('app/public/' . $imagePrincipale->chemin_stockage)))
                                <img src="{{ asset('storage/' . $imagePrincipale->chemin_stockage) }}" 
                                     alt="{{ $annonce->titre }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-secondary), var(--color-secondary-light));">
                                    <i class="fas fa-paw text-8xl" style="color: var(--color-primary);"></i>
                                </div>
                            @endif
                            
                            <div class="absolute bottom-4 right-4 px-6 py-3 rounded-lg font-bold text-white shadow-lg text-xl"
                                 style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                {{ number_format($annonce->prix, 0, ',', ' ') }} FCFA
                            </div>
                            
                            <div class="absolute top-4 left-4">
                                <span class="px-4 py-2 rounded-full text-sm font-semibold text-white backdrop-blur-sm"
                                      style="background-color: rgba(0,0,0,0.7);">
                                    @if($annonce->statut === 'active')
                                        <i class="fas fa-check-circle text-green-400 mr-1"></i> Disponible
                                    @elseif($annonce->statut === 'en_attente')
                                        <i class="fas fa-clock text-yellow-400 mr-1"></i> En attente
                                    @elseif($annonce->statut === 'vendue')
                                        <i class="fas fa-sold-out text-red-400 mr-1"></i> Vendue
                                    @else
                                        {{ ucfirst($annonce->statut) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <!-- Miniatures -->
                        @if($annonce->piecesJointes->count() > 1)
                            <div class="p-4 grid grid-cols-6 gap-2">
                                @foreach($annonce->piecesJointes as $piece)
                                    @if($piece->chemin_stockage && file_exists(storage_path('app/public/' . $piece->chemin_stockage)))
                                        <div class="aspect-square rounded-lg overflow-hidden cursor-pointer border-2 transition hover:border-primary"
                                             style="border-color: {{ $loop->first ? 'var(--color-primary)' : 'var(--color-nav-border)' }};"
                                             onclick="changeMainImage(this)">
                                            <img src="{{ asset('storage/' . $piece->chemin_stockage) }}" 
                                                 alt="{{ $piece->nom_media }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Colonne de droite: Infos -->
                <div>
                    <div class="rounded-xl shadow-lg p-6" style="background-color: var(--color-bg-white);">
                        <h2 class="text-2xl font-bold mb-4" style="color: var(--color-nav-text);">
                            Détails de l'annonce
                        </h2>
                        
                        <div class="space-y-3">
                            @if($annonce->animal)
                                <div class="flex items-center justify-between py-2 border-b" style="border-color: var(--color-nav-border);">
                                    <span class="text-sm" style="color: var(--color-nav-text); opacity: 0.7;">Race</span>
                                    <span class="font-semibold" style="color: var(--color-nav-text);">
                                        {{ $annonce->animal->race->label ?? $annonce->animal->race->nom ?? 'Non spécifiée' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b" style="border-color: var(--color-nav-border);">
                                    <span class="text-sm" style="color: var(--color-nav-text); opacity: 0.7;">Âge</span>
                                    <span class="font-semibold" style="color: var(--color-nav-text);">
                                        @if($annonce->animal->age_mois)
                                            {{ $annonce->animal->age_mois }} mois 
                                            ({{ round($annonce->animal->age_mois / 12, 1) }} ans)
                                        @else
                                            Non spécifié
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b" style="border-color: var(--color-nav-border);">
                                    <span class="text-sm" style="color: var(--color-nav-text); opacity: 0.7;">Sexe</span>
                                    <span class="font-semibold" style="color: var(--color-nav-text);">
                                        {{ $annonce->animal->sexe === 'M' ? 'Mâle ♂' : 'Femelle ♀' }}
                                    </span>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between py-2 border-b" style="border-color: var(--color-nav-border);">
                                <span class="text-sm" style="color: var(--color-nav-text); opacity: 0.7;">Quantité</span>
                                <span class="font-semibold" style="color: var(--color-nav-text);">
                                    {{ $annonce->quantite ?? 1 }} unité(s)
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between py-2 border-b" style="border-color: var(--color-nav-border);">
                                <span class="text-sm" style="color: var(--color-nav-text); opacity: 0.7;">Publié par</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: var(--color-secondary-light);">
                                        <i class="fas fa-user text-xs" style="color: var(--color-primary);"></i>
                                    </div>
                                    <span class="font-semibold" style="color: var(--color-nav-text);">
                                        {{ $annonce->auteur->prenom ?? '' }} {{ $annonce->auteur->nom ?? 'Vendeur' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between py-2" style="border-color: var(--color-nav-border);">
                                <span class="text-sm" style="color: var(--color-nav-text); opacity: 0.7;">Localisation</span>
                                <span class="font-semibold" style="color: var(--color-nav-text);">
                                    {{ $annonce->auteur->commune ?? 'Non spécifiée' }}, 
                                    {{ $annonce->auteur->departement ?? '' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-6 space-y-3">
                            @if($annonce->statut === 'active')
                                @auth
                                    @if($annonce->id_user !== Auth::id())
                                        <button onclick="openContactModal({{ $annonce->id }})" 
                                                class="flex items-center justify-center gap-2 w-full py-3 rounded-lg font-semibold transition-all duration-300"
                                                style="background-color: var(--color-secondary-light); color: var(--color-primary-dark);"
                                                onmouseover="this.style.backgroundColor='var(--color-secondary)'; this.style.transform='translateY(-2px)'"
                                                onmouseout="this.style.backgroundColor='var(--color-secondary-light)'; this.style.transform='translateY(0)'">
                                            <i class="fas fa-comment"></i>
                                            Contacter le vendeur
                                        </button>
                                    @endif
                                    
                                    @if($annonce->id_user !== Auth::id())
                                        <a href="#" class="flex items-center justify-center gap-2 w-full py-3 rounded-lg font-semibold transition-all duration-300 text-white"
                                           style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                                           onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'; this.style.transform='translateY(-2px)'"
                                           onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'; this.style.transform='translateY(0)'">
                                            <i class="fas fa-shopping-cart"></i>
                                            Acheter maintenant
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full py-3 rounded-lg font-semibold transition-all duration-300 text-white"
                                       style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                                       onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'; this.style.transform='translateY(-2px)'"
                                       onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'; this.style.transform='translateY(0)'">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Connectez-vous pour acheter
                                    </a>
                                @endauth
                            @elseif($annonce->statut === 'en_attente')
                                <div class="text-center py-3 rounded-lg" style="background-color: var(--color-secondary-light); color: var(--color-primary-dark);">
                                    <i class="fas fa-clock mr-2"></i>
                                    Cette annonce est en attente de validation
                                </div>
                            @elseif($annonce->statut === 'vendue')
                                <div class="text-center py-3 rounded-lg" style="background-color: #fee2e2; color: #dc2626;">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Cette annonce a déjà été vendue
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="mt-8">
                <div class="rounded-xl shadow-lg p-6" style="background-color: var(--color-bg-white);">
                    <h3 class="text-xl font-bold mb-4" style="color: var(--color-nav-text);">
                        <i class="fas fa-align-left mr-2" style="color: var(--color-primary);"></i>
                        Description
                    </h3>
                    <div class="prose max-w-none" style="color: var(--color-nav-text); line-height: 1.8;">
                        {{ $annonce->description ?? 'Aucune description disponible pour cette annonce.' }}
                    </div>
                </div>
            </div>
            
            <!-- Annonces similaires -->
            @if(isset($annoncesSimilaires) && $annoncesSimilaires->count() > 0)
                <div class="mt-12">
                    <h3 class="text-2xl font-bold mb-6" style="color: var(--color-nav-text);">
                        <i class="fas fa-paw mr-2" style="color: var(--color-primary);"></i>
                        Annonces similaires
                    </h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($annoncesSimilaires as $similaire)
                            <div class="rounded-lg overflow-hidden shadow-md transition hover:shadow-xl" style="background-color: var(--color-bg-white);">
                                <div class="relative h-40 overflow-hidden">
                                    @php
                                        $img = $similaire->piecesJointes->where('est_principale', true)->first() ?? $similaire->piecesJointes->first();
                                    @endphp
                                    @if($img && $img->chemin_stockage && file_exists(storage_path('app/public/' . $img->chemin_stockage)))
                                        <img src="{{ asset('storage/' . $img->chemin_stockage) }}" alt="{{ $similaire->titre }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center" style="background-color: var(--color-secondary-light);">
                                            <i class="fas fa-paw text-3xl" style="color: var(--color-primary);"></i>
                                        </div>
                                    @endif
                                    <div class="absolute bottom-2 right-2 px-3 py-1 rounded-lg text-sm font-bold text-white" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                        {{ number_format($similaire->prix, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold mb-1 line-clamp-1">
                                        <a href="{{ route('annonces.animaux.show', $similaire->id) }}" 
                                           class="hover:text-primary transition"
                                           style="color: var(--color-nav-text);">
                                            {{ $similaire->titre }}
                                        </a>
                                    </h4>
                                    <a href="{{ route('annonces.animaux.show', $similaire->id) }}" 
                                       class="text-sm font-semibold transition inline-flex items-center"
                                       style="color: var(--color-primary);">
                                        Voir détails <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    function changeMainImage(element) {
        const imgSrc = element.querySelector('img').src;
        const mainImg = document.querySelector('.relative.h-96 img');
        if (mainImg) {
            mainImg.src = imgSrc;
        }
        document.querySelectorAll('.grid.grid-cols-6 .aspect-square').forEach(el => {
            el.style.borderColor = 'var(--color-nav-border)';
        });
        element.style.borderColor = 'var(--color-primary)';
    }
</script>