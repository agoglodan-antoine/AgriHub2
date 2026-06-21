<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-calendar-plus text-4xl" style="color: var(--color-primary-light);"></i>
                    Prendre rendez-vous
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">
                    Vétérinaire : Dr. {{ $veterinaire->prenom }} {{ $veterinaire->nom }}
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
                        {{ strtoupper(substr($veterinaire->prenom ?? 'U', 0, 1) . substr($veterinaire->nom ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold" style="color: var(--color-nav-text);">
                            Dr. {{ $veterinaire->prenom }} {{ $veterinaire->nom }}
                        </h2>
                        @if($veterinaire->veterinaire && $veterinaire->veterinaire->specialites)
                            <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">
                                <i class="fas fa-graduation-cap mr-1" style="color: var(--color-primary);"></i>
                                {{ $veterinaire->veterinaire->specialites }}
                            </p>
                        @endif
                        <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">
                            <i class="fas fa-map-marker-alt mr-1" style="color: var(--color-primary);"></i>
                            {{ $veterinaire->veterinaire->zone_intervention ?? $veterinaire->ville ?? 'Bénin' }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('service.veterinaire.rendez-vous', $veterinaire->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                            <i class="fas fa-tag mr-1" style="color: var(--color-primary);"></i> Sujet du rendez-vous *
                        </label>
                        <input type="text" 
                               name="sujet" 
                               value="{{ old('sujet') }}"
                               required
                               placeholder="Ex: Consultation, Vaccination, Urgence..." 
                               class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                               style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                        @error('sujet')
                            <p class="text-xs mt-1 text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                <i class="fas fa-calendar mr-1" style="color: var(--color-primary);"></i> Date *
                            </label>
                            <input type="date" 
                                   name="date_prevue" 
                                   value="{{ old('date_prevue') }}"
                                   required
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                   style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                            @error('date_prevue')
                                <p class="text-xs mt-1 text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                                <i class="fas fa-clock mr-1" style="color: var(--color-primary);"></i> Heure *
                            </label>
                            <select name="heure_prevue" 
                                    required
                                    class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                                    style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                                <option value="">Sélectionner une heure</option>
                                <option value="08:00" {{ old('heure_prevue') == '08:00' ? 'selected' : '' }}>08:00</option>
                                <option value="08:30" {{ old('heure_prevue') == '08:30' ? 'selected' : '' }}>08:30</option>
                                <option value="09:00" {{ old('heure_prevue') == '09:00' ? 'selected' : '' }}>09:00</option>
                                <option value="09:30" {{ old('heure_prevue') == '09:30' ? 'selected' : '' }}>09:30</option>
                                <option value="10:00" {{ old('heure_prevue') == '10:00' ? 'selected' : '' }}>10:00</option>
                                <option value="10:30" {{ old('heure_prevue') == '10:30' ? 'selected' : '' }}>10:30</option>
                                <option value="11:00" {{ old('heure_prevue') == '11:00' ? 'selected' : '' }}>11:00</option>
                                <option value="11:30" {{ old('heure_prevue') == '11:30' ? 'selected' : '' }}>11:30</option>
                                <option value="14:00" {{ old('heure_prevue') == '14:00' ? 'selected' : '' }}>14:00</option>
                                <option value="14:30" {{ old('heure_prevue') == '14:30' ? 'selected' : '' }}>14:30</option>
                                <option value="15:00" {{ old('heure_prevue') == '15:00' ? 'selected' : '' }}>15:00</option>
                                <option value="15:30" {{ old('heure_prevue') == '15:30' ? 'selected' : '' }}>15:30</option>
                                <option value="16:00" {{ old('heure_prevue') == '16:00' ? 'selected' : '' }}>16:00</option>
                                <option value="16:30" {{ old('heure_prevue') == '16:30' ? 'selected' : '' }}>16:30</option>
                                <option value="17:00" {{ old('heure_prevue') == '17:00' ? 'selected' : '' }}>17:00</option>
                            </select>
                            @error('heure_prevue')
                                <p class="text-xs mt-1 text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                            <i class="fas fa-align-left mr-1" style="color: var(--color-primary);"></i> Description
                        </label>
                        <textarea name="description" 
                                  rows="4"
                                  placeholder="Décrivez brièvement le motif de votre visite..." 
                                  class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 transition-colors resize-none"
                                  style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs mt-1 text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-r-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Informations importantes :</strong>
                                </p>
                                <ul class="text-sm text-yellow-700 list-disc list-inside mt-1">
                                    <li>Veuillez arriver 10 minutes avant l'heure prévue</li>
                                    <li>En cas d'empêchement, annulez au moins 24h à l'avance</li>
                                    <li>Vous recevrez une confirmation par email</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('service.veterinaire.show', $veterinaire->id) }}" 
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
                            <i class="fas fa-paper-plane mr-2"></i> Demander le rendez-vous
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>