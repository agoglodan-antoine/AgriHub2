<nav class="shadow-md sticky top-0 z-50" style="background-color: var(--color-bg-white); box-shadow: var(--shadow-md);">
    <!-- Section supérieure -->
    <div style="background: linear-gradient(135deg, var(--color-nav-bg-start), var(--color-nav-bg-end)); border-bottom: 1px solid var(--color-nav-border);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between gap-4">
                <!-- Logo et Nom -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 flex-shrink-0">
                    @if($settings->logo && file_exists(public_path($settings->logo)))
                        <img src="{{ asset($settings->logo) }}" alt="Logo" class="h-8 sm:h-10 w-auto">
                    @else
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                            <i class="fas fa-seedling text-white text-sm sm:text-lg"></i>
                        </div>
                    @endif
                    <div class="hidden xs:block">
                        <h1 class="text-lg sm:text-xl font-bold" style="color: var(--color-primary-dark);">{{ $settings->nom_plateforme }}</h1>
                        <p class="text-xs hidden sm:block" style="color: var(--color-secondary);">{{ $settings->slogan }}</p>
                    </div>
                </a>
                
                <!-- Champ de recherche -->
                <div class="hidden md:block flex-1 max-w-xl mx-4">
                    <form action="{{ route('annonces.index') }}" method="GET" class="relative">
                        <input type="text" name="search" 
                               placeholder="Rechercher des annonces..." 
                               class="w-full px-4 py-2 pl-10 pr-4 rounded-full border focus:outline-none focus:ring-2 transition-colors"
                               style="border-color: var(--color-nav-border); background-color: var(--color-bg-white); color: var(--color-nav-text);">
                        <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search" style="color: var(--color-primary);"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Bouton recherche mobile -->
                <button id="mobile-search-btn" class="md:hidden w-8 h-8 rounded-full transition flex items-center justify-center hover:scale-105" style="background-color: var(--color-nav-bg-start); color: var(--color-nav-highlight);">
                    <i class="fas fa-search text-sm"></i>
                </button>
                
                <!-- Bouton aide "?" -->
                <a href="{{ route('faq') }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full transition flex items-center justify-center flex-shrink-0 hover:scale-105" style="background-color: var(--color-nav-bg-start); color: var(--color-primary-dark);">
                    <i class="fas fa-question text-sm sm:text-lg"></i>
                </a>
                
                <!-- Bouton mode sombre/clair -->
                <button id="darkModeToggle" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full transition flex items-center justify-center flex-shrink-0 hover:scale-105" style="background-color: var(--color-nav-bg-start); color: var(--color-primary-dark);">
                    <i id="darkModeIcon" class="fas fa-moon text-sm sm:text-lg"></i>
                </button>
                
                <!-- Menu utilisateur -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 focus:outline-none transition-transform hover:scale-105">
                            @php
                                $avatar = Auth::user()->avatar ?? null;
                                $initiales = strtoupper(substr(Auth::user()->prenom, 0, 1) . substr(Auth::user()->nom, 0, 1));
                                $unreadCount = App\Models\Message::where('id_destinataire', Auth::id())->where('lu', false)->count();
                            @endphp
                            
                            @if($avatar && file_exists(public_path($avatar)))
                                <img src="{{ asset($avatar) }}" alt="Avatar" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover border-2" style="border-color: var(--color-primary);">
                            @else
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-semibold text-white" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                    {{ $initiales }}
                                </div>
                            @endif
                            
                            <!-- Badge de messages non lus sur l'avatar -->
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold text-white animate-pulse"
                                      style="background-color: #EF4444; border: 2px solid var(--color-bg-white);">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                            
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{'rotate-180': open}" style="color: var(--color-nav-text);"></i>
                        </button>
                        
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg z-50" style="background-color: var(--color-bg-white); border: 1px solid var(--color-nav-border);">
                            <div class="py-2">
                                <div class="px-4 py-3 border-b" style="border-color: var(--color-nav-border);">
                                    <p class="text-sm font-semibold" style="color: var(--color-nav-text);">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                                    <p class="text-xs mt-1" style="color: var(--color-secondary);">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors dropdown-link" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-tachometer-alt w-5 mr-3" style="color: var(--color-primary);"></i> Dashboard
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors dropdown-link" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-user w-5 mr-3" style="color: var(--color-primary);"></i> Mon profil
                                </a>
                                <a href="{{ route('mes-annonces') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors dropdown-link" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-box w-5 mr-3" style="color: var(--color-primary);"></i> Mes annonces
                                </a>
                                <a href="{{ route('mes-transactions') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors dropdown-link" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-exchange-alt w-5 mr-3" style="color: var(--color-primary);"></i> Mes transactions
                                </a>
                                <a href="{{ route('mes-points') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors dropdown-link" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-gem w-5 mr-3" style="color: var(--color-primary);"></i> Mes points
                                </a>
                                
                                <!-- Lien vers la messagerie avec compteur -->
                                <a href="{{ route('messagerie.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors dropdown-link relative" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-comments w-5 mr-3" style="color: var(--color-primary);"></i> Mes messages
                                    @php
                                        $unreadCount = App\Models\Message::where('id_destinataire', Auth::id())->where('lu', false)->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="ml-auto px-2 py-0.5 rounded-full text-xs font-bold text-white animate-pulse"
                                              style="background-color: #EF4444;">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </a>
                                
                                <div class="border-t my-1" style="border-color: var(--color-nav-border);"></div>
                                
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2.5 text-sm transition-colors dropdown-link" style="color: #dc2626;" onmouseover="this.style.backgroundColor='#FEE2E2'" onmouseout="this.style.backgroundColor='transparent'">
                                        <i class="fas fa-sign-out-alt w-5 mr-3"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-white px-5 py-2 rounded-full transition shadow-md hover:scale-105" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                        <i class="fas fa-sign-in-alt mr-2"></i> Connexion
                    </a>
                @endauth
                
                <!-- Menu hamburger -->
                <button id="mobile-menu-toggle" class="lg:hidden w-8 h-8 rounded-full transition flex items-center justify-center hover:scale-105" style="background-color: var(--color-nav-bg-start); color: var(--color-nav-highlight);">
                    <i class="fas fa-bars text-sm"></i>
                </button>
            </div>
            
            <!-- Recherche mobile -->
            <div id="mobile-search" class="hidden md:hidden mt-3 pt-3" style="border-top: 1px solid var(--color-nav-border);">
                <form action="{{ route('annonces.index') }}" method="GET" class="relative">
                    <input type="text" name="search" placeholder="Rechercher..." 
                           class="w-full px-4 py-2 pl-10 pr-4 rounded-full border focus:outline-none focus:ring-2 transition-colors"
                           style="border-color: var(--color-nav-border); background-color: var(--color-bg-white); color: var(--color-nav-text);">
                    <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <i class="fas fa-search" style="color: var(--color-primary);"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Navigation Desktop -->
    <div class="hidden lg:block" style="background-color: var(--color-bg-white);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between flex-wrap">
                <!-- Bande décorative -->
                <div class="flex items-center h-12">
                    <div class="flex h-full">
                        <div class="w-8 h-full" style="background: linear-gradient(to bottom, var(--color-strip-1), var(--color-primary-light));"></div>
                        <div class="w-8 h-full" style="background: linear-gradient(to bottom, var(--color-primary-light), var(--color-primary));"></div>
                        <div class="w-8 h-full" style="background: linear-gradient(to bottom, var(--color-primary), var(--color-primary-dark));"></div>
                        <div class="w-8 h-full" style="background: linear-gradient(to bottom, var(--color-primary-dark), var(--color-secondary));"></div>
                        <div class="w-8 h-full" style="background: linear-gradient(to bottom, var(--color-secondary), var(--color-secondary-dark));"></div>
                    </div>
                    <div class="w-0 h-0 border-t-[24px] border-t-transparent border-l-[20px] border-b-[24px] border-b-transparent" style="border-left-color: var(--color-primary);"></div>
                </div>
                
                <!-- Liens de navigation -->
                <div class="flex items-center space-x-6 py-3">
                    <a href="{{ route('home') }}" class="font-medium transition relative group" style="color: var(--color-nav-text);">
                        Accueil
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 transition-all group-hover:w-full" style="background-color: var(--color-primary);"></span>
                    </a>
                    
                    <!-- Dropdown Annonces -->
                    <div class="relative group/dropdown">
                        <button class="font-medium transition flex items-center gap-1" style="color: var(--color-nav-text);">
                            Annonces
                            <i class="fas fa-chevron-down text-xs transition-transform group-hover/dropdown:rotate-180"></i>
                        </button>
                        
                        <div class="absolute top-full left-0 w-64 rounded-lg opacity-0 invisible group-hover/dropdown:opacity-100 group-hover/dropdown:visible transition-all duration-200 z-50 shadow-lg" style="background-color: var(--color-bg-white); border: 1px solid var(--color-nav-border);">
                            <div class="py-2">
                                <a href="{{ route('annonces.animaux.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-paw w-5 mr-3" style="color: var(--color-primary);"></i> Animaux
                                </a>
                                <a href="{{ route('annonces.escrements.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-leaf w-5 mr-3" style="color: var(--color-primary);"></i> Escrements / Fumier
                                </a>
                                <a href="{{ route('annonces.aliments.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-apple-alt w-5 mr-3" style="color: var(--color-primary);"></i> Aliment / Provende
                                </a>
                                <a href="{{ route('annonces.accessoires.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-tools w-5 mr-3" style="color: var(--color-primary);"></i> Accessoires
                                </a>
                                <div class="border-t my-1" style="border-color: var(--color-nav-border);"></div>
                                <a href="{{ route('annonces.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-list w-5 mr-3" style="color: var(--color-primary);"></i> Toutes les annonces
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dropdown Services -->
                    <div class="relative group/dropdown">
                        <button class="font-medium transition flex items-center gap-1" style="color: var(--color-nav-text);">
                            Services
                            <i class="fas fa-chevron-down text-xs transition-transform group-hover/dropdown:rotate-180"></i>
                        </button>
                        
                        <div class="absolute top-full left-0 w-56 rounded-lg opacity-0 invisible group-hover/dropdown:opacity-100 group-hover/dropdown:visible transition-all duration-200 z-50 shadow-lg" style="background-color: var(--color-bg-white); border: 1px solid var(--color-nav-border);">
                            <div class="py-2">
                                <a href="{{ route('services-veterinaires.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-stethoscope w-5 mr-3" style="color: var(--color-primary);"></i> Vétérinaires
                                </a>
                                <a href="{{ route('transporteurs.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='var(--color-primary)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-nav-text)'">
                                    <i class="fas fa-truck w-5 mr-3" style="color: var(--color-primary);"></i> Transporteurs
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dashboard -->
                    @auth
                        <a href="{{ route('dashboard') }}" class="font-medium transition relative group" style="color: var(--color-nav-text);">
                            Dashboard
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 transition-all group-hover:w-full" style="background-color: var(--color-primary);"></span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="font-medium transition relative group" style="color: var(--color-nav-text);">
                            Dashboard
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 transition-all group-hover:w-full" style="background-color: var(--color-primary);"></span>
                        </a>
                    @endauth
                    
                    <!-- Lien Messagerie dans la navbar desktop -->
                    @auth
                        <a href="{{ route('messagerie.index') }}" class="font-medium transition relative group flex items-center gap-1" style="color: var(--color-nav-text);">
                            <i class="fas fa-comments"></i>
                            Messages
                            @php
                                $unreadCount = App\Models\Message::where('id_destinataire', Auth::id())->where('lu', false)->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold text-white animate-pulse"
                                      style="background-color: #EF4444;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 transition-all group-hover:w-full" style="background-color: var(--color-primary);"></span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    
    <!-- Menu Mobile -->
    <div id="mobile-menu" class="hidden lg:hidden" style="background-color: var(--color-bg-white); border-top: 1px solid var(--color-nav-border);">
        <div class="max-w-7xl mx-auto px-4 py-3 space-y-2">
            <a href="{{ route('home') }}" class="flex items-center py-3 px-3 rounded-lg transition font-medium" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                <i class="fas fa-home w-5 mr-3" style="color: var(--color-primary);"></i> Accueil
            </a>
            
            <!-- Dropdown Annonces mobile -->
            <div x-data="{ open: false }" class="w-full">
                <button @click="open = !open" class="w-full flex items-center justify-between py-3 px-3 rounded-lg transition font-medium" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                    <span><i class="fas fa-box w-5 mr-3" style="color: var(--color-primary);"></i> Annonces</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{'rotate-180': open}" style="color: var(--color-nav-text);"></i>
                </button>
                <div x-show="open" x-collapse class="pl-6 space-y-1 mt-1">
                    <a href="{{ route('annonces.animaux.index') }}" class="flex items-center py-2 px-3 rounded-lg transition" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-paw w-5 mr-3" style="color: var(--color-primary);"></i> Animaux
                    </a>
                    <a href="{{ route('annonces.escrements.index') }}" class="flex items-center py-2 px-3 rounded-lg transition" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-leaf w-5 mr-3" style="color: var(--color-primary);"></i> Escrements / Fumier
                    </a>
                    <a href="{{ route('annonces.aliments.index') }}" class="flex items-center py-2 px-3 rounded-lg transition" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-apple-alt w-5 mr-3" style="color: var(--color-primary);"></i> Aliment / Provende
                    </a>
                    <a href="{{ route('annonces.accessoires.index') }}" class="flex items-center py-2 px-3 rounded-lg transition" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-tools w-5 mr-3" style="color: var(--color-primary);"></i> Accessoires
                    </a>
                    <a href="{{ route('annonces.index') }}" class="flex items-center py-2 px-3 rounded-lg transition" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-list w-5 mr-3" style="color: var(--color-primary);"></i> Toutes les annonces
                    </a>
                </div>
            </div>
            
            <!-- Dropdown Services mobile -->
            <div x-data="{ open: false }" class="w-full">
                <button @click="open = !open" class="w-full flex items-center justify-between py-3 px-3 rounded-lg transition font-medium" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                    <span><i class="fas fa-concierge-bell w-5 mr-3" style="color: var(--color-primary);"></i> Services</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{'rotate-180': open}" style="color: var(--color-nav-text);"></i>
                </button>
                <div x-show="open" x-collapse class="pl-6 space-y-1 mt-1">
                    <a href="{{ route('services-veterinaires.index') }}" class="flex items-center py-2 px-3 rounded-lg transition" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-stethoscope w-5 mr-3" style="color: var(--color-primary);"></i> Vétérinaires
                    </a>
                    <a href="{{ route('transporteurs.index') }}" class="flex items-center py-2 px-3 rounded-lg transition" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-truck w-5 mr-3" style="color: var(--color-primary);"></i> Transporteurs
                    </a>
                </div>
            </div>
            
            <!-- Dashboard mobile -->
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center py-3 px-3 rounded-lg transition font-medium" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fas fa-tachometer-alt w-5 mr-3" style="color: var(--color-primary);"></i> Dashboard
                </a>
                
                <!-- Messagerie mobile -->
                <a href="{{ route('messagerie.index') }}" class="flex items-center py-3 px-3 rounded-lg transition font-medium relative" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fas fa-comments w-5 mr-3" style="color: var(--color-primary);"></i> Messages
                    @php
                        $unreadCount = App\Models\Message::where('id_destinataire', Auth::id())->where('lu', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="ml-auto px-2 py-0.5 rounded-full text-xs font-bold text-white animate-pulse"
                              style="background-color: #EF4444;">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
            @else
                <a href="{{ route('login') }}" class="flex items-center py-3 px-3 rounded-lg transition font-medium" style="color: var(--color-nav-text);" onmouseover="this.style.backgroundColor='#F3F4F6'" onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fas fa-tachometer-alt w-5 mr-3" style="color: var(--color-primary);"></i> Dashboard
                </a>
            @endauth
        </div>
    </div>
</nav>

<script>
    // Dark mode
    function initDarkMode() {
        const savedMode = localStorage.getItem('darkMode');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark = savedMode !== null ? savedMode === 'true' : systemPrefersDark;
        
        if (isDark) {
            document.body.classList.add('dark');
        } else {
            document.body.classList.remove('dark');
        }
        
        updateDarkModeIcon(isDark);
        return isDark;
    }
    
    function updateDarkModeIcon(isDark) {
        const icon = document.getElementById('darkModeIcon');
        if (icon) {
            icon.className = isDark ? 'fas fa-sun text-sm sm:text-lg' : 'fas fa-moon text-sm sm:text-lg';
        }
    }
    
    function toggleDarkMode() {
        const isDark = document.body.classList.toggle('dark');
        localStorage.setItem('darkMode', isDark);
        updateDarkModeIcon(isDark);
        
        // Forcer la mise à jour des couleurs de survol
        document.querySelectorAll('[onmouseover], [onmouseout]').forEach(el => {
            const hoverBg = el.getAttribute('data-hover-bg');
            const originalBg = el.getAttribute('data-original-bg');
            if (hoverBg && originalBg) {
                if (isDark) {
                    el.style.backgroundColor = hoverBg;
                } else {
                    el.style.backgroundColor = originalBg;
                }
            }
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        initDarkMode();
        
        const toggleBtn = document.getElementById('darkModeToggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleDarkMode);
        }
    });
    
    // Mobile menu toggle
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Mobile search toggle
    const searchBtn = document.getElementById('mobile-search-btn');
    const mobileSearch = document.getElementById('mobile-search');
    
    if (searchBtn && mobileSearch) {
        searchBtn.addEventListener('click', function() {
            mobileSearch.classList.toggle('hidden');
        });
    }
    
    // Close mobile menu on link click
    const mobileLinks = document.querySelectorAll('#mobile-menu a, #mobile-menu button');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (mobileMenu) {
                mobileMenu.classList.add('hidden');
            }
        });
    });
    
    // Dropdown hover handling
    const dropdowns = document.querySelectorAll('.group\\/dropdown');
    dropdowns.forEach(dropdown => {
        let timeout;
        
        dropdown.addEventListener('mouseenter', () => {
            clearTimeout(timeout);
            const menu = dropdown.querySelector('div.absolute');
            if (menu) {
                menu.classList.add('opacity-100', 'visible');
                menu.classList.remove('opacity-0', 'invisible');
            }
        });
        
        dropdown.addEventListener('mouseleave', () => {
            timeout = setTimeout(() => {
                const menu = dropdown.querySelector('div.absolute');
                if (menu) {
                    menu.classList.remove('opacity-100', 'visible');
                    menu.classList.add('opacity-0', 'invisible');
                }
            }, 100);
        });
    });
</script>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    @media (min-width: 480px) {
        .xs\:block {
            display: block;
        }
    }
    
    #mobile-menu {
        transition: all var(--transition-normal);
        max-height: 80vh;
        overflow-y: auto;
    }
    
    input:focus {
        outline: none;
        ring-width: 2px;
        ring-color: var(--color-primary);
        border-color: var(--color-primary);
    }
    
    a, button {
        transition: all 0.2s ease;
    }
    
    /* Mode sombre - couleurs de survol */
    body.dark .group\/dropdown div.absolute a:hover {
        background-color: #374151 !important;
        color: var(--color-primary) !important;
    }
    
    body.dark #mobile-menu a:hover,
    body.dark #mobile-menu button:hover {
        background-color: #374151 !important;
    }
    
    body.dark .dropdown-link:hover {
        background-color: #374151 !important;
        color: var(--color-primary) !important;
    }
    
    /* Mode clair - couleurs de survol */
    .group\/dropdown div.absolute a:hover {
        background-color: #F3F4F6 !important;
        color: var(--color-primary) !important;
    }
    
    #mobile-menu a:hover,
    #mobile-menu button:hover {
        background-color: #F3F4F6 !important;
    }
    
    .dropdown-link:hover {
        background-color: #F3F4F6 !important;
        color: var(--color-primary) !important;
    }
    
    /* Animation pour l'avatar */
    .avatar-dropdown-btn:hover {
        transform: scale(1.05);
    }
    
    /* Scrollbar personnalisée */
    #mobile-menu::-webkit-scrollbar {
        width: 4px;
    }
    
    #mobile-menu::-webkit-scrollbar-track {
        background: var(--color-nav-border);
        border-radius: 10px;
    }
    
    #mobile-menu::-webkit-scrollbar-thumb {
        background: var(--color-primary);
        border-radius: 10px;
    }
    
    /* Animation de pulsation pour les notifications */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }
    
    .animate-pulse {
        animation: pulse 2s infinite;
    }
</style>