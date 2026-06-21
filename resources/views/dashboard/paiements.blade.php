<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-credit-card text-4xl" style="color: var(--color-primary-light);"></i>
                    Mes paiements
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">
                    Gérez tous vos paiements en un seul endroit
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Statistiques des paiements -->
            @php
                $totalPaiements = $paiements->total();
                $totalMontant = $paiements->sum('montant_paye');
                $totalReussi = $paiements->where('statut_paiement', 'reussi')->count();
                $totalEnAttente = $paiements->where('statut_paiement', 'en_attente')->count();
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="rounded-xl shadow-md p-4 text-center" style="background-color: var(--color-bg-white);">
                    <div class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ $totalPaiements }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Total paiements</div>
                </div>
                <div class="rounded-xl shadow-md p-4 text-center" style="background-color: var(--color-bg-white);">
                    <div class="text-2xl font-bold" style="color: var(--color-primary);">{{ number_format($totalMontant, 0, ',', ' ') }} FCFA</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Montant total</div>
                </div>
                <div class="rounded-xl shadow-md p-4 text-center" style="background-color: var(--color-bg-white);">
                    <div class="text-2xl font-bold" style="color: #22C55E;">{{ $totalReussi }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Paiements réussis</div>
                </div>
                <div class="rounded-xl shadow-md p-4 text-center" style="background-color: var(--color-bg-white);">
                    <div class="text-2xl font-bold" style="color: #F59E0B;">{{ $totalEnAttente }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">En attente</div>
                </div>
            </div>

            <!-- Filtres et recherche -->
            <div class="mb-6">
                <form action="{{ route('dashboard.paiements') }}" method="GET" id="filter-form">
                    <div class="rounded-xl shadow-md p-4" style="background-color: var(--color-bg-white);">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Rechercher un paiement par produit..." 
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
                                    <option value="montant_asc" {{ request('sort') == 'montant_asc' ? 'selected' : '' }}>Montant croissant</option>
                                    <option value="montant_desc" {{ request('sort') == 'montant_desc' ? 'selected' : '' }}>Montant décroissant</option>
                                </select>
                            </div>
                            <button type="submit" 
                                    class="px-6 py-2 rounded-lg font-semibold transition hover:scale-105 text-white"
                                    style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                <i class="fas fa-filter mr-2"></i> Filtrer
                            </button>
                            @if(request()->anyFilled(['search', 'statut', 'sort']))
                                <a href="{{ route('dashboard.paiements') }}" 
                                   class="px-6 py-2 rounded-lg font-semibold transition text-center flex items-center justify-center gap-2"
                                   style="background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                                    <i class="fas fa-times"></i> Réinitialiser
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Liste des paiements -->
            @if($paiements->count() > 0)
                <div class="rounded-xl shadow-md overflow-hidden" style="background-color: var(--color-bg-white);">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead style="background-color: var(--color-bg-gray);">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Produit</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Montant</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Statut</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Date</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider" style="color: var(--color-nav-text);">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color: var(--color-nav-border);">
                                @foreach($paiements as $paiement)
                                    <tr class="hover:bg-gray-50 transition" style="background-color: var(--color-bg-white);">
                                        <td class="px-4 py-3 text-sm font-semibold" style="color: var(--color-nav-text);">
                                            #{{ $paiement->id }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm font-medium" style="color: var(--color-nav-text);">
                                                {{ $paiement->commande->annonce->titre ?? 'Commande #' . $paiement->id_commande }}
                                            </p>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">
                                                Commande #{{ $paiement->id_commande }}
                                            </p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-sm font-bold" style="color: var(--color-primary);">
                                                {{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-xs px-2 py-1 rounded-full 
                                                {{ $paiement->statut_paiement === 'reussi' ? 'bg-green-100 text-green-800' : 
                                                   ($paiement->statut_paiement === 'echoue' ? 'bg-red-100 text-red-800' : 
                                                   ($paiement->statut_paiement === 'rembourse' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                {{ $statuts[$paiement->statut_paiement] ?? ucfirst($paiement->statut_paiement) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-sm" style="color: var(--color-nav-text); opacity: 0.7;">
                                                {{ $paiement->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('dashboard.paiement.show', $paiement->id) }}" 
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
                    {{ $paiements->links() }}
                </div>
            @else
                <div class="text-center py-16 rounded-xl" style="background-color: var(--color-bg-white);">
                    <i class="fas fa-credit-card text-6xl mb-4" style="color: var(--color-primary-light);"></i>
                    <h3 class="text-2xl font-bold mb-2" style="color: var(--color-nav-text);">Aucun paiement trouvé</h3>
                    <p style="color: var(--color-nav-text); opacity: 0.6;">Aucun paiement ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('dashboard.paiements') }}" class="inline-block mt-4 px-6 py-2 rounded-lg text-white transition hover:scale-105" 
                       style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                        <i class="fas fa-undo mr-2"></i> Voir tous les paiements
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>