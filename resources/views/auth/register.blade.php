<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3" 
             style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <i class="fas fa-user-plus text-white text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold" style="color: var(--color-nav-text);">
            {{ __('Créer un compte') }}
        </h2>
        <p class="text-sm mt-1" style="color: var(--color-nav-text); opacity: 0.6;">
            {{ __('Rejoignez la plateforme agricole connectée') }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Prénom et Nom -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="prenom" :value="__('Prénom')" style="color: var(--color-nav-text);" />
                <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" :value="old('prenom')" required autofocus autocomplete="given-name"
                    placeholder="Jean"
                    style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
                <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="nom" :value="__('Nom')" style="color: var(--color-nav-text);" />
                <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom')" required autocomplete="family-name"
                    placeholder="Dupont"
                    style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Adresse email')" style="color: var(--color-nav-text);" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username"
                placeholder="exemple@email.com"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Téléphone -->
        <div class="mt-4">
            <x-input-label for="telephone" :value="__('Téléphone')" style="color: var(--color-nav-text);" />
            <x-text-input id="telephone" class="block mt-1 w-full" type="tel" name="telephone" :value="old('telephone')" autocomplete="tel"
                placeholder="+229 97 00 00 00"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
        </div>

        <!-- Département -->
        <div class="mt-4">
            <x-input-label for="departement" :value="__('Département')" style="color: var(--color-nav-text);" />
            <select id="departement" name="departement" 
                    class="block mt-1 w-full rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                    style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                    required>
                <option value="">{{ __('Sélectionnez votre département') }}</option>
                @foreach($departements as $key => $departement)
                    <option value="{{ $key }}" {{ old('departement') == $key ? 'selected' : '' }}>
                        {{ $departement }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('departement')" class="mt-2" />
        </div>

        <!-- Commune -->
        <div class="mt-4">
            <x-input-label for="commune" :value="__('Commune')" style="color: var(--color-nav-text);" />
            <select id="commune" name="commune" 
                    class="block mt-1 w-full rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                    style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                    required>
                <option value="">{{ __('Sélectionnez d\'abord un département') }}</option>
            </select>
            <x-input-error :messages="$errors->get('commune')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" style="color: var(--color-nav-text);" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="••••••••"
                            style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="text-xs mt-1" style="color: var(--color-nav-text); opacity: 0.5;">
                {{ __('Minimum 8 caractères') }}
            </p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" style="color: var(--color-nav-text);" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••"
                            style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Type d'utilisateur -->
        <div class="mt-4">
            <x-input-label for="id_type_user" :value="__('Je suis')" style="color: var(--color-nav-text);" />
            <select id="id_type_user" name="id_type_user" 
                    class="block mt-1 w-full rounded-lg border focus:outline-none focus:ring-2 transition-colors"
                    style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);">
                <option value="">{{ __('Sélectionnez votre profil') }}</option>
                @foreach($typesUsers as $type)
                    <option value="{{ $type->id }}" {{ old('id_type_user') == $type->id ? 'selected' : '' }}>
                        {{ $type->label }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('id_type_user')" class="mt-2" />
        </div>

        <!-- Conditions générales -->
        <div class="block mt-4">
            <label for="terms" class="inline-flex items-start cursor-pointer">
                <input id="terms" type="checkbox" class="mt-0.5 rounded shadow-sm focus:ring-2 transition cursor-pointer" 
                       style="border-color: var(--color-nav-border); accent-color: var(--color-primary);" 
                       name="terms" required>
                <span class="ms-2 text-sm" style="color: var(--color-nav-text); opacity: 0.8;">
                    {{ __("J'accepte les") }}
                    <a href="{{ route('cgv') }}" class="font-semibold transition" 
                       style="color: var(--color-primary);"
                       onmouseover="this.style.color='var(--color-primary-dark)'"
                       onmouseout="this.style.color='var(--color-primary)'">
                        {{ __('Conditions Générales') }}
                    </a>
                    {{ __('et la') }}
                    <a href="{{ route('confidentialite') }}" class="font-semibold transition"
                       style="color: var(--color-primary);"
                       onmouseover="this.style.color='var(--color-primary-dark)'"
                       onmouseout="this.style.color='var(--color-primary)'">
                        {{ __('Politique de confidentialité') }}
                    </a>
                </span>
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        </div>

        <!-- Boutons -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-6">
            <a href="{{ route('login') }}" 
               class="text-sm transition inline-flex items-center gap-1 order-2 sm:order-1"
               style="color: var(--color-nav-text); opacity: 0.6;"
               onmouseover="this.style.opacity='1'; this.style.color='var(--color-primary)'"
               onmouseout="this.style.opacity='0.6'; this.style.color='var(--color-nav-text)'">
                <i class="fas fa-arrow-left"></i>
                {{ __('Déjà inscrit ?') }}
            </a>

            <button type="submit" 
                    class="w-full sm:w-auto px-8 py-3.5 rounded-lg font-bold text-white flex items-center justify-center gap-2 transition hover:scale-[1.02] text-base"
                    style="background: linear-gradient(135deg, #1a1a2e, #16213e); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4); border: 2px solid var(--color-primary);">
                <i class="fas fa-user-plus" style="color: var(--color-primary);"></i>
                <span style="color: #ffffff; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">{{ __("S'inscrire") }}</span>
            </button>
        </div>
    </form>

    <!-- Lien vers la page d'accueil -->
    <div class="mt-6 text-center">
        <a href="{{ route('home') }}" 
           class="text-xs transition inline-flex items-center gap-1"
           style="color: var(--color-nav-text); opacity: 0.4;"
           onmouseover="this.style.opacity='0.8'"
           onmouseout="this.style.opacity='0.4'">
            <i class="fas fa-home"></i>
            {{ __('Retour à l\'accueil') }}
        </a>
    </div>

    <!-- Script pour les communes dépendantes -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departementSelect = document.getElementById('departement');
            const communeSelect = document.getElementById('commune');
            const communesData = @json($communes);

            // Fonction pour mettre à jour les communes
            function updateCommunes() {
                const selectedDepartement = departementSelect.value;
                
                // Vider le select des communes
                communeSelect.innerHTML = '<option value="">{{ __("Sélectionnez votre commune") }}</option>';
                
                if (selectedDepartement && communesData[selectedDepartement]) {
                    // Ajouter les communes du département sélectionné
                    communesData[selectedDepartement].forEach(function(commune) {
                        const option = document.createElement('option');
                        option.value = commune;
                        option.textContent = commune;
                        communeSelect.appendChild(option);
                    });
                    
                    // Sélectionner la commune si elle existe dans les anciennes valeurs
                    const oldCommune = '{{ old('commune') }}';
                    if (oldCommune && communesData[selectedDepartement].includes(oldCommune)) {
                        communeSelect.value = oldCommune;
                    }
                }
            }

            // Écouter le changement de département
            departementSelect.addEventListener('change', updateCommunes);

            // Initialiser les communes si un département est déjà sélectionné
            if (departementSelect.value) {
                updateCommunes();
            }
        });
    </script>
</x-guest-layout>