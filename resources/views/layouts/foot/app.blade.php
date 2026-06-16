<!-- Bouton retour en haut -->
<button id="backToTop" class="fixed bottom-8 right-8 z-50 w-12 h-12 rounded-full shadow-lg transition-all transform hover:scale-110 hidden items-center justify-center" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); color: white;">
    <i class="fas fa-arrow-up text-xl"></i>
</button>

<footer class="mt-auto" style="background-color: var(--color-bg-gray);">
    <!-- Newsletter -->
    <div style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center text-white">
                <h3 class="text-2xl font-bold mb-4">Restez informé</h3>
                <p class="mb-6">Recevez nos actualités et offres spéciales</p>
                <form action="{{ url('/newsletter') }}" method="POST" class="max-w-md mx-auto flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="email" name="email" placeholder="Votre adresse email" 
                           class="flex-1 px-4 py-3 rounded-lg focus:outline-none focus:ring-2 transition-colors"
                           style="background-color: var(--color-bg-white); color: var(--color-nav-text); border: 1px solid var(--color-nav-border);"
                           onfocus="this.style.borderColor='var(--color-primary)'; this.style.ringColor='var(--color-primary)'" required>
                    <button type="submit" class="px-6 py-3 rounded-lg font-semibold transition shadow-md hover:scale-105" 
                            style="background-color: var(--color-bg-white); color: var(--color-primary-dark);"
                            onmouseover="this.style.backgroundColor='var(--color-nav-border)'; this.style.color='var(--color-primary)'"
                            onmouseout="this.style.backgroundColor='var(--color-bg-white)'; this.style.color='var(--color-primary-dark)'">
                        S'inscrire
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <!-- Colonne 1: À propos -->
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    @if($settings->logo && file_exists(public_path($settings->logo)))
                        <img src="{{ asset($settings->logo) }}" alt="Logo {{ $settings->nom_plateforme }}" class="h-10 w-auto">
                    @else
                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                            <i class="fas fa-seedling text-white"></i>
                        </div>
                    @endif
                    <h3 class="text-xl font-bold" style="color: var(--color-primary);">{{ $settings->nom_plateforme }}</h3>
                </div>
                <p class="mb-4 leading-relaxed" style="color: var(--color-nav-text);">
                    {{ $settings->description }}
                </p>
                <div class="flex space-x-4">
                    @if($settings->facebook && $settings->facebook != '#')
                        <a href="{{ $settings->facebook }}" target="_blank" rel="noopener noreferrer" class="social-icon transition text-xl transform hover:scale-110" style="color: var(--color-nav-text);">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if($settings->whatsapp && $settings->whatsapp != '#')
                        <a href="{{ $settings->whatsapp }}" target="_blank" rel="noopener noreferrer" class="social-icon transition text-xl transform hover:scale-110" style="color: var(--color-nav-text);">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif
                    @if($settings->linkedin && $settings->linkedin != '#')
                        <a href="{{ $settings->linkedin }}" target="_blank" rel="noopener noreferrer" class="social-icon transition text-xl transform hover:scale-110" style="color: var(--color-nav-text);">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                    @if($settings->twitter && $settings->twitter != '#')
                        <a href="{{ $settings->twitter }}" target="_blank" rel="noopener noreferrer" class="social-icon transition text-xl transform hover:scale-110" style="color: var(--color-nav-text);">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if($settings->instagram && $settings->instagram != '#')
                        <a href="{{ $settings->instagram }}" target="_blank" rel="noopener noreferrer" class="social-icon transition text-xl transform hover:scale-110" style="color: var(--color-nav-text);">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Colonne 2: Liens rapides -->
            <div>
                <h4 class="text-lg font-semibold mb-4" style="color: var(--color-primary);">Liens rapides</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="footer-link transition block" style="color: var(--color-nav-text);">Accueil</a></li>
                    <li><a href="{{ route('annonces.index') }}" class="footer-link transition block" style="color: var(--color-nav-text);">Toutes les annonces</a></li>
                    <li><a href="{{ route('annonces.animaux.index') }}" class="footer-link transition block" style="color: var(--color-nav-text);">Animaux</a></li>
                    <li><a href="{{ route('annonces.aliments.index') }}" class="footer-link transition block" style="color: var(--color-nav-text);">Alimentation</a></li>
                    <li><a href="{{ route('annonces.accessoires.index') }}" class="footer-link transition block" style="color: var(--color-nav-text);">Accessoires</a></li>
                    <li><a href="{{ route('services-veterinaires.index') }}" class="footer-link transition block" style="color: var(--color-nav-text);">Vétérinaires</a></li>
                    <li><a href="{{ route('transporteurs.index') }}" class="footer-link transition block" style="color: var(--color-nav-text);">Transporteurs</a></li>
                </ul>
            </div>
            
            <!-- Colonne 3: Informations légales -->
            <div>
                <h4 class="text-lg font-semibold mb-4" style="color: var(--color-primary);">Informations</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('a-propos') }}" class="footer-link transition block" style="color: var(--color-nav-text);">À propos</a></li>
                    <li><a href="{{ route('contact.index') }}" class="footer-link transition block" style="color: var(--color-nav-text);">Contact</a></li>
                    <li><a href="{{ route('cgv') }}" class="footer-link transition block" style="color: var(--color-nav-text);">CGV</a></li>
                    <li><a href="{{ route('confidentialite') }}" class="footer-link transition block" style="color: var(--color-nav-text);">Politique de confidentialité</a></li>
                    <li><a href="{{ route('faq') }}" class="footer-link transition block" style="color: var(--color-nav-text);">FAQ</a></li>
                    <li><a href="{{ route('mentions-legales') }}" class="footer-link transition block" style="color: var(--color-nav-text);">Mentions légales</a></li>
                </ul>
            </div>
            
            <!-- Colonne 4: Contact -->
            <div>
                <h4 class="text-lg font-semibold mb-4" style="color: var(--color-primary);">Contact</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt mt-1" style="color: var(--color-primary);"></i>
                        <span style="color: var(--color-nav-text);">{{ $settings->bp }}, {{ $settings->departement }}, {{ $settings->commune }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone" style="color: var(--color-primary);"></i>
                        <span style="color: var(--color-nav-text);">{{ $settings->tel }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope" style="color: var(--color-primary);"></i>
                        <span style="color: var(--color-nav-text);">{{ $settings->mail }}</span>
                    </li>
                </ul>
                
                <!-- Horaires d'ouverture -->
                <div class="mt-6">
                    <h5 class="text-sm font-semibold mb-2" style="color: var(--color-primary);">Horaires d'ouverture</h5>
                    <ul class="space-y-1 text-sm">
                        <li style="color: var(--color-nav-text);"><i class="far fa-clock mr-2"></i> Lundi - Vendredi: 8h - 18h</li>
                        <li style="color: var(--color-nav-text);"><i class="far fa-clock mr-2"></i> Samedi: 9h - 13h</li>
                        <li style="color: var(--color-nav-text);"><i class="far fa-clock mr-2"></i> Dimanche: Fermé</li>
                    </ul>
                </div>
                
                <!-- Paiements acceptés -->
                <div class="mt-6">
                    <h5 class="text-sm font-semibold mb-2" style="color: var(--color-primary);">Paiements sécurisés</h5>
                    <div class="flex gap-3">
                        <i class="fab fa-cc-visa text-2xl transition transform hover:scale-110 payment-icon" style="color: var(--color-nav-text); cursor: pointer;"></i>
                        <i class="fab fa-cc-mastercard text-2xl transition transform hover:scale-110 payment-icon" style="color: var(--color-nav-text); cursor: pointer;"></i>
                        <i class="fab fa-cc-amex text-2xl transition transform hover:scale-110 payment-icon" style="color: var(--color-nav-text); cursor: pointer;"></i>
                        <i class="fab fa-cc-paypal text-2xl transition transform hover:scale-110 payment-icon" style="color: var(--color-nav-text); cursor: pointer;"></i>
                        <i class="fas fa-mobile-alt text-2xl transition transform hover:scale-110 payment-icon" style="color: var(--color-nav-text); cursor: pointer;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Section Fondateurs -->
        <div class="border-t mt-8 pt-8 text-center" style="border-color: var(--color-nav-border);">
            <p class="text-sm" style="color: var(--color-nav-text);">
                Plateforme développée par 
                <span style="color: var(--color-primary); font-weight: bold;">LASSISSOU Missigbèto Zakari Yaoo</span> & 
                <span style="color: var(--color-primary); font-weight: bold;">AGOGLODAN Antoine</span>
            </p>
        </div>
        
        <!-- Copyright -->
        <div class="border-t mt-4 pt-6 text-center text-sm" style="border-color: var(--color-nav-border); color: var(--color-nav-text);">
            <p>&copy; {{ date('Y') }} {{ $settings->nom_plateforme }}. Tous droits réservés.</p>
            <p class="mt-1 text-xs">Made with <i class="fas fa-heart" style="color: var(--color-primary);"></i> in Bénin</p>
        </div>
    </div>
</footer>

<script>
    // Gestion du bouton retour en haut
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('backToTop');
        
        if (backToTopButton) {
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.remove('hidden');
                    backToTopButton.style.display = 'flex';
                } else {
                    backToTopButton.classList.add('hidden');
                    backToTopButton.style.display = 'none';
                }
            });
            
            backToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            backToTopButton.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            backToTopButton.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        }
    });
</script>

<style>
    /* Animation de pulsation pour le bouton retour en haut */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(212, 175, 55, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(212, 175, 55, 0);
        }
    }
    
    #backToTop {
        animation: pulse 2s infinite;
    }
    
    #backToTop:hover {
        animation: none;
    }
    
    /* Transition fluide pour le footer */
    footer * {
        transition: all var(--transition-fast);
    }
    
    /* Liens du footer - comportement au survol */
    .footer-link {
        transition: all var(--transition-fast);
        position: relative;
        display: inline-block;
    }
    
    .footer-link:hover {
        color: var(--color-primary) !important;
        transform: translateX(4px);
    }
    
    /* Icônes sociales et paiements au survol */
    .social-icon:hover,
    .payment-icon:hover {
        color: var(--color-primary) !important;
        transform: scale(1.1);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        footer .grid {
            gap: 2rem;
        }
    }
</style>