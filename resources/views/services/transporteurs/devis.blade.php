<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-file-invoice text-4xl" style="color: var(--color-primary-light);"></i>
                    Demander un devis
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">
                    Transporteur : {{ $transporteur->prenom }} {{ $transporteur->nom }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl shadow-md p-6" style="background-color: var(--color-bg-white);">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-xl font-bold flex-shrink-0" 
                         style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                        {{ strtoupper(substr($transporteur->prenom ?? 'U', 0, 1) . substr($transporteur->nom ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold" style="color: var(--color-nav-text);">
                            {{ $transporteur->prenom }} {{ $transporteur->nom }}
                        </h2>
                        <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">
                            <i class="fas fa-truck mr-1" style="color: var(--color-primary);"></i>
                            {{ $transporteur->transporteur->type_vehicule ?? 'Transporteur' }}
                            @if($transporteur->transporteur->capacite_transport)
                                - {{ $transporteur->transporteur->capacite_transport }} kg
                            @endif
                        </p>
                        <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">
                            <i class="fas fa-map-marker-alt mr-1" style="color: var(--color-primary);"></i>
                            {{ $transporteur->transporteur->zone_intervention ?? $transporteur->ville ?? 'Bénin' }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('service.transporteur.devis', $transporteur->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                            <i class="fas fa-map-pin mr-1" style="color: var(--color-primary);"></i> Lieu de départ *
                        </label>
                        <input type="text" 
                               name="lieu_depart" 
                               value="{{ old('lieu_depart') }}"
                               required
                               placeholder="Ex: Cotonou, Abomey-Calavi..." 
                               class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                               style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                        @error('lieu_depart')
                            <p class="text-xs mt-1 text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                            <i class="fas fa-flag-checkered mr-1" style="color: var(--color-primary);"></i> Lieu d'arrivée *
                        </label>
                        <input type="text" 
                               name="lieu_arrivee" 
                               value="{{ old('lieu_arrivee') }}"
                               required
                               placeholder="Ex: Parakou, Porto-Novo..." 
                               class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                               style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                        @error('lieu_arrivee')
                            <p class="text-xs mt-1 text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                <i class="fas fa-weight-hanging mr-1" style="color: var(--color-primary);"></i> Poids (kg)
                            </label>
                            <input type="number" 
                                   name="poids" 
                                   value="{{ old('poids') }}"
                                   placeholder="Ex: 500" 
                                   min="0"
                                   class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                   style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                            @error('poids')
                                <p class="text-xs mt-1 text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                <i class="fas fa-calendar mr-1" style="color: var(--color-primary);"></i> Date souhaitée
                            </label>
                            <input type="date" 
                                   name="date_souhaitee" 
                                   value="{{ old('date_souhaitee') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                   style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                            @error('date_souhaitee')
                                <p class="text-xs mt-1 text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                            <i class="fas fa-align-left mr-1" style="color: var(--color-primary);"></i> Description du transport *
                        </label>
                        <textarea name="description" 
                                  rows="4"
                                  required
                                  placeholder="Décrivez ce que vous souhaitez transporter (animaux, produits agricoles, etc.)..." 
                                  class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors resize-none"
                                  style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs mt-1 text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('service.transporteur.show', $transporteur->id) }}" 
                           class="flex-1 text-center py-3 rounded-lg font-semibold transition"
                           style="background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                           onmouseover="this.style.backgroundColor='var(--color-nav-border)'"
                           onmouseout="this.style.backgroundColor='var(--color-bg-gray)'">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                        <button type="submit" 
                                class="flex-1 py-3 rounded-lg font-semibold transition hover:scale-105 text-white"
                                style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                                onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'"
                                onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'">
                            <i class="fas fa-paper-plane mr-2"></i> Envoyer la demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>