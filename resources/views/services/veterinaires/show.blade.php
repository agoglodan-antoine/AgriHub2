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
                <a href="{{ route('service.veterinaire.index') }}" class="hover:text-primary transition" style="color: var(--color-primary);">
                    Vétérinaires
                </a>
                <span>/</span>
                <span>Dr. {{ $veterinaire->prenom }} {{ $veterinaire->nom }}</span>
            </nav>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Colonne principale : Informations du vétérinaire -->
                <div class="lg:col-span-2">
                    <!-- Carte principale -->
                    <div class="rounded-xl shadow-md overflow-hidden mb-6" style="background-color: var(--color-bg-white);">
                        <!-- En-tête avec photo -->
                        <div class="relative h-48 flex items-center justify-center" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                            @php
                                $avatar = $veterinaire->avatar ?? null;
                            @endphp
                            
                            @if($avatar && file_exists(public_path($avatar)))
                                <img src="{{ asset($avatar) }}" 
                                     alt="Photo" 
                                     class="w-32 h-32 rounded-full object-cover border-4 shadow-xl"
                                     style="border-color: white;">
                            @else
                                <div class="w-32 h-32 rounded-full flex items-center justify-center border-4 shadow-xl"
                                     style="border-color: white; background-color: var(--color-bg-white);">
                                    <i class="fas fa-user-md text-5xl" style="color: var(--color-primary);"></i>
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
                                        Dr. {{ $veterinaire->prenom }} {{ $veterinaire->nom }}
                                    </h1>
                                    <p class="text-sm mt-1" style="color: var(--color-nav-text); opacity: 0.6;">
                                        <i class="fas fa-graduation-cap mr-1" style="color: var(--color-primary);"></i>
                                        {{ $veterinaire->veterinaire->specialites ?? 'Vétérinaire généraliste' }}
                                    </p>
                                    @if($veterinaire->veterinaire && $veterinaire->veterinaire->numero_ordre)
                                        <p class="text-xs mt-1" style="color: var(--color-nav-text); opacity: 0.5;">
                                            <i class="fas fa-id-card mr-1"></i>
                                            N° Ordre : {{ $veterinaire->veterinaire->numero_ordre }}
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
                                        <span class="text-sm font-semibold ml-1" style="color: var(--color-nav-text);">4.5</span>
                                    </div>
                                    <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">(12 avis)</p>
                                </div>
                            </div>

                            <!-- Zone d'intervention -->
                            @if($veterinaire->veterinaire && $veterinaire->veterinaire->zone_intervention)
                                <div class="flex items-center gap-2 mb-4 p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                    <i class="fas fa-map-marker-alt" style="color: var(--color-primary);"></i>
                                    <span class="text-sm" style="color: var(--color-nav-text);">
                                        <strong>Zone d'intervention :</strong> 
                                        {{ $veterinaire->veterinaire->zone_intervention }}
                                    </span>
                                </div>
                            @endif

                            <!-- Coordonnées -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                @if($veterinaire->email)
                                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                        <i class="fas fa-envelope" style="color: var(--color-primary);"></i>
                                        <div>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Email</p>
                                            <p class="text-sm" style="color: var(--color-nav-text);">{{ $veterinaire->email }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($veterinaire->telephone)
                                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                        <i class="fas fa-phone" style="color: var(--color-primary);"></i>
                                        <div>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Téléphone</p>
                                            <p class="text-sm" style="color: var(--color-nav-text);">{{ $veterinaire->telephone }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($veterinaire->ville)
                                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                        <i class="fas fa-city" style="color: var(--color-primary);"></i>
                                        <div>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Ville</p>
                                            <p class="text-sm" style="color: var(--color-nav-text);">{{ $veterinaire->ville }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($veterinaire->commune)
                                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-bg-gray);">
                                        <i class="fas fa-map-pin" style="color: var(--color-primary);"></i>
                                        <div>
                                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Commune</p>
                                            <p class="text-sm" style="color: var(--color-nav-text);">{{ $veterinaire->commune }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Boutons d'action -->
                            <div class="flex flex-wrap gap-3">
                                @auth
                                    <a href="{{ route('service.veterinaire.rendez-vous.form', $veterinaire->id) }}" 
                                       class="flex-1 min-w-[200px] py-3 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center justify-center gap-2"
                                       style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                        <i class="fas fa-calendar-plus"></i> Prendre rendez-vous
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
                                        <i class="fas fa-sign-in-alt"></i> Connectez-vous pour prendre rendez-vous
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Avis -->
                    <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-star mr-2" style="color: var(--color-primary);"></i> Avis des clients
                        </h3>
                        <div class="text-center py-8">
                            <i class="fas fa-comment text-4xl mb-3" style="color: var(--color-primary-light);"></i>
                            <p style="color: var(--color-nav-text); opacity: 0.6;">Aucun avis pour le moment</p>
                            <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.4;">Soyez le premier à donner votre avis</p>
                        </div>
                    </div>
                </div>

                <!-- Colonne latérale -->
                <div class="lg:col-span-1">
                    <!-- Horaires de consultation -->
                    <div class="rounded-xl shadow-md p-6 mb-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-clock mr-2" style="color: var(--color-primary);"></i> Horaires de consultation
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--color-nav-text); opacity: 0.6;">Lundi - Vendredi</span>
                                <span style="color: var(--color-nav-text);">08:00 - 18:00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--color-nav-text); opacity: 0.6;">Samedi</span>
                                <span style="color: var(--color-nav-text);">08:00 - 13:00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--color-nav-text); opacity: 0.6;">Dimanche</span>
                                <span style="color: var(--color-nav-text);" class="text-red-500">Fermé</span>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t" style="border-color: var(--color-nav-border);">
                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">
                                <i class="fas fa-info-circle mr-1"></i>
                                Urgences disponibles 24h/24 sur appel
                            </p>
                        </div>
                    </div>

                    <!-- Services proposés -->
                    <div class="rounded-xl shadow-md p-6 mb-6" style="background-color: var(--color-bg-white);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                            <i class="fas fa-list mr-2" style="color: var(--color-primary);"></i> Services proposés
                        </h3>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-sm" style="color: var(--color-nav-text);">
                                <i class="fas fa-check-circle text-xs" style="color: var(--color-primary);"></i>
                                Consultations générales
                            </div>
                            <div class="flex items-center gap-2 text-sm" style="color: var(--color-nav-text);">
                                <i class="fas fa-check-circle text-xs" style="color: var(--color-primary);"></i>
                                Vaccinations
                            </div>
                            <div class="flex items-center gap-2 text-sm" style="color: var(--color-nav-text);">
                                <i class="fas fa-check-circle text-xs" style="color: var(--color-primary);"></i>
                                Chirurgies
                            </div>
                            <div class="flex items-center gap-2 text-sm" style="color: var(--color-nav-text);">
                                <i class="fas fa-check-circle text-xs" style="color: var(--color-primary);"></i>
                                Analyses de laboratoire
                            </div>
                            <div class="flex items-center gap-2 text-sm" style="color: var(--color-nav-text);">
                                <i class="fas fa-check-circle text-xs" style="color: var(--color-primary);"></i>
                                Soins d'urgence
                            </div>
                        </div>
                    </div>

                    <!-- Autres vétérinaires -->
                    @if(isset($autresVeterinaires) && $autresVeterinaires->count() > 0)
                        <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                            <h3 class="text-lg font-semibold mb-4" style="color: var(--color-nav-text);">
                                <i class="fas fa-users mr-2" style="color: var(--color-primary);"></i> Autres vétérinaires
                            </h3>
                            <div class="space-y-3">
                                @foreach($autresVeterinaires as $autre)
                                    <a href="{{ route('service.veterinaire.show', $autre->id) }}" 
                                       class="flex items-center gap-3 p-2 rounded-lg transition hover:shadow-md"
                                       style="background-color: var(--color-bg-gray);">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                             style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                            {{ strtoupper(substr($autre->prenom ?? 'U', 0, 1) . substr($autre->nom ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold truncate" style="color: var(--color-nav-text);">
                                                Dr. {{ $autre->prenom }} {{ $autre->nom }}
                                            </p>
                                            <p class="text-xs truncate" style="color: var(--color-nav-text); opacity: 0.6;">
                                                {{ $autre->ville ?? $autre->commune ?? 'Bénin' }}
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