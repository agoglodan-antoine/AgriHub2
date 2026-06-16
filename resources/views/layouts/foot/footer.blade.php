@php
    $parametres = App\Models\Parametre::first();
    $settings = $parametres ?? (object)[
        'nom_plateforme' => 'AgriHub',
        'logo' => null,
        'mail' => 'contact@agrihub.sn',
        'tel' => '+221 33 123 45 67',
        'bp' => 'BP 12345 Dakar',
        'departement' => 'Dakar',
        'commune' => 'Dakar Plateau',
        'description' => 'La première plateforme agricole connectée du Sénégal',
        'facebook' => '#',
        'whatsapp' => '#',
        'linkedin' => '#',
        'twitter' => '#',
        'instagram' => '#'
    ];
@endphp

<footer class="bg-gray-900 text-gray-300">
    <!-- Newsletter -->
    <div class="bg-gradient-to-r from-green-700 to-green-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h3 class="text-2xl font-bold mb-4">Restez informé</h3>
                <p class="mb-6">Recevez nos actualités et offres spéciales</p>
                <form action="{{ url('/newsletter') }}" method="POST" class="max-w-md mx-auto flex gap-3">
                    @csrf
                    <input type="email" name="email" placeholder="Votre adresse email" 
                           class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-gold" required>
                    <button type="submit" class="bg-gold text-gray-900 px-6 py-3 rounded-lg font-semibold hover:bg-gold-dark transition">
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
                    @if($settings->logo && file_exists(public_path('storage/' . $settings->logo)))
                        <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="h-10 w-auto">
                    @else
                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-seedling text-white"></i>
                        </div>
                    @endif
                    <h3 class="text-xl font-bold text-white">{{ $settings->nom_plateforme }}</h3>
                </div>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    {{ $settings->description }}
                </p>
                <div class="flex space-x-4">
                    @if($settings->facebook && $settings->facebook != '#')
                        <a href="{{ $settings->facebook }}" class="text-gray-400 hover:text-gold transition text-xl">
                            <i class="fab fa-facebook"></i>
                        </a>
                    @endif
                    @if($settings->whatsapp && $settings->whatsapp != '#')
                        <a href="{{ $settings->whatsapp }}" class="text-gray-400 hover:text-gold transition text-xl">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif
                    @if($settings->linkedin && $settings->linkedin != '#')
                        <a href="{{ $settings->linkedin }}" class="text-gray-400 hover:text-gold transition text-xl">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    @endif
                    @if($settings->twitter && $settings->twitter != '#')
                        <a href="{{ $settings->twitter }}" class="text-gray-400 hover:text-gold transition text-xl">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if($settings->instagram && $settings->instagram != '#')
                        <a href="{{ $settings->instagram }}" class="text-gray-400 hover:text-gold transition text-xl">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Colonne 2: Liens rapides -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Liens rapides</h4>
                <ul class="space-y-2">
                    <li><a href="{{ url('/') }}" class="hover:text-gold transition">Accueil</a></li>
                    <li><a href="{{ url('/produits') }}" class="hover:text-gold transition">Produits</a></li>
                    <li><a href="{{ url('/animaux') }}" class="hover:text-gold transition">Animaux</a></li>
                    <li><a href="{{ url('/services') }}" class="hover:text-gold transition">Services vétérinaires</a></li>
                    <li><a href="{{ url('/transporteurs') }}" class="hover:text-gold transition">Transporteurs</a></li>
                    <li><a href="{{ url('/blog') }}" class="hover:text-gold transition">Blog & Conseils</a></li>
                </ul>
            </div>
            
            <!-- Colonne 3: Informations légales -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Informations</h4>
                <ul class="space-y-2">
                    <li><a href="{{ url('/a-propos') }}" class="hover:text-gold transition">À propos</a></li>
                    <li><a href="{{ url('/contact') }}" class="hover:text-gold transition">Contact</a></li>
                    <li><a href="{{ url('/cgv') }}" class="hover:text-gold transition">CGV</a></li>
                    <li><a href="{{ url('/confidentialite') }}" class="hover:text-gold transition">Politique de confidentialité</a></li>
                    <li><a href="{{ url('/faq') }}" class="hover:text-gold transition">FAQ</a></li>
                    <li><a href="{{ url('/mentions-legales') }}" class="hover:text-gold transition">Mentions légales</a></li>
                </ul>
            </div>
            
            <!-- Colonne 4: Contact -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Contact</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-gold mt-1"></i>
                        <span>{{ $settings->bp }}, {{ $settings->departement }}, {{ $settings->commune }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone text-gold"></i>
                        <span>{{ $settings->tel }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-gold"></i>
                        <span>{{ $settings->mail }}</span>
                    </li>
                </ul>
                
                <!-- Paiements acceptés -->
                <div class="mt-6">
                    <h5 class="text-sm font-semibold text-white mb-2">Paiements sécurisés</h5>
                    <div class="flex gap-3">
                        <i class="fab fa-cc-visa text-2xl text-gray-400"></i>
                        <i class="fab fa-cc-mastercard text-2xl text-gray-400"></i>
                        <i class="fab fa-cc-amex text-2xl text-gray-400"></i>
                        <i class="fas fa-mobile-alt text-2xl text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} {{ $settings->nom_plateforme }}. Tous droits réservés.</p>
            <p class="mt-2">Made with <i class="fas fa-heart text-red-500"></i> pour l'agriculture sénégalaise</p>
        </div>
    </div>
</footer>