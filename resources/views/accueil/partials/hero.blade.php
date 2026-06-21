<div class="relative overflow-hidden" style="background: linear-gradient(135deg, #D4AF37 0%, #B8960F 40%, #8B6914 100%);">
    <!-- Formes décoratives -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full" style="background: radial-gradient(circle, rgba(255,215,0,0.25) 0%, rgba(255,215,0,0) 70%);"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full" style="background: radial-gradient(circle, rgba(255,215,0,0.2) 0%, rgba(255,215,0,0) 70%);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full" style="background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, rgba(255,215,0,0) 70%);"></div>
        <div class="absolute top-20 left-10 opacity-15">
            <i class="fas fa-leaf text-7xl text-white"></i>
        </div>
        <div class="absolute bottom-20 right-10 opacity-15">
            <i class="fas fa-seedling text-7xl text-white"></i>
        </div>
        <div class="absolute top-1/2 left-1/4 opacity-8">
            <i class="fas fa-tractor text-8xl text-white"></i>
        </div>
        <div class="absolute bottom-1/3 right-1/4 opacity-10">
            <i class="fas fa-paw text-6xl text-white"></i>
        </div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 lg:py-36">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Texte -->
            <div data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center px-5 py-2.5 rounded-full mb-6" style="background: rgba(255,215,0,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,215,0,0.3);">
                    <span class="w-3 h-3 rounded-full mr-2 animate-pulse" style="background-color: #FFD700;"></span>
                    <span class="text-white text-sm font-semibold tracking-wider">🇧🇯 Plateforme N°1 au Bénin</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-6 leading-tight text-white">
                    Bienvenue sur 
                    <span class="relative inline-block">
                        <span style="color: #FFD700; text-shadow: 0 0 30px rgba(255,215,0,0.3);">{{ $settings->nom_plateforme ?? 'AgriHub Bénin' }}</span>
                        <svg class="absolute bottom-0 left-0 w-full h-3 -z-10" preserveAspectRatio="none" viewBox="0 0 200 10">
                            <path d="M0,5 Q50,10 100,5 Q150,0 200,5" fill="none" stroke="#FFD700" stroke-width="2" opacity="0.6"/>
                        </svg>
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl mb-8 leading-relaxed text-white/90">
                    La première plateforme connectée pour l'élevage moderne au Bénin. 
                    Achetez, vendez, et gérez votre activité en toute simplicité.
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="group relative px-8 py-4 rounded-full font-bold shadow-xl transition-all duration-300 overflow-hidden"
                       style="background: linear-gradient(135deg, #FFD700, #F59E0B); color: #78350F;">
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-rocket mr-2"></i>
                            Commencer maintenant
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </span>
                        <span class="absolute inset-0 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left" 
                              style="background: linear-gradient(135deg, #F59E0B, #D97706);"></span>
                    </a>
                    <a href="#" class="group px-8 py-4 rounded-full font-bold shadow-xl transition-all duration-300"
                       style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); color: white; border: 2px solid rgba(255,255,255,0.3);"
                       onmouseover="this.style.backgroundColor='rgba(255,255,255,0.25)'; this.style.transform='translateY(-2px)'; this.style.borderColor='rgba(255,255,255,0.5)'"
                       onmouseout="this.style.backgroundColor='rgba(255,255,255,0.15)'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(255,255,255,0.3)'">
                        <i class="fas fa-search mr-2"></i>
                        Explorer les annonces
                    </a>
                </div>
                
                <!-- Statistiques -->
                <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t" style="border-color: rgba(255,255,255,0.2);">
                    <div class="text-center group cursor-pointer">
                        <div class="text-3xl font-bold text-white mb-1 flex items-center justify-center gap-1">
                            {{ number_format($statistiques['total_eleveurs'] ?? 0) }}
                            <span class="text-xl">+</span>
                        </div>
                        <div class="text-sm text-white/80">Éleveurs</div>
                        <div class="w-full h-1.5 mt-2 rounded-full overflow-hidden" style="background-color: rgba(255,255,255,0.2);">
                            <div class="h-full rounded-full transition-all duration-700 group-hover:w-full" style="width: 75%; background: linear-gradient(90deg, #FFD700, #F59E0B);"></div>
                        </div>
                    </div>
                    <div class="text-center group cursor-pointer">
                        <div class="text-3xl font-bold text-white mb-1 flex items-center justify-center gap-1">
                            {{ number_format($statistiques['total_annonces'] ?? 0) }}
                            <span class="text-xl">+</span>
                        </div>
                        <div class="text-sm text-white/80">Annonces</div>
                        <div class="w-full h-1.5 mt-2 rounded-full overflow-hidden" style="background-color: rgba(255,255,255,0.2);">
                            <div class="h-full rounded-full transition-all duration-700 group-hover:w-full" style="width: 85%; background: linear-gradient(90deg, #FFD700, #F59E0B);"></div>
                        </div>
                    </div>
                    <div class="text-center group cursor-pointer">
                        <div class="text-3xl font-bold text-white mb-1 flex items-center justify-center gap-1">
                            {{ number_format($statistiques['total_veterinaires'] ?? 0) }}
                            <span class="text-xl">+</span>
                        </div>
                        <div class="text-sm text-white/80">Vétérinaires</div>
                        <div class="w-full h-1.5 mt-2 rounded-full overflow-hidden" style="background-color: rgba(255,255,255,0.2);">
                            <div class="h-full rounded-full transition-all duration-700 group-hover:w-full" style="width: 60%; background: linear-gradient(90deg, #FFD700, #F59E0B);"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image -->
            <div data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="relative">
                    <!-- Cartes flottantes -->
                    <div class="absolute -top-6 -left-6 z-10 px-5 py-3 rounded-xl shadow-2xl animate-bounce-slow"
                         style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255,215,0,0.3);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #FFD700, #F59E0B);">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold" style="color: #78350F;">Qualité garantie</div>
                                <div class="text-xs" style="color: #92400E;">100% satisfait</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-6 -right-6 z-10 px-5 py-3 rounded-xl shadow-2xl animate-bounce-slow-delayed"
                         style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255,215,0,0.3);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #FFD700, #F59E0B);">
                                <i class="fas fa-truck-fast text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold" style="color: #78350F;">Livraison rapide</div>
                                <div class="text-xs" style="color: #92400E;">Partout au Bénin</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Image principale -->
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl transform transition-all duration-500 hover:scale-105"
                         style="background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,215,0,0.2));">
                        <img src="{{ asset('images/hero.png') }}" 
                            alt="Agriculture" 
                            class="w-full h-auto object-cover">
                        <div class="absolute inset-0" style="background: linear-gradient(45deg, rgba(212,175,55,0.15), rgba(255,215,0,0.05));"></div>
                    </div>
                    
                    <!-- Badge de confiance -->
                    <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full flex items-center justify-center shadow-2xl animate-pulse-slow"
                         style="background: linear-gradient(135deg, #FFD700, #F59E0B); border: 3px solid white;">
                        <div class="text-center">
                            <i class="fas fa-shield-alt text-2xl text-white"></i>
                            <div class="text-[8px] font-bold text-white leading-none mt-0.5">SÉCURISÉ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }
    
    @keyframes bounce-slow-delayed {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }
    
    @keyframes pulse-slow {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.08); opacity: 0.9; }
    }
    
    .animate-bounce-slow { animation: bounce-slow 3s ease-in-out infinite; }
    .animate-bounce-slow-delayed { animation: bounce-slow-delayed 3s ease-in-out infinite 1.5s; }
    .animate-pulse-slow { animation: pulse-slow 2s ease-in-out infinite; }
</style>