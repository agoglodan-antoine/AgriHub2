<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-credit-card text-4xl" style="color: var(--color-primary-light);"></i>
                    Détails du paiement #{{ $paiement->id }}
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">
                    Consultez tous les détails de votre paiement
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Fil d'Ariane -->
            <nav class="flex items-center gap-2 text-sm mb-6" style="color: var(--color-nav-text);">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary transition" style="color: var(--color-primary);">
                    <i class="fas fa-home"></i>
                </a>
                <span>/</span>
                <a href="{{ route('dashboard.paiements') }}" class="hover:text-primary transition" style="color: var(--color-primary);">
                    Mes paiements
                </a>
                <span>/</span>
                <span>Paiement #{{ $paiement->id }}</span>
            </nav>

            <!-- En-tête du paiement -->
            <div class="rounded-xl shadow-md p-6 mb-6" style="background-color: var(--color-bg-white);">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-bold" style="color: var(--color-nav-text);">
                                Paiement #{{ $paiement->id }}
                            </span>
                            <span class="text-xs px-3 py-1 rounded-full 
                                {{ $paiement->statut_paiement === 'reussi' ? 'bg-green-100 text-green-800' : 
                                   ($paiement->statut_paiement === 'echoue' ? 'bg-red-100 text-red-800' : 
                                   ($paiement->statut_paiement === 'rembourse' ? 'bg-gray-100 text-gray-800' : 
                                   'bg-yellow-100 text-yellow-800')) }}">
                                {{ $statuts[$paiement->statut_paiement] ?? ucfirst($paiement->statut_paiement) }}
                            </span>
                        </div>
                        <p class="text-sm mt-1" style="color: var(--color-nav-text); opacity: 0.6;">
                            Effectué le {{ $paiement->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold" style="color: var(--color-primary);">
                            {{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA
                        </span>
                        <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">
                            Commande #{{ $paiement->id_commande }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Colonne principale -->
                <div class="lg:col-span-2">
                    <!-- Informations du paiement -->
                    <div class="rounded-xl shadow-md p-6 mb-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-info-circle mr-2" style="color: var(--color-primary);"></i>
                            Informations du paiement
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-gray);">
                                <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">ID Paiement</p>
                                <p class="text-sm font-semibold" style="color: var(--color-nav-text);">#{{ $paiement->id }}</p>
                            </div>
                            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-gray);">
                                <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Montant</p>
                                <p class="text-sm font-semibold" style="color: var(--color-primary);">{{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA</p>
                            </div>
                            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-gray);">
                                <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Statut</p>
                                <span class="text-sm font-semibold px-2 py-0.5 rounded-full 
                                    {{ $paiement->statut_paiement === 'reussi' ? 'bg-green-100 text-green-800' : 
                                       ($paiement->statut_paiement === 'echoue' ? 'bg-red-100 text-red-800' : 
                                       ($paiement->statut_paiement === 'rembourse' ? 'bg-gray-100 text-gray-800' : 
                                       'bg-yellow-100 text-yellow-800')) }}">
                                    {{ $statuts[$paiement->statut_paiement] ?? ucfirst($paiement->statut_paiement) }}
                                </span>
                            </div>
                            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-gray);">
                                <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Date</p>
                                <p class="text-sm font-semibold" style="color: var(--color-nav-text);">{{ $paiement->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Produit associé -->
                    <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-box mr-2" style="color: var(--color-primary);"></i>
                            Produit associé
                        </h3>

                        @if($paiement->commande && $paiement->commande->annonce)
                            <div class="flex items-center gap-4 p-4 rounded-lg" style="background-color: var(--color-bg-gray);">
                                @php
                                    $image = $paiement->commande->annonce->piecesJointes->first() ?? null;
                                @endphp
                                <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0" style="background-color: var(--color-bg-gray);">
                                    @if($image && $image->chemin_stockage && file_exists(storage_path('app/public/' . $image->chemin_stockage)))
                                        <img src="{{ asset('storage/' . $image->chemin_stockage) }}" 
                                             alt="{{ $paiement->commande->annonce->titre }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-primary-light), var(--color-primary));">
                                            <i class="fas fa-box text-2xl" style="color: rgba(255,255,255,0.5);"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <a href="{{ route('annonce.all.show', $paiement->commande->id_annonce) }}" 
                                       class="text-base font-semibold hover:text-primary transition" 
                                       style="color: var(--color-nav-text);"
                                       onmouseover="this.style.color='var(--color-primary)'"
                                       onmouseout="this.style.color='var(--color-nav-text)'">
                                        {{ $paiement->commande->annonce->titre ?? 'Produit' }}
                                    </a>
                                    <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">
                                        Commande #{{ $paiement->id_commande }}
                                    </p>
                                    <p class="text-sm font-semibold" style="color: var(--color-primary);">
                                        {{ number_format($paiement->commande->montant_total, 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-box text-3xl mb-2" style="color: var(--color-primary-light);"></i>
                                <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">Produit non disponible</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Colonne latérale -->
                <div class="lg:col-span-1">
                    <!-- Résumé de la commande -->
                    <div class="rounded-xl shadow-md p-6 mb-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-shopping-cart mr-2" style="color: var(--color-primary);"></i>
                            Résumé de la commande
                        </h3>
                        
                        @if($paiement->commande)
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span style="color: var(--color-nav-text); opacity: 0.6;">Commande</span>
                                    <span class="font-semibold" style="color: var(--color-nav-text);">#{{ $paiement->id_commande }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span style="color: var(--color-nav-text); opacity: 0.6;">Statut commande</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full 
                                        {{ $paiement->commande->statut_commande === 'livree' ? 'bg-green-100 text-green-800' : 
                                           ($paiement->commande->statut_commande === 'annulee' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($paiement->commande->statut_commande) }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span style="color: var(--color-nav-text); opacity: 0.6;">Montant total</span>
                                    <span class="font-semibold" style="color: var(--color-primary);">
                                        {{ number_format($paiement->commande->montant_total, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span style="color: var(--color-nav-text); opacity: 0.6;">Quantité</span>
                                    <span style="color: var(--color-nav-text);">{{ $paiement->commande->quantite }}</span>
                                </div>
                                
                                <div class="pt-2 mt-2 border-t" style="border-color: var(--color-nav-border);">
                                    <a href="{{ route('dashboard.commande.show', $paiement->id_commande) }}" 
                                       class="text-sm transition block text-center py-2 rounded-lg"
                                       style="color: var(--color-primary);"
                                       onmouseover="this.style.color='var(--color-primary-dark)'"
                                       onmouseout="this.style.color='var(--color-primary)'">
                                        <i class="fas fa-eye mr-1"></i> Voir la commande
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart text-3xl mb-2" style="color: var(--color-primary-light);"></i>
                                <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">Commande non trouvée</p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-cog mr-2" style="color: var(--color-primary);"></i>
                            Actions
                        </h3>
                        <div class="space-y-3">
                            @if($paiement->statut_paiement === 'en_attente')
                                <button type="button" 
                                        onclick="alert('Fonctionnalité à venir : Paiement en attente de validation')"
                                        class="w-full py-2.5 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center justify-center gap-2"
                                        style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                    <i class="fas fa-check"></i> Valider le paiement
                                </button>
                            @endif

                            <a href="{{ route('dashboard.paiements') }}" 
                               class="w-full block text-center py-2.5 rounded-lg font-semibold transition hover:scale-105"
                               style="background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                               onmouseover="this.style.backgroundColor='var(--color-nav-border)'"
                               onmouseout="this.style.backgroundColor='var(--color-bg-gray)'">
                                <i class="fas fa-arrow-left mr-2"></i> Retour aux paiements
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>