@php
    $user = Auth::user();
    $points = $user ? App\Models\PointsFidelite::where('id_utilisateur', $user->id)->sum('points') : 0;
    $recompenses = App\Models\Recompense::where('status', 'actif')->take(4)->get();
@endphp

<section class="py-16" style="background: linear-gradient(135deg, var(--color-secondary-dark), var(--color-nav-text));">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl shadow-2xl overflow-hidden" data-aos="zoom-in" style="background-color: var(--color-bg-white);">
            <div class="grid md:grid-cols-2">
                <!-- Partie gauche: Message d'incitation -->
                <div class="p-8 md:p-12 text-white" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                    <div class="mb-6">
                        <i class="fas fa-gem text-5xl mb-4"></i>
                    </div>
                    <h3 class="text-3xl font-bold mb-4">
                        Programme de Fidélité
                    </h3>
                    <p class="mb-6 leading-relaxed" style="color: var(--color-secondary-light);">
                        Gagnez des points à chaque achat et bénéficiez de récompenses exclusives ! 
                        Plus vous achetez, plus vous gagnez.
                    </p>
                    
                    @auth
                        <div class="bg-white/20 rounded-xl p-4 mb-6">
                            <div class="text-center">
                                <div class="text-sm uppercase tracking-wide">Vos points</div>
                                <div class="text-4xl font-bold">{{ number_format($points) }}</div>
                                <div class="text-xs mt-1">points disponibles</div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <a href="{{ route('register') }}" 
                               class="inline-block px-6 py-3 rounded-lg font-semibold transition"
                               style="background-color: var(--color-bg-white); color: var(--color-primary-dark);"
                               onmouseover="this.style.backgroundColor='var(--color-secondary-light)'; this.style.color='var(--color-primary-dark)'"
                               onmouseout="this.style.backgroundColor='var(--color-bg-white)'; this.style.color='var(--color-primary-dark)'">
                                Créer un compte gratuitement
                                <i class="fas fa-user-plus ml-2"></i>
                            </a>
                            <p class="text-sm" style="color: var(--color-secondary-light);">
                                Déjà membre ? <a href="{{ route('login') }}" class="underline hover:text-white">Connectez-vous</a>
                            </p>
                        </div>
                    @endauth
                    
                    <!-- Avantages -->
                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle" style="color: var(--color-secondary-light);"></i>
                            <span>1 FCFA dépensé = 1 point gagné</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle" style="color: var(--color-secondary-light);"></i>
                            <span>1000 points = 500 FCFA de réduction</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle" style="color: var(--color-secondary-light);"></i>
                            <span>Programme parrainage : 5000 points offerts</span>
                        </div>
                    </div>
                </div>
                
                <!-- Partie droite: Récompenses -->
                <div class="p-8 md:p-12" style="background-color: var(--color-bg-white);">
                    <h4 class="text-xl font-bold mb-6" style="color: var(--color-nav-text);">
                        <i class="fas fa-gift mr-2" style="color: var(--color-primary);"></i>
                        Récompenses disponibles
                    </h4>
                    
                    <div class="space-y-4">
                        @forelse($recompenses as $recompense)
                            <div class="flex items-center justify-between p-4 rounded-lg transition" 
                                 style="background-color: var(--color-bg-gray);"
                                 onmouseover="this.style.boxShadow='var(--shadow-md)'"
                                 onmouseout="this.style.boxShadow='none'">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: var(--color-secondary-light);">
                                        @if($recompense->type == 'reduction')
                                            <i class="fas fa-tag" style="color: var(--color-primary);"></i>
                                        @elseif($recompense->type == 'bon_achat')
                                            <i class="fas fa-ticket-alt" style="color: var(--color-primary);"></i>
                                        @elseif($recompense->type == 'premium')
                                            <i class="fas fa-crown" style="color: var(--color-primary);"></i>
                                        @else
                                            <i class="fas fa-gift" style="color: var(--color-primary);"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="font-semibold" style="color: var(--color-nav-text);">{{ $recompense->nom }}</h5>
                                        <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.7;">{{ $recompense->description ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold" style="color: var(--color-primary);">{{ number_format($recompense->cout_points) }} pts</div>
                                    @auth
                                        @if($points >= $recompense->cout_points)
                                            <button onclick="alert('Fonctionnalité à venir')" 
                                                    class="text-xs px-2 py-1 rounded transition text-white"
                                                    style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                                                    onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'"
                                                    onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'">
                                                Échanger
                                            </button>
                                        @else
                                            <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Points insuffisants</div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <p class="text-center" style="color: var(--color-nav-text);">Aucune récompense disponible</p>
                        @endforelse
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ url('/recompenses') }}" class="font-semibold transition" 
                           style="color: var(--color-primary);"
                           onmouseover="this.style.color='var(--color-primary-dark)'"
                           onmouseout="this.style.color='var(--color-primary)'">
                            Voir toutes les récompenses <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>