<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-tachometer-alt text-4xl" style="color: var(--color-primary-light);"></i>
                    Tableau de bord
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">
                    Bienvenue, {{ $user->prenom }} {{ $user->nom }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Statistiques -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                <div class="rounded-xl shadow-md p-4 text-center transition hover:scale-105" style="background-color: var(--color-bg-white);">
                    <i class="fas fa-box text-2xl mb-2" style="color: var(--color-primary);"></i>
                    <div class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ $statistiques['total_annonces'] }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Mes annonces</div>
                    <div class="text-xs mt-1" style="color: var(--color-primary);">{{ $statistiques['annonces_actives'] }} actives</div>
                </div>
                
                <div class="rounded-xl shadow-md p-4 text-center transition hover:scale-105" style="background-color: var(--color-bg-white);">
                    <i class="fas fa-shopping-cart text-2xl mb-2" style="color: var(--color-primary);"></i>
                    <div class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ $statistiques['total_commandes'] }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Commandes</div>
                    <div class="text-xs mt-1" style="color: var(--color-primary);">{{ $statistiques['commandes_en_cours'] }} en cours</div>
                </div>
                
                <div class="rounded-xl shadow-md p-4 text-center transition hover:scale-105" style="background-color: var(--color-bg-white);">
                    <i class="fas fa-comment text-2xl mb-2" style="color: var(--color-primary);"></i>
                    <div class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ $statistiques['messages_non_lus'] }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Messages non lus</div>
                </div>
                
                <div class="rounded-xl shadow-md p-4 text-center transition hover:scale-105" style="background-color: var(--color-bg-white);">
                    <i class="fas fa-gem text-2xl mb-2" style="color: var(--color-primary);"></i>
                    <div class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ number_format($statistiques['points_fidelite']) }}</div>
                    <div class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">Points de fidélité</div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="grid lg:grid-cols-2 gap-6 mb-8">
                <!-- Graphique des commandes -->
                <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                        <i class="fas fa-chart-line mr-2" style="color: var(--color-primary);"></i>
                        Évolution des commandes
                    </h3>
                    <div class="relative" style="height: 250px;">
                        <canvas id="commandesChart"></canvas>
                    </div>
                </div>

                <!-- Graphique des points -->
                <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                        <i class="fas fa-gem mr-2" style="color: var(--color-primary);"></i>
                        Évolution des points gagnés
                    </h3>
                    <div class="relative" style="height: 250px;">
                        <canvas id="pointsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Répartition des commandes -->
            <div class="grid lg:grid-cols-2 gap-6 mb-8">
                <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                        <i class="fas fa-chart-pie mr-2" style="color: var(--color-primary);"></i>
                        Répartition des commandes
                    </h3>
                    <div class="relative" style="height: 250px;">
                        <canvas id="statutsChart"></canvas>
                    </div>
                </div>

                <!-- Carte rapide des actions -->
                <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                        <i class="fas fa-rocket mr-2" style="color: var(--color-primary);"></i>
                        Actions rapides
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('annonce.animal.create') }}" 
                           class="p-4 rounded-lg text-center transition hover:scale-105"
                           style="background-color: var(--color-bg-gray);">
                            <i class="fas fa-plus-circle text-2xl mb-2" style="color: var(--color-primary);"></i>
                            <p class="text-xs font-semibold" style="color: var(--color-nav-text);">Publier une annonce</p>
                        </a>
                        <a href="{{ route('messagerie.index') }}" 
                           class="p-4 rounded-lg text-center transition hover:scale-105"
                           style="background-color: var(--color-bg-gray);">
                            <i class="fas fa-comment text-2xl mb-2" style="color: var(--color-primary);"></i>
                            <p class="text-xs font-semibold" style="color: var(--color-nav-text);">Voir mes messages</p>
                        </a>
                        <a href="{{ route('dashboard.commandes') }}" 
                           class="p-4 rounded-lg text-center transition hover:scale-105"
                           style="background-color: var(--color-bg-gray);">
                            <i class="fas fa-shopping-cart text-2xl mb-2" style="color: var(--color-primary);"></i>
                            <p class="text-xs font-semibold" style="color: var(--color-nav-text);">Mes commandes</p>
                        </a>
                        <a href="{{ route('dashboard.points-fidelite') }}" 
                           class="p-4 rounded-lg text-center transition hover:scale-105"
                           style="background-color: var(--color-bg-gray);">
                            <i class="fas fa-gem text-2xl mb-2" style="color: var(--color-primary);"></i>
                            <p class="text-xs font-semibold" style="color: var(--color-nav-text);">Mes points</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dernières commandes -->
            <div class="grid lg:grid-cols-2 gap-6">
                <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold" style="color: var(--color-nav-text);">
                            <i class="fas fa-shopping-cart mr-2" style="color: var(--color-primary);"></i>
                            Dernières commandes
                        </h3>
                        <a href="{{ route('dashboard.commandes') }}" class="text-sm transition" style="color: var(--color-primary);" onmouseover="this.style.color='var(--color-primary-dark)'" onmouseout="this.style.color='var(--color-primary)'">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    @if($dernieresCommandes->count() > 0)
                        <div class="space-y-3">
                            @foreach($dernieresCommandes as $commande)
                                <a href="{{ route('dashboard.commande.show', $commande->id) }}" 
                                   class="flex items-center justify-between p-3 rounded-lg transition hover:shadow-md"
                                   style="background-color: var(--color-bg-gray);">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white"
                                             style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold" style="color: var(--color-nav-text);">
                                                #{{ $commande->id }} - {{ $commande->annonce->titre ?? 'Commande' }}
                                            </p>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">
                                                {{ $commande->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-bold" style="color: var(--color-primary);">
                                            {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA
                                        </span>
                                        <div>
                                            <span class="text-xs px-2 py-0.5 rounded-full {{ $commande->statut_commande === 'livree' ? 'bg-green-100 text-green-800' : ($commande->statut_commande === 'annulee' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($commande->statut_commande) }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-cart text-4xl mb-3" style="color: var(--color-primary-light);"></i>
                            <p style="color: var(--color-nav-text); opacity: 0.6;">Aucune commande pour le moment</p>
                        </div>
                    @endif
                </div>

                <!-- Derniers messages -->
                <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold" style="color: var(--color-nav-text);">
                            <i class="fas fa-comments mr-2" style="color: var(--color-primary);"></i>
                            Derniers messages
                        </h3>
                        <a href="{{ route('messagerie.index') }}" class="text-sm transition" style="color: var(--color-primary);" onmouseover="this.style.color='var(--color-primary-dark)'" onmouseout="this.style.color='var(--color-primary)'">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    @if($derniersMessages->count() > 0)
                        <div class="space-y-3">
                            @foreach($derniersMessages as $message)
                                <a href="{{ route('messagerie.show', $message->id) }}" 
                                   class="flex items-center gap-3 p-3 rounded-lg transition hover:shadow-md"
                                   style="background-color: var(--color-bg-gray);">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                         style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                        {{ $message->expediteur->id === Auth::id() ? 'Moi' : strtoupper(substr($message->expediteur->prenom ?? 'U', 0, 1) . substr($message->expediteur->nom ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold" style="color: var(--color-nav-text);">
                                            {{ $message->expediteur->id === Auth::id() ? 'Moi' : $message->expediteur->prenom . ' ' . $message->expediteur->nom }}
                                            @if(!$message->lu && $message->id_destinataire === Auth::id())
                                                <span class="text-xs px-1.5 py-0.5 rounded-full bg-red-500 text-white ml-1">Nouveau</span>
                                            @endif
                                        </p>
                                        <p class="text-xs truncate" style="color: var(--color-nav-text); opacity: 0.6;">
                                            {{ Str::limit($message->contenu, 50) }}
                                        </p>
                                    </div>
                                    <span class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-comments text-4xl mb-3" style="color: var(--color-primary-light);"></i>
                            <p style="color: var(--color-nav-text); opacity: 0.6;">Aucun message</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts pour les graphiques -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Couleurs
            const primaryColor = '#D4AF37';
            const primaryDark = '#B8960F';
            const primaryLight = '#FFD700';
            const colors = {
                green: '#22C55E',
                yellow: '#F59E0B',
                red: '#EF4444',
                blue: '#3B82F6',
                purple: '#8B5CF6',
                gray: '#9CA3AF'
            };

            // 1. Graphique des commandes
            const ctx1 = document.getElementById('commandesChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($moisLabels),
                    datasets: [{
                        label: 'Commandes',
                        data: @json($commandesParMois),
                        borderColor: primaryColor,
                        backgroundColor: primaryColor + '33',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: primaryColor,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255,255,255,0.9)',
                            titleColor: '#1F2937',
                            bodyColor: '#1F2937',
                            borderColor: primaryColor,
                            borderWidth: 2,
                            cornerRadius: 10,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' commande(s)';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: '#9CA3AF'
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#9CA3AF'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 2. Graphique des points
            const ctx2 = document.getElementById('pointsChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: @json($moisLabels),
                    datasets: [{
                        label: 'Points gagnés',
                        data: @json($pointsParMois),
                        backgroundColor: primaryColor + '80',
                        borderColor: primaryColor,
                        borderWidth: 2,
                        borderRadius: 6,
                        hoverBackgroundColor: primaryColor
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255,255,255,0.9)',
                            titleColor: '#1F2937',
                            bodyColor: '#1F2937',
                            borderColor: primaryColor,
                            borderWidth: 2,
                            cornerRadius: 10,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' points';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#9CA3AF'
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#9CA3AF'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 3. Graphique de répartition des statuts
            const ctx3 = document.getElementById('statutsChart').getContext('2d');
            new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: ['En attente', 'Validée', 'En cours', 'Livrée', 'Annulée'],
                    datasets: [{
                        data: [
                            {{ $commandesStatuts['en_attente'] }},
                            {{ $commandesStatuts['validee'] }},
                            {{ $commandesStatuts['en_cours'] }},
                            {{ $commandesStatuts['livree'] }},
                            {{ $commandesStatuts['annulee'] }}
                        ],
                        backgroundColor: [
                            colors.yellow,
                            colors.blue,
                            colors.purple,
                            colors.green,
                            colors.red
                        ],
                        borderColor: '#fff',
                        borderWidth: 3,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: '#1F2937',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255,255,255,0.9)',
                            titleColor: '#1F2937',
                            bodyColor: '#1F2937',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        });
    </script>
    @endpush
</x-app-layout>