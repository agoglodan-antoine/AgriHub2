<x-app-layout>
    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Fil d'Ariane -->
            <nav class="flex items-center gap-2 text-sm mb-6" style="color: var(--color-nav-text);">
                <a href="{{ route('home') }}" class="hover:text-primary transition" style="color: var(--color-primary);">
                    <i class="fas fa-home"></i>
                </a>
                <span>/</span>
                <a href="{{ route('service.index') }}" class="hover:text-primary transition" style="color: var(--color-primary);">
                    Services
                </a>
                <span>/</span>
                <a href="{{ route('service.transporteur.index') }}" class="hover:text-primary transition" style="color: var(--color-primary);">
                    Transporteurs
                </a>
                <span>/</span>
                <span>{{ $transporteur->prenom }} {{ $transporteur->nom }}</span>
            </nav>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Colonne principale : Informations du transporteur -->
                <div class="lg:col-span-2">
                    <!-- Carte principale -->
                    <div class="rounded-xl shadow-md overflow-hidden mb-6" style="background-color: var(--color-bg-white);">
                        <!-- En-tête avec photo -->
                        <div class="relative h-48 flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-secondary), var(--color-secondary-dark));">
                            @php
                                $avatar = $transporteur->avatar ?? null;
                            @endphp
                            
                            @if($avatar && file_exists(public_path($avatar)))
                                <img src="{{ asset($avatar) }}" 
                                     alt="Photo" 
                                     class="w-32 h-32 rounded-full object-cover border-4 shadow-xl"
                                     style="border-color: white;">
                            @else
                                <div class="w-32 h-32 rounded-full flex items-center justify-center border-4 shadow-xl"
                                     style="border-color: white; background-color: var(--color-bg-white);">
                                    <i class="fas fa-truck text-5xl" style="color: var(--color-primary);"></i>
                                </div>
                            @endif
                            
                            <!-- Badge de statut -->
                            <div class="absolute bottom-4 right-4 px-4 py-2 rounded-lg text-white font-semibold text-sm backdrop-blur-md"
                                 style="background: rgba(0,0,0,0.5);">
                                <i class="fas fa-circle text-green-400 mr-2"></i>
                                Disponible
                            </div>
                        </div>

                        <!-- Informations -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h1 class="text-2xl font-bold" style="color: var(--color-nav-text);">
                                        {{ $transporteur->prenom }} {{ $transporteur->nom }}
                                    </h1>
                                    <p class="text-sm mt-1" style="color: var(--color-nav-text); opacity: 0.6;">
                                        <i class="fas fa-truck mr-1" style="color: var(--color-primary);"></i>
                                        {{ $transporteur->transporteur->type_vehicule ?? 'Transporteur' }}
                                    </p>
                                    @if($transporteur->transporteur && $transporteur->transporteur->licence_transport)
                                        <p class="text-xs mt-1" style="color: var(--color-nav-text); opacity: 0.5;">
                                            <i class="fas fa-id-card mr-1"></i>
                                            Licence : {{ $transporteur->transporteur->licence_transport }}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-star text-sm" style="color: var(--color-primary);"></i>
                                        <i class="fas fa-star text-sm" style="color: var(--color-primary);"></i>
                                        <i class="fas fa-star text-sm" style="color: var(--color-primary);"></i>
                                        <i class="fas fa-star text-sm" style="color: var(--color-primary);"></i>
                                        <i class="fas fa-star-half-alt text-sm" style="color: var(--color-primary);"></i>
                                        <span class="text-sm font-semibold ml-1" style="color: var(--color-nav-text);">4.7</span>
                                    </div>
                                    <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">(8 avis)</p>
                                </div>
                            </div>

                            <!-- Détails du transport -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                                <div class="p-3 rounded-lg text-center" style="background-color: var(--color-bg-gray);">
                                    <i class="fas fa-truck text-xl mb-1" style="color: var(--color-primary);"></i>
                                    <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Type de véhicule</p>
                                    <p class="text-sm font-semibold" style="color: var(--color-nav-text);">
                                        {{ $transporteur->transporteur->type_vehicule ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="p-3 rounded-lg text-center" style="background-color: var(--color-bg-gray);">
                                    <i class="fas fa-weight-hanging text-xl mb-1" style="color: var(--color-primary);"></i>
                                    <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Capacité</p>
                                    <p class="text-sm font-semibold" style="color: var(--color-nav-text);">
                                        {{ $transporteur->transporteur->capacite_transport ?? 'N/A' }} kg
                                    </p>
                                </div>
                                <div class="p-3 rounded-lg text-center" style="background-color: var(--color-bg-gray);">
                                    <i class="fas fa-map-marker-alt text-xl mb-1" style="color: var(--color-primary);"></i>
                                    <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Zone</p>
                                    <p class="text-sm font-semibold" style="color: var(--color-nav-text);">
                                        {{ $transporteur->transporteur->zone_intervention ?? $transporteur->ville ?? 'Bénin' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Coordonnées -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                @if($transporteur->email)
                                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                        <i class="fas fa-envelope" style="color: var(--color-primary);"></i>
                                        <div>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Email</p>
                                            <p class="text-sm" style="color: var(--color-nav-text);">{{ $transporteur->email }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($transporteur->telephone)
                                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                        <i class="fas fa-phone" style="color: var(--color-primary);"></i>
                                        <div>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Téléphone</p>
                                            <p class="text-sm" style="color: var(--color-nav-text);">{{ $transporteur->telephone }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($transporteur->ville)
                                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                        <i class="fas fa-city" style="color: var(--color-primary);"></i>
                                        <div>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Ville</p>
                                            <p class="text-sm" style="color: var(--color-nav-text);">{{ $transporteur->ville }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($transporteur->commune)
                                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                        <i class="fas fa-map-pin" style="color: var(--color-primary);"></i>
                                        <div>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Commune</p>
                                            <p class="text-sm" style="color: var(--color-nav-text);">{{ $transporteur->commune }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Boutons d'action -->
                            <div class="flex flex-wrap gap-3">
                                @auth
                                    <a href="{{ route('service.transporteur.devis.form', $transporteur->id) }}" 
                                       class="flex-1 min-w-[200px] py-3 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center justify-center gap-2"
                                       style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                        <i class="fas fa-file-invoice"></i> Demander un devis
                                    </a>
                                    <a href="#" 
                                       class="flex-1 min-w-[200px] py-3 rounded-lg font-semibold transition hover:scale-105 flex items-center justify-center gap-2"
                                       style="background-color: var(--color-secondary-light); color: var(--color-primary-dark);"
                                       onmouseover="this.style.backgroundColor='var(--color-secondary)'"
                                       onmouseout="this.style.backgroundColor='var(--color-secondary-light)'">
                                        <i class="fas fa-comment"></i> Contacter
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="flex-1 min-w-[200px] py-3 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center justify-center gap-2"
                                       style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                        <i class="fas fa-sign-in-alt"></i> Connectez-vous pour demander un devis
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Description / Informations complémentaires -->
                    <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-info-circle mr-2" style="color: var(--color-primary);"></i> Informations
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-semibold mb-1" style="color: var(--color-nav-text);">
                                    <i class="fas fa-tasks mr-1" style="color: var(--color-primary);"></i> Services proposés
                                </h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="flex items-center gap-2 text-sm" style="color: var(--color-nav-text);">
                                        <i class="fas fa-check-circle text-xs" style="color: var(--color-primary);"></i>
                                        Transport d'animaux
                                    </div>
                                    <div class="flex items-center gap-2 text-sm" style="color: var(--color-nav-text);">
                                        <i class="fas fa-check-circle text-xs" style="color: var(--color-primary);"></i>
                                        Transport de produits agricoles
                                    </div>
                                    <div class="flex items-center gap-2 text-sm" style="color: var(--color-nav-text);">
                                        <i class="fas fa-check-circle text-xs" style="color: var(--color-primary);"></i>
                                        Livraison d'aliments
                                    </div>
                                    <div class="flex items-center gap-2 text-sm" style="color: var(--color-nav-text);">
                                        <i class="fas fa-check-circle text-xs" style="color: var(--color-primary);"></i>
                                        Transport de matériel
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 border-t" style="border-color: var(--color-nav-border);">
                                <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">
                                    <i class="fas fa-info-circle mr-1" style="color: var(--color-primary);"></i>
                                    Service disponible sur toute l'étendue du territoire béninois
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne latérale -->
                <div class="lg:col-span-1">
                    <!-- Tarifs -->
                    <div class="rounded-xl shadow-md p-6 mb-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-coins mr-2" style="color: var(--color-primary);"></i> Tarifs indicatifs
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-2 rounded-lg" style="background-color: var(--color-bg-gray);">
                                <span class="text-sm" style="color: var(--color-nav-text);">Transport local</span>
                                <span class="text-sm font-semibold" style="color: var(--color-primary);">Sur devis</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded-lg" style="background-color: var(--color-bg-gray);">
                                <span class="text-sm" style="color: var(--color-nav-text);">Transport national</span>
                                <span class="text-sm font-semibold" style="color: var(--color-primary);">Sur devis</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded-lg" style="background-color: var(--color-bg-gray);">
                                <span class="text-sm" style="color: var(--color-nav-text);">Frais de déplacement</span>
                                <span class="text-sm font-semibold" style="color: var(--color-primary);">Gratuit</span>
                            </div>
                            <div class="text-center pt-2">
                                <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Demandez un devis pour un tarif personnalisé
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Disponibilité -->
                    <div class="rounded-xl shadow-md p-6 mb-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-clock mr-2" style="color: var(--color-primary);"></i> Disponibilité
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--color-nav-text); opacity: 0.6;">Lundi - Vendredi</span>
                                <span style="color: var(--color-nav-text);">07:00 - 19:00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--color-nav-text); opacity: 0.6;">Samedi</span>
                                <span style="color: var(--color-nav-text);">07:00 - 14:00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--color-nav-text); opacity: 0.6;">Dimanche</span>
                                <span style="color: var(--color-nav-text);" class="text-red-500">Fermé</span>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t" style="border-color: var(--color-nav-border);">
                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">
                                <i class="fas fa-info-circle mr-1"></i>
                                Service d'urgence disponible sur appel
                            </p>
                        </div>
                    </div>

                    <!-- Autres transporteurs -->
                    @if(isset($autresTransporteurs) && $autresTransporteurs->count() > 0)
                        <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                            <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                                <i class="fas fa-users mr-2" style="color: var(--color-primary);"></i> Autres transporteurs
                            </h3>
                            <div class="space-y-3">
                                @foreach($autresTransporteurs as $autre)
                                    <a href="{{ route('service.transporteur.show', $autre->id) }}" 
                                       class="flex items-center gap-3 p-2 rounded-lg transition hover:shadow-md"
                                       style="background-color: var(--color-bg-gray);">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                             style="background: linear-gradient(135deg, var(--color-secondary), var(--color-secondary-dark));">
                                            {{ strtoupper(substr($autre->prenom ?? 'U', 0, 1) . substr($autre->nom ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold truncate" style="color: var(--color-nav-text);">
                                                {{ $autre->prenom }} {{ $autre->nom }}
                                            </p>
                                            <p class="text-xs truncate" style="color: var(--color-nav-text); opacity: 0.6;">
                                                {{ $autre->transporteur->type_vehicule ?? 'Transporteur' }}
                                                @if($autre->transporteur && $autre->transporteur->capacite_transport)
                                                    - {{ $autre->transporteur->capacite_transport }} kg
                                                @endif
                                            </p>
                                        </div>
                                        <i class="fas fa-chevron-right text-xs" style="color: var(--color-nav-text); opacity: 0.3;"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>