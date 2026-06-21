<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-list text-4xl" style="color: var(--color-primary-light);"></i>
                    Toutes les annonces
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">Découvrez toutes les annonces disponibles sur la plateforme</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Barre de recherche et filtres -->
            <div class="mb-8">
                <form action="{{ route('annonce.all.index') }}" method="GET" id="filter-form">
                    <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                        <!-- Recherche principale -->
                        <div class="flex flex-col md:flex-row gap-4 mb-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Rechercher dans toutes les annonces..." 
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
                            @if(request()->anyFilled(['search', 'type', 'categorie', 'espece', 'race', 'type_aliment', 'vendeur_nom', 'prix_min', 'prix_max', 'sort', 'date_filter']))
                                <a href="{{ route('annonce.all.index') }}" 
                                   class="px-6 py-3 rounded-lg font-semibold transition text-center flex items-center justify-center gap-2"
                                   style="background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                                    <i class="fas fa-times"></i> Réinitialiser
                                </a>
                            @endif
                        </div>

                        <!-- Filtres avancés -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Filtre par type d'annonce -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-tag mr-1" style="color: var(--color-primary);"></i> Type
                                </label>
                                <select name="type" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Tous les types</option>
                                    @foreach($types as $key => $label)
                                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre par catégorie (accessoires) -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-folder mr-1" style="color: var(--color-primary);"></i> Catégorie
                                </label>
                                <select name="categorie" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{ $categorie }}" {{ request('categorie') == $categorie ? 'selected' : '' }}>
                                            {{ $categorie }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre par espèce -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-paw mr-1" style="color: var(--color-primary);"></i> Espèce
                                </label>
                                <select name="espece" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Toutes les espèces</option>
                                    @foreach($especes as $espece)
                                        <option value="{{ $espece->id }}" {{ request('espece') == $espece->id ? 'selected' : '' }}>
                                            {{ $espece->label ?? $espece->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre par race (animaux) -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-dog mr-1" style="color: var(--color-primary);"></i> Race
                                </label>
                                <select name="race" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Toutes les races</option>
                                    @foreach($races as $race)
                                        <option value="{{ $race->id }}" {{ request('race') == $race->id ? 'selected' : '' }}>
                                            {{ $race->label ?? $race->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Deuxième ligne de filtres -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                            <!-- Filtre par type d'aliment -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-apple-alt mr-1" style="color: var(--color-primary);"></i> Type aliment
                                </label>
                                <select name="type_aliment" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Tous les types</option>
                                    @foreach($typesAliments as $typeAliment)
                                        <option value="{{ $typeAliment }}" {{ request('type_aliment') == $typeAliment ? 'selected' : '' }}>
                                            {{ ucfirst($typeAliment) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre par vendeur -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-user mr-1" style="color: var(--color-primary);"></i> Vendeur
                                </label>
                                <input type="text" 
                                       name="vendeur_nom" 
                                       value="{{ request('vendeur_nom') }}"
                                       placeholder="Nom du vendeur..." 
                                       class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                       style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                            </div>

                            <!-- Filtre par prix -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-coins mr-1" style="color: var(--color-primary);"></i> Prix max (FCFA)
                                </label>
                                <input type="number" 
                                       name="prix_max" 
                                       value="{{ request('prix_max') }}"
                                       placeholder="Max" 
                                       class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                       style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                       min="0">
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
                                    <option value="prix_asc" {{ request('sort') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                                    <option value="prix_desc" {{ request('sort') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                </select>
                            </div>
                        </div>

                        <!-- Troisième ligne de filtres -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <!-- Prix min -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-coins mr-1" style="color: var(--color-primary);"></i> Prix min (FCFA)
                                </label>
                                <input type="number" 
                                       name="prix_min" 
                                       value="{{ request('prix_min') }}"
                                       placeholder="Min" 
                                       class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                       style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                       min="0">
                            </div>

                            <!-- Filtre par date -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-calendar mr-1" style="color: var(--color-primary);"></i> Date
                                </label>
                                <select name="date_filter" 
                                        class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                        style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                        onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Toutes les dates</option>
                                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>Ce mois</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bouton appliquer les filtres -->
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
            <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                <div>
                    <span style="color: var(--color-nav-text);">
                        <strong>{{ $annonces->total() }}</strong> annonce(s) trouvée(s)
                    </span>
                </div>
                @auth
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('annonce.animal.create') }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center gap-2"
                           style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                            <i class="fas fa-paw"></i> Animal
                        </a>
                        <a href="{{ route('annonce.aliment.create') }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center gap-2"
                           style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                            <i class="fas fa-apple-alt"></i> Aliment
                        </a>
                        <a href="{{ route('annonce.accessoire.create') }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center gap-2"
                           style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                            <i class="fas fa-tools"></i> Accessoire
                        </a>
                        <a href="{{ route('annonce.escrement.create') }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center gap-2"
                           style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                            <i class="fas fa-leaf"></i> Escrement
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Grille d'annonces -->
            @if($annonces->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($annonces as $annonce)
                        @include('components.carousel-annonce', ['annonce' => $annonce])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $annonces->links() }}
                </div>
            @else
                <div class="text-center py-16 rounded-xl" style="background-color: var(--color-bg-white);">
                    <i class="fas fa-search text-6xl mb-4" style="color: var(--color-primary-light);"></i>
                    <h3 class="text-2xl font-bold mb-2" style="color: var(--color-nav-text);">Aucune annonce trouvée</h3>
                    <p style="color: var(--color-nav-text); opacity: 0.6;">Aucune annonce ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('annonce.all.index') }}" class="inline-block mt-4 px-6 py-2 rounded-lg text-white transition hover:scale-105" 
                       style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                        <i class="fas fa-undo mr-2"></i> Voir toutes les annonces
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de contact -->
    @include('components.contact-modal')
</x-app-layout>