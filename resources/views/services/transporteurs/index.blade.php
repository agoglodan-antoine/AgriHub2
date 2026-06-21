<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-truck text-4xl" style="color: var(--color-primary-light);"></i>
                    Transporteurs disponibles
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">Transport fiable et sécurisé pour vos animaux et produits agricoles</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Barre de recherche et filtres -->
            <div class="mb-8">
                <form action="{{ route('service.transporteur.index') }}" method="GET" id="filter-form">
                    <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                        <div class="flex flex-col md:flex-row gap-4 mb-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Rechercher un transporteur par nom, type de véhicule..." 
                                           class="w-full px-4 py-3 pl-11 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                           style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2" style="color: var(--color-primary);"></i>
                                </div>
                            </div>
                            <button type="submit" 
                                    class="px-6 py-3 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center justify-center gap-2"
                                    style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                            @if(request()->anyFilled(['search', 'type_vehicule', 'zone', 'ville', 'commune', 'capacite_min', 'capacite_max', 'sort']))
                                <a href="{{ route('service.transporteur.index') }}" 
                                   class="px-6 py-3 rounded-lg font-semibold transition text-center flex items-center justify-center gap-2"
                                   style="background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                                    <i class="fas fa-times"></i> Réinitialiser
                                </a>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Filtre par type de véhicule -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-truck mr-1" style="color: var(--color-primary);"></i> Type de véhicule
                                </label>
                                <select name="type_vehicule" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Tous les types</option>
                                    @foreach($typesVehicules as $type)
                                        <option value="{{ $type }}" {{ request('type_vehicule') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre par zone d'intervention -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-map-marker-alt mr-1" style="color: var(--color-primary);"></i> Zone
                                </label>
                                <select name="zone" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Toutes les zones</option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone }}" {{ request('zone') == $zone ? 'selected' : '' }}>
                                            {{ ucfirst($zone) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre par capacité minimum -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-weight-hanging mr-1" style="color: var(--color-primary);"></i> Capacité min (kg)
                                </label>
                                <input type="number" 
                                       name="capacite_min" 
                                       value="{{ request('capacite_min') }}"
                                       placeholder="Min" 
                                       class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                       style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                       min="0">
                            </div>

                            <!-- Filtre par capacité maximum -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-weight-hanging mr-1" style="color: var(--color-primary);"></i> Capacité max (kg)
                                </label>
                                <input type="number" 
                                       name="capacite_max" 
                                       value="{{ request('capacite_max') }}"
                                       placeholder="Max" 
                                       class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                       style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                       min="0">
                            </div>
                        </div>

                        <!-- Deuxième ligne de filtres -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                            <!-- Filtre par ville -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-city mr-1" style="color: var(--color-primary);"></i> Ville
                                </label>
                                <select name="ville" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Toutes les villes</option>
                                    @foreach($villes as $ville)
                                        <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>
                                            {{ ucfirst($ville) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre par commune -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-map-pin mr-1" style="color: var(--color-primary);"></i> Commune
                                </label>
                                <select name="commune" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Toutes les communes</option>
                                    @foreach($communes as $commune)
                                        <option value="{{ $commune }}" {{ request('commune') == $commune ? 'selected' : '' }}>
                                            {{ ucfirst($commune) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tri -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-sort mr-1" style="color: var(--color-primary);"></i> Trier par
                                </label>
                                <select name="sort" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                                    <option value="ancien" {{ request('sort') == 'ancien' ? 'selected' : '' }}>Plus ancien</option>
                                    <option value="nom_asc" {{ request('sort') == 'nom_asc' ? 'selected' : '' }}>Nom A-Z</option>
                                    <option value="nom_desc" {{ request('sort') == 'nom_desc' ? 'selected' : '' }}>Nom Z-A</option>
                                    <option value="capacite_asc" {{ request('sort') == 'capacite_asc' ? 'selected' : '' }}>Capacité croissante</option>
                                    <option value="capacite_desc" {{ request('sort') == 'capacite_desc' ? 'selected' : '' }}>Capacité décroissante</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 text-right">
                            <button type="submit" 
                                    class="px-6 py-2 rounded-lg font-semibold transition hover:scale-105 text-white"
                                    style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                <i class="fas fa-filter mr-2"></i> Appliquer les filtres
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Résultats -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <span style="color: var(--color-nav-text);">
                        <strong>{{ $transporteurs->total() }}</strong> transporteur(s) trouvé(s)
                    </span>
                </div>
            </div>

            @if($transporteurs->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($transporteurs as $transporteur)
                        @include('components.carousel-transporteur', ['transporteur' => $transporteur])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $transporteurs->links() }}
                </div>
            @else
                <div class="text-center py-16 rounded-xl" style="background-color: var(--color-bg-white);">
                    <i class="fas fa-truck text-6xl mb-4" style="color: var(--color-primary-light);"></i>
                    <h3 class="text-2xl font-bold mb-2" style="color: var(--color-nav-text);">Aucun transporteur trouvé</h3>
                    <p style="color: var(--color-nav-text); opacity: 0.6;">Aucun transporteur ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('service.transporteur.index') }}" class="inline-block mt-4 px-6 py-2 rounded-lg text-white transition hover:scale-105" 
                       style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                        <i class="fas fa-undo mr-2"></i> Voir tous les transporteurs
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>z