<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-shopping-cart text-4xl" style="color: var(--color-primary-light);"></i>
                    Mes commandes
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">
                    Gérez toutes vos commandes en un seul endroit
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Statistiques des commandes -->
            @php
                $totalCommandes = $commandes->total();
                $totalMontant = $commandes->sum('montant_total');
                $totalLivrees = $commandes->where('statut_commande', 'livree')->count();
                $totalEnCours = $commandes->whereIn('statut_commande', ['en_attente', 'validee', 'en_cours'])->count();
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="rounded-xl shadow-md p-4 text-center" style="background-color: var(--color-bg-white);">
                    <div class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ $totalCommandes }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Total commandes</div>
                </div>
                <div class="rounded-xl shadow-md p-4 text-center" style="background-color: var(--color-bg-white);">
                    <div class="text-2xl font-bold" style="color: var(--color-primary);">{{ number_format($totalMontant, 0, ',', ' ') }} FCFA</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Montant total</div>
                </div>
                <div class="rounded-xl shadow-md p-4 text-center" style="background-color: var(--color-bg-white);">
                    <div class="text-2xl font-bold" style="color: #22C55E;">{{ $totalLivrees }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Commandes livrées</div>
                </div>
                <div class="rounded-xl shadow-md p-4 text-center" style="background-color: var(--color-bg-white);">
                    <div class="text-2xl font-bold" style="color: #F59E0B;">{{ $totalEnCours }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Commandes en cours</div>
                </div>
            </div>

            <!-- Filtres et recherche -->
            <div class="mb-6">
                <form action="{{ route('dashboard.commandes') }}" method="GET" id="filter-form">
                    <div class="rounded-xl shadow-md p-4" style="background-color: var(--color-bg-white);">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Rechercher une commande par produit ou ID..." 
                                           class="w-full px-4 py-2 pl-10 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                           style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2" style="color: var(--color-primary);"></i>
                                </div>
                            </div>
                            <div class="w-full md:w-48">
                                <select name="statut" 
                                        class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Tous les statuts</option>
                                    @foreach($statuts as $key => $label)
                                        <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-48">
                                <select name="sort" 
                                        class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                                    <option value="ancien" {{ request('sort') == 'ancien' ? 'selected' : '' }}>Plus ancien</option>
                                    <option value="prix_asc" {{ request('sort') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                                    <option value="prix_desc" {{ request('sort') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                </select>
                            </div>
                            <button type="submit" 
                                    class="px-6 py-2 rounded-lg font-semibold transition hover:scale-105 text-white"
                                    style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                <i class="fas fa-filter mr-2"></i> Filtrer
                            </button>
                            @if(request()->anyFilled(['search', 'statut', 'sort']))
                                <a href="{{ route('dashboard.commandes') }}" 
                                   class="px-6 py-2 rounded-lg font-semibold transition text-center flex items-center justify-center gap-2"
                                   style="background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                                    <i class="fas fa-times"></i> Réinitialiser
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Liste des commandes -->
            @if($commandes->count() > 0)
                <div class="rounded-xl shadow-md overflow-hidden" style="background-color: var(--color-bg-white);">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead style="background-color: var(--color-bg-gray);">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Commande</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Produit</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Montant</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Statut</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Date</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color: var(--color-nav-border);">
                                @foreach($commandes as $commande)
                                    <tr class="hover:bg-gray-50 transition" style="background-color: var(--color-bg-white);">
                                        <td class="px-4 py-3">
                                            <span class="text-sm font-semibold" style="color: var(--color-nav-text);">
                                                #{{ $commande->id }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm font-medium" style="color: var(--color-nav-text);">
                                                {{ $commande->annonce->titre ?? 'Produit non disponible' }}
                                            </p>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">
                                                {{ $commande->quantite }} unité(s)
                                            </p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-sm font-bold" style="color: var(--color-primary);">
                                                {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA
                                            </span>
                                            @if($commande->reduction > 0)
                                                <span class="text-xs block" style="color: #22C55E;">
                                                    -{{ number_format($commande->reduction, 0, ',', ' ') }} FCFA
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-xs px-2 py-1 rounded-full 
                                                {{ $commande->statut_commande === 'livree' ? 'bg-green-100 text-green-800' : 
                                                   ($commande->statut_commande === 'annulee' ? 'bg-red-100 text-red-800' : 
                                                   ($commande->statut_commande === 'validee' ? 'bg-blue-100 text-blue-800' : 
                                                   'bg-yellow-100 text-yellow-800')) }}">
                                                {{ $statuts[$commande->statut_commande] ?? ucfirst($commande->statut_commande) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-sm" style="color: var(--color-nav-text); opacity: 0.7;">
                                                {{ $commande->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('dashboard.commande.show', $commande->id) }}" 
                                               class="text-sm transition hover:scale-105 inline-block"
                                               style="color: var(--color-primary);"
                                               onmouseover="this.style.color='var(--color-primary-dark)'"
                                               onmouseout="this.style.color='var(--color-primary)'">
                                                <i class="fas fa-eye mr-1"></i> Détails
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $commandes->links() }}
                </div>
            @else
                <div class="text-center py-16 rounded-xl" style="background-color: var(--color-bg-white);">
                    <i class="fas fa-shopping-cart text-6xl mb-4" style="color: var(--color-primary-light);"></i>
                    <h3 class="text-2xl font-bold mb-2" style="color: var(--color-nav-text);">Aucune commande trouvée</h3>
                    <p style="color: var(--color-nav-text); opacity: 0.6;">Aucune commande ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('dashboard.commandes') }}" class="inline-block mt-4 px-6 py-2 rounded-lg text-white transition hover:scale-105" 
                       style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                        <i class="fas fa-undo mr-2"></i> Voir toutes les commandes
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>