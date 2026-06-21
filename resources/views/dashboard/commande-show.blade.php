<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-shopping-cart text-4xl" style="color: var(--color-primary-light);"></i>
                    Détails de la commande #{{ $commande->id }}
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">
                    Consultez tous les détails de votre commande
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
                <a href="{{ route('dashboard.commandes') }}" class="hover:text-primary transition" style="color: var(--color-primary);">
                    Mes commandes
                </a>
                <span>/</span>
                <span>Commande #{{ $commande->id }}</span>
            </nav>

            <!-- En-tête de la commande -->
            <div class="rounded-xl shadow-md p-6 mb-6" style="background-color: var(--color-bg-white);">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-bold" style="color: var(--color-nav-text);">
                                Commande #{{ $commande->id }}
                            </span>
                            <span class="text-xs px-3 py-1 rounded-full 
                                {{ $commande->statut_commande === 'livree' ? 'bg-green-100 text-green-800' : 
                                   ($commande->statut_commande === 'annulee' ? 'bg-red-100 text-red-800' : 
                                   ($commande->statut_commande === 'validee' ? 'bg-blue-100 text-blue-800' : 
                                   'bg-yellow-100 text-yellow-800')) }}">
                                {{ ucfirst($commande->statut_commande) }}
                            </span>
                        </div>
                        <p class="text-sm mt-1" style="color: var(--color-nav-text); opacity: 0.6;">
                            Passée le {{ $commande->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold" style="color: var(--color-primary);">
                            {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA
                        </span>
                        @if($commande->reduction > 0)
                            <p class="text-xs" style="color: #22C55E;">
                                Réduction : -{{ number_format($commande->reduction, 0, ',', ' ') }} FCFA
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Colonne principale -->
                <div class="lg:col-span-2">
                    <!-- Détails de la commande -->
                    <div class="rounded-xl shadow-md p-6 mb-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-box mr-2" style="color: var(--color-primary);"></i>
                            Détails de la commande
                        </h3>

                        <div class="space-y-4">
                            <!-- Produit -->
                            <div class="flex items-center gap-4 p-4 rounded-lg" style="background-color: var(--color-bg-gray);">
                                @php
                                    $image = $commande->annonce->piecesJointes->first() ?? null;
                                @endphp
                                <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0" style="background-color: var(--color-bg-gray);">
                                    @if($image && $image->chemin_stockage && file_exists(storage_path('app/public/' . $image->chemin_stockage)))
                                        <img src="{{ asset('storage/' . $image->chemin_stockage) }}" 
                                             alt="{{ $commande->annonce->titre }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-primary-light), var(--color-primary));">
                                            <i class="fas fa-box text-2xl" style="color: rgba(255,255,255,0.5);"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <a href="{{ route('annonce.all.show', $commande->id_annonce) }}" 
                                       class="text-base font-semibold hover:text-primary transition" 
                                       style="color: var(--color-nav-text);"
                                       onmouseover="this.style.color='var(--color-primary)'"
                                       onmouseout="this.style.color='var(--color-nav-text)'">
                                        {{ $commande->annonce->titre ?? 'Produit' }}
                                    </a>
                                    <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">
                                        Quantité : {{ $commande->quantite }}
                                    </p>
                                    <p class="text-sm font-semibold" style="color: var(--color-primary);">
                                        {{ number_format($commande->prix_unitaire, 0, ',', ' ') }} FCFA / unité
                                    </p>
                                </div>
                            </div>

                            <!-- Répartition du montant -->
                            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-gray);">
                                <h4 class="text-sm font-semibold mb-2" style="color: var(--color-nav-text);">
                                    Répartition du montant
                                </h4>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span style="color: var(--color-nav-text); opacity: 0.6;">Prix unitaire</span>
                                        <span style="color: var(--color-nav-text);">{{ number_format($commande->prix_unitaire, 0, ',', ' ') }} FCFA</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span style="color: var(--color-nav-text); opacity: 0.6;">Quantité</span>
                                        <span style="color: var(--color-nav-text);">x {{ $commande->quantite }}</span>
                                    </div>
                                    @if($commande->reduction > 0)
                                        <div class="flex justify-between">
                                            <span style="color: var(--color-nav-text); opacity: 0.6;">Réduction</span>
                                            <span style="color: #22C55E;">-{{ number_format($commande->reduction, 0, ',', ' ') }} FCFA</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between pt-2 border-t font-bold" style="border-color: var(--color-nav-border);">
                                        <span style="color: var(--color-nav-text);">Total</span>
                                        <span style="color: var(--color-primary);">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Adresse de livraison -->
                            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-gray);">
                                <h4 class="text-sm font-semibold mb-2" style="color: var(--color-nav-text);">
                                    <i class="fas fa-map-marker-alt mr-1" style="color: var(--color-primary);"></i>
                                    Adresse de livraison
                                </h4>
                                <p style="color: var(--color-nav-text); opacity: 0.7;">
                                    {{ $commande->acheteur->adresse ?? 'Adresse non renseignée' }}
                                </p>
                                <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.5;">
                                    {{ $commande->acheteur->ville ?? '' }} {{ $commande->acheteur->commune ?? '' }}
                                </p>
                            </div>

                            <!-- Transporteur -->
                            @if($commande->transporteur)
                                <div class="p-4 rounded-lg" style="background-color: var(--color-bg-gray);">
                                    <h4 class="text-sm font-semibold mb-2" style="color: var(--color-nav-text);">
                                        <i class="fas fa-truck mr-1" style="color: var(--color-primary);"></i>
                                        Transporteur
                                    </h4>
                                    <p style="color: var(--color-nav-text);">
                                        {{ $commande->transporteur->prenom }} {{ $commande->transporteur->nom }}
                                    </p>
                                    <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.5;">
                                        {{ $commande->transporteur->telephone ?? '' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Paiements -->
                    @if($commande->paiements && $commande->paiements->count() > 0)
                        <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                            <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                                <i class="fas fa-credit-card mr-2" style="color: var(--color-primary);"></i>
                                Paiements
                            </h3>
                            <div class="space-y-3">
                                @foreach($commande->paiements as $paiement)
                                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                        <div>
                                            <p class="text-sm font-semibold" style="color: var(--color-nav-text);">
                                                Paiement #{{ $paiement->id }}
                                            </p>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">
                                                {{ $paiement->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm font-bold" style="color: var(--color-primary);">
                                                {{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA
                                            </span>
                                            <div>
                                                <span class="text-xs px-2 py-0.5 rounded-full 
                                                    {{ $paiement->statut_paiement === 'reussi' ? 'bg-green-100 text-green-800' : 
                                                       ($paiement->statut_paiement === 'echoue' ? 'bg-red-100 text-red-800' : 
                                                       'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($paiement->statut_paiement) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Colonne latérale -->
                <div class="lg:col-span-1">
                    <!-- Points de fidélité -->
                    <div class="rounded-xl shadow-md p-6 mb-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-gem mr-2" style="color: var(--color-primary);"></i>
                            Points de fidélité
                        </h3>
                        @if($commande->pointsFidelite && $commande->pointsFidelite->count() > 0)
                            <div class="space-y-2">
                                @foreach($commande->pointsFidelite as $point)
                                    <div class="flex items-center justify-between p-2 rounded-lg" style="background-color: var(--color-bg-gray);">
                                        <span class="text-sm" style="color: var(--color-nav-text);">
                                            {{ $point->type_operation === 'gain' ? 'Points gagnés' : 'Points utilisés' }}
                                        </span>
                                        <span class="text-sm font-bold {{ $point->type_operation === 'gain' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $point->type_operation === 'gain' ? '+' : '-' }}{{ number_format(abs($point->montant_points)) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-gem text-3xl mb-2" style="color: var(--color-primary-light);"></i>
                                <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">Aucun point pour cette commande</p>
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
                            @if($commande->statut_commande !== 'livree' && $commande->statut_commande !== 'annulee')
                                <form action="#" method="POST" class="w-full">
                                    @csrf
                                    @method('PUT')
                                    <button type="button" 
                                            onclick="if(confirm('Voulez-vous vraiment annuler cette commande ?')) this.closest('form').submit()"
                                            class="w-full py-2.5 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center justify-center gap-2"
                                            style="background: linear-gradient(135deg, #EF4444, #DC2626);">
                                        <i class="fas fa-times"></i> Annuler la commande
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('dashboard.commandes') }}" 
                               class="w-full block text-center py-2.5 rounded-lg font-semibold transition hover:scale-105"
                               style="background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                               onmouseover="this.style.backgroundColor='var(--color-nav-border)'"
                               onmouseout="this.style.backgroundColor='var(--color-bg-gray)'">
                                <i class="fas fa-arrow-left mr-2"></i> Retour aux commandes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>