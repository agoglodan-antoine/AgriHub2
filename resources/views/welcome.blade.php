@php
    if(!isset($settings)) {
        $settings = App\Models\Parametre::first();
        if(!$settings) {
            $settings = (object)[
                'nom_plateforme' => 'AgriHub Bénin',
                'logo' => 'logo/agrihub.png',
                'slogan' => "L'harmonie entre les acteurs du secteur élevage au Bénin",
                'mail' => 'contact@agrihub.bj',
                'tel' => '+229 01 23 45 67',
                'bp' => 'BP 123 Cotonou',
                'departement' => 'Littoral',
                'commune' => 'Cotonou',
                'description' => "AgriHub Bénin est une plateforme innovante créée pour harmoniser les relations entre éleveurs, acheteurs, vétérinaires, transporteurs et vendeurs de produits agricoles.",
                'facebook' => '#',
                'whatsapp' => '#',
                'linkedin' => '#',
                'twitter' => '#',
                'instagram' => '#'
            ];
        }
    }
@endphp

<x-app-layout>
    <!-- Hero Section -->
    <div class="hero-section relative overflow-hidden" style="background: linear-gradient(135deg, var(--color-tertiary) 0%, var(--color-tertiary-dark) 100%);">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-6xl font-bold mb-4 animate-fade-in">
                    Bienvenue sur <span style="color: var(--color-primary);">{{ $settings->nom_plateforme }}</span>
                </h1>
                <p class="text-xl md:text-2xl mb-6 max-w-3xl mx-auto animate-slide-up">
                    {{ $settings->slogan }}
                </p>
                <p class="text-md mb-8 max-w-2xl mx-auto animate-slide-up opacity-90">
                    <i class="fas fa-map-marker-alt mr-2"></i> Bénin
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-slide-up">
                    <a href="#" class="px-8 py-3 rounded-full font-semibold transition transform hover:scale-105 shadow-lg" style="background: var(--color-primary); color: #1F2937;">
                        <i class="fas fa-search mr-2"></i> Explorer les annonces
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="px-8 py-3 rounded-full font-semibold transition transform hover:scale-105 shadow-lg" style="background: transparent; border: 2px solid white; color: white;">
                            <i class="fas fa-user-plus mr-2"></i> Créer un compte
                        </a>
                    @endguest
                </div>
            </div>
        </div>
        <!-- Vague décorative -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="w-full">
                <path fill="var(--color-bg-body)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>

    <!-- Section Statistiques -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 -mt-16 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="stat-card text-center p-4 rounded-xl shadow-md transition transform hover:scale-105" style="background-color: var(--color-bg-white);">
                <i class="fas fa-users text-3xl mb-2" style="color: var(--color-primary);"></i>
                <h3 class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ number_format($statistiques['total_utilisateurs']) }}</h3>
                <p class="text-sm" style="color: var(--color-secondary);">Utilisateurs</p>
            </div>
            <div class="stat-card text-center p-4 rounded-xl shadow-md transition transform hover:scale-105" style="background-color: var(--color-bg-white);">
                <i class="fas fa-paw text-3xl mb-2" style="color: var(--color-primary);"></i>
                <h3 class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ number_format($statistiques['total_animaux']) }}</h3>
                <p class="text-sm" style="color: var(--color-secondary);">Animaux</p>
            </div>
            <div class="stat-card text-center p-4 rounded-xl shadow-md transition transform hover:scale-105" style="background-color: var(--color-bg-white);">
                <i class="fas fa-tag text-3xl mb-2" style="color: var(--color-primary);"></i>
                <h3 class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ number_format($statistiques['total_annonces']) }}</h3>
                <p class="text-sm" style="color: var(--color-secondary);">Annonces</p>
            </div>
            <div class="stat-card text-center p-4 rounded-xl shadow-md transition transform hover:scale-105" style="background-color: var(--color-bg-white);">
                <i class="fas fa-tractor text-3xl mb-2" style="color: var(--color-primary);"></i>
                <h3 class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ number_format($statistiques['total_eleveurs']) }}</h3>
                <p class="text-sm" style="color: var(--color-secondary);">Éleveurs</p>
            </div>
            <div class="stat-card text-center p-4 rounded-xl shadow-md transition transform hover:scale-105" style="background-color: var(--color-bg-white);">
                <i class="fas fa-truck text-3xl mb-2" style="color: var(--color-primary);"></i>
                <h3 class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ number_format($statistiques['total_transporteurs']) }}</h3>
                <p class="text-sm" style="color: var(--color-secondary);">Transporteurs</p>
            </div>
            <div class="stat-card text-center p-4 rounded-xl shadow-md transition transform hover:scale-105" style="background-color: var(--color-bg-white);">
                <i class="fas fa-stethoscope text-3xl mb-2" style="color: var(--color-primary);"></i>
                <h3 class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ number_format($statistiques['total_veterinaires']) }}</h3>
                <p class="text-sm" style="color: var(--color-secondary);">Vétérinaires</p>
            </div>
        </div>
    </div>

    <!-- Section Mission -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4" style="color: var(--color-nav-text);">
                Notre <span style="color: var(--color-primary);">Mission</span>
            </h2>
            <p class="text-lg max-w-3xl mx-auto" style="color: var(--color-secondary);">
                {{ $settings->description }}
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <div class="founder-card p-6 rounded-xl shadow-md text-center" style="background-color: var(--color-bg-white);">
                <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                    <i class="fas fa-user-tie text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-1" style="color: var(--color-nav-text);">LASSISSOU Missigbèto Zakari Yaoo</h3>
                <p class="text-sm mb-3" style="color: var(--color-primary);">Co-fondateur & Développeur</p>
                <p class="text-sm" style="color: var(--color-secondary);">
                    Passionné par l'agriculture et les nouvelles technologies, il œuvre pour la digitalisation du secteur élevage au Bénin.
                </p>
            </div>
            
            <div class="founder-card p-6 rounded-xl shadow-md text-center" style="background-color: var(--color-bg-white);">
                <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                    <i class="fas fa-laptop-code text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-1" style="color: var(--color-nav-text);">AGOGLODAN Antoine</h3>
                <p class="text-sm mb-3" style="color: var(--color-primary);">Co-fondateur & Développeur</p>
                <p class="text-sm" style="color: var(--color-secondary);">
                    Expert en solutions digitales, il met son expertise au service des acteurs du secteur agricole béninois.
                </p>
            </div>
        </div>
    </div>

    <!-- Section Services -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" style="background-color: var(--color-bg-gray);">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4" style="color: var(--color-nav-text);">
                Nos <span style="color: var(--color-primary);">Services</span>
            </h2>
            <p class="text-lg max-w-2xl mx-auto" style="color: var(--color-secondary);">
                Découvrez tous les services que nous proposons pour faciliter votre activité agricole au Bénin
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($services as $service)
            <div class="service-card p-6 rounded-xl shadow-md transition-all duration-300 hover:shadow-lg hover:-translate-y-2" style="background-color: var(--color-bg-white);">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                    <i class="{{ $service['icone'] }} text-2xl" style="color: white;"></i>
                </div>
                <h3 class="text-xl font-bold mb-2" style="color: var(--color-nav-text);">{{ $service['titre'] }}</h3>
                <p class="mb-4" style="color: var(--color-secondary);">{{ $service['description'] }}</p>
                <a href="{{ $service['lien'] }}" class="inline-flex items-center font-semibold transition group" style="color: var(--color-primary);">
                    En savoir plus <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Section Annonces récentes -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4" style="color: var(--color-nav-text);">
                Dernières <span style="color: var(--color-primary);">Annonces</span>
            </h2>
            <p class="text-lg max-w-2xl mx-auto" style="color: var(--color-secondary);">
                Découvrez les dernières annonces publiées par nos éleveurs et vendeurs
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($dernieresAnnonces as $annonce)
            <div class="annonce-card rounded-xl overflow-hidden shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-2" style="background-color: var(--color-bg-white);">
                <div class="relative h-48" style="background: linear-gradient(135deg, var(--color-primary-light), var(--color-primary));">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-tag text-5xl text-white opacity-50"></i>
                    </div>
                    <div class="absolute top-3 right-3 bg-white px-2 py-1 rounded-full text-sm font-semibold" style="color: var(--color-primary-dark);">
                        {{ number_format($annonce->prix, 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-nav-text);">{{ $annonce->titre }}</h3>
                    <p class="text-sm mb-3 line-clamp-2" style="color: var(--color-secondary);">{{ Str::limit($annonce->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xs" style="color: var(--color-secondary);">
                            <i class="fas fa-map-marker-alt mr-1"></i> Bénin
                        </span>
                        <a href="{{ route('annonces.show', $annonce->id) }}" class="text-sm font-semibold transition" style="color: var(--color-primary);">
                            Voir détails <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8" style="color: var(--color-secondary);">
                <i class="fas fa-box-open text-5xl mb-3 opacity-50"></i>
                <p>Aucune annonce pour le moment</p>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-10">
            <a href="#" class="inline-flex items-center px-6 py-3 rounded-full font-semibold transition transform hover:scale-105 shadow-md" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); color: #1F2937;">
                Voir toutes les annonces <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Section CTA -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="rounded-2xl overflow-hidden shadow-xl" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="py-12 px-8 text-center text-white">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Rejoignez l'aventure AgriHub Bénin</h2>
                <p class="text-lg mb-8 max-w-2xl mx-auto">
                    Ensemble, construisons un secteur élevage plus connecté et plus prospère au Bénin
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}" class="px-8 py-3 rounded-full font-semibold transition transform hover:scale-105 shadow-lg" style="background-color: #1F2937; color: white;">
                            <i class="fas fa-user-plus mr-2"></i> Créer un compte gratuit
                        </a>
                    @else
                        <a href="{{ route('dashboard.index') }}" class="px-8 py-3 rounded-full font-semibold transition transform hover:scale-105 shadow-lg" style="background-color: #1F2937; color: white;">
                            <i class="fas fa-tachometer-alt mr-2"></i> Accéder à mon dashboard
                        </a>
                    @endguest
                    <a href="#" class="px-8 py-3 rounded-full font-semibold transition transform hover:scale-105 shadow-lg" style="background-color: rgba(255,255,255,0.2); color: white;">
                        <i class="fas fa-search mr-2"></i> Explorer les annonces
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }
        
        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .stat-card, .service-card, .annonce-card, .founder-card {
            transition: all 0.3s ease;
        }
        
        .founder-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
    @endpush
</x-app-layout>