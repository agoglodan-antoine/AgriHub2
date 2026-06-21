<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-gem text-4xl" style="color: var(--color-primary-light);"></i>
                    Mes points de fidélité
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">
                    Gérez vos points et échangez-les contre des récompenses
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Résumé des points -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="rounded-xl shadow-md p-6 text-center" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                    <div class="text-4xl font-bold text-white">{{ number_format($totalPoints) }}</div>
                    <div class="text-sm text-white/80">Points disponibles</div>
                </div>
                <div class="rounded-xl shadow-md p-6 text-center" style="background-color: var(--color-bg-white);">
                    <div class="text-2xl font-bold" style="color: var(--color-primary);">{{ number_format($pointsGagnes) }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Points gagnés</div>
                    <div class="text-xs mt-1" style="color: var(--color-nav-text); opacity: 0.4;">(1 FCFA = 1 point)</div>
                </div>
                <div class="rounded-xl shadow-md p-6 text-center" style="background-color: var(--color-bg-white);">
                    <div class="text-2xl font-bold" style="color: #EF4444;">{{ number_format($pointsDepenses) }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Points dépensés</div>
                    <div class="text-xs mt-1" style="color: var(--color-nav-text); opacity: 0.4;">(Échangés contre des récompenses)</div>
                </div>
            </div>

            <!-- Grille principale -->
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Récompenses disponibles -->
                <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                        <i class="fas fa-gift mr-2" style="color: var(--color-primary);"></i>
                        Récompenses disponibles
                    </h3>
                    
                    @if($recompenses->count() > 0)
                        <div class="space-y-3">
                            @foreach($recompenses as $recompense)
                                <div class="flex items-center justify-between p-3 rounded-lg transition hover:shadow-md" 
                                     style="background-color: var(--color-bg-gray);">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: var(--color-secondary-light);">
                                            @if($recompense->type_recompense == 'reduction')
                                                <i class="fas fa-tag" style="color: var(--color-primary);"></i>
                                            @elseif($recompense->type_recompense == 'bon_achat')
                                                <i class="fas fa-ticket-alt" style="color: var(--color-primary);"></i>
                                            @elseif($recompense->type_recompense == 'premium')
                                                <i class="fas fa-crown" style="color: var(--color-primary);"></i>
                                            @else
                                                <i class="fas fa-gift" style="color: var(--color-primary);"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="font-semibold" style="color: var(--color-nav-text);">{{ $recompense->nom_recompense }}</h5>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.7;">{{ $recompense->description ?? 'Récompense disponible' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold" style="color: var(--color-primary);">{{ number_format($recompense->cout_points) }} pts</div>
                                        @if($totalPoints >= $recompense->cout_points)
                                            <form action="{{ route('dashboard.points-fidelite.echanger') }}" method="POST" class="mt-1">
                                                @csrf
                                                <input type="hidden" name="recompense_id" value="{{ $recompense->id }}">
                                                <button type="submit" 
                                                        class="text-xs px-3 py-1 rounded transition text-white hover:scale-105"
                                                        style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                                                        onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'"
                                                        onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'"
                                                        onclick="return confirm('Voulez-vous vraiment échanger {{ $recompense->cout_points }} points contre {{ $recompense->nom_recompense }} ?')">
                                                    <i class="fas fa-exchange-alt mr-1"></i> Échanger
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-xs mt-1" style="color: var(--color-nav-text); opacity: 0.5;">Points insuffisants</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-gift text-4xl mb-3" style="color: var(--color-primary-light);"></i>
                            <p style="color: var(--color-nav-text); opacity: 0.6;">Aucune récompense disponible</p>
                        </div>
                    @endif
                </div>

                <!-- Historique des points -->
                <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold" style="color: var(--color-nav-text);">
                            <i class="fas fa-history mr-2" style="color: var(--color-primary);"></i>
                            Historique des points
                        </h3>
                        <a href="{{ route('dashboard.points-fidelite') }}" class="text-sm transition" style="color: var(--color-primary);" onmouseover="this.style.color='var(--color-primary-dark)'" onmouseout="this.style.color='var(--color-primary)'">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>

                    <!-- Filtres -->
                    <form action="{{ route('dashboard.points-fidelite') }}" method="GET" class="mb-4">
                        <div class="flex gap-2">
                            <select name="type" 
                                    class="flex-1 px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors text-sm"
                                    style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                    onchange="this.form.submit()">
                                <option value="">Toutes les opérations</option>
                                <option value="gain" {{ request('type') == 'gain' ? 'selected' : '' }}>Gains</option>
                                <option value="depense" {{ request('type') == 'depense' ? 'selected' : '' }}>Dépenses</option>
                            </select>
                            <select name="sort" 
                                    class="flex-1 px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors text-sm"
                                    style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                    onchange="this.form.submit()">
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                                <option value="ancien" {{ request('sort') == 'ancien' ? 'selected' : '' }}>Plus ancien</option>
                                <option value="montant_asc" {{ request('sort') == 'montant_asc' ? 'selected' : '' }}>Montant croissant</option>
                                <option value="montant_desc" {{ request('sort') == 'montant_desc' ? 'selected' : '' }}>Montant décroissant</option>
                            </select>
                            @if(request()->anyFilled(['type', 'sort']))
                                <a href="{{ route('dashboard.points-fidelite') }}" 
                                   class="px-3 py-2 rounded-lg text-sm transition flex items-center"
                                   style="background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </form>

                    @if($historique->count() > 0)
                        <div class="space-y-2 max-h-96 overflow-y-auto pr-1">
                            @foreach($historique as $point)
                                <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                             style="background: {{ $point->type_operation === 'gain' ? 'linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))' : 'linear-gradient(135deg, #EF4444, #DC2626)' }};">
                                            <i class="fas {{ $point->type_operation === 'gain' ? 'fa-plus' : 'fa-minus' }}"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold" style="color: var(--color-nav-text);">
                                                {{ $point->type_operation === 'gain' ? 'Points gagnés' : 'Points dépensés' }}
                                            </p>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">
                                                @if($point->commande)
                                                    Commande #{{ $point->commande->id }}
                                                @else
                                                    Échange de récompense
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-bold {{ $point->type_operation === 'gain' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $point->type_operation === 'gain' ? '+' : '-' }}{{ number_format(abs($point->montant_points)) }}
                                        </span>
                                        <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">
                                            {{ $point->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $historique->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-history text-4xl mb-3" style="color: var(--color-primary-light);"></i>
                            <p style="color: var(--color-nav-text); opacity: 0.6;">Aucun historique de points</p>
                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.4;">Les points apparaîtront après vos achats</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bouton vers mes récompenses -->
            <div class="mt-6 text-center">
                <a href="{{ route('dashboard.mes-recompenses') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold transition hover:scale-105 text-white"
                   style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                    <i class="fas fa-gift"></i>
                    Voir mes récompenses
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>