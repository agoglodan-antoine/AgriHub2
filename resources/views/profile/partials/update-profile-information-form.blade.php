<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Prénom -->
        <div>
            <x-input-label for="prenom" :value="__('Prénom')" style="color: var(--color-nav-text);" />
            <x-text-input id="prenom" name="prenom" type="text" class="mt-1 block w-full" 
                :value="old('prenom', $user->prenom)" required autofocus autocomplete="given-name"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error class="mt-2" :messages="$errors->get('prenom')" />
        </div>

        <!-- Nom -->
        <div>
            <x-input-label for="nom" :value="__('Nom')" style="color: var(--color-nav-text);" />
            <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" 
                :value="old('nom', $user->nom)" required autocomplete="family-name"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error class="mt-2" :messages="$errors->get('nom')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" style="color: var(--color-nav-text);" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                :value="old('email', $user->email)" required autocomplete="username"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 rounded-lg" style="background-color: #FEF3C7;">
                    <p class="text-sm" style="color: #92400E;">
                        {{ __('Votre adresse email n\'est pas vérifiée.') }}

                        <button form="send-verification" class="underline text-sm font-semibold transition" 
                                style="color: var(--color-primary);"
                                onmouseover="this.style.color='var(--color-primary-dark)'"
                                onmouseout="this.style.color='var(--color-primary)'">
                            {{ __('Renvoyer le lien de vérification') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm" style="color: #16A34A;">
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Téléphone -->
        <div>
            <x-input-label for="telephone" :value="__('Téléphone')" style="color: var(--color-nav-text);" />
            <x-text-input id="telephone" name="telephone" type="tel" class="mt-1 block w-full" 
                :value="old('telephone', $user->telephone)" autocomplete="tel"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error class="mt-2" :messages="$errors->get('telephone')" />
        </div>

        <!-- Ville -->
        <div>
            <x-input-label for="ville" :value="__('Ville')" style="color: var(--color-nav-text);" />
            <x-text-input id="ville" name="ville" type="text" class="mt-1 block w-full" 
                :value="old('ville', $user->ville)" autocomplete="address-level2"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error class="mt-2" :messages="$errors->get('ville')" />
        </div>

        <!-- Commune -->
        <div>
            <x-input-label for="commune" :value="__('Commune')" style="color: var(--color-nav-text);" />
            <x-text-input id="commune" name="commune" type="text" class="mt-1 block w-full" 
                :value="old('commune', $user->commune)" autocomplete="address-level3"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error class="mt-2" :messages="$errors->get('commune')" />
        </div>

        <!-- Adresse -->
        <div>
            <x-input-label for="adresse" :value="__('Adresse')" style="color: var(--color-nav-text);" />
            <x-text-input id="adresse" name="adresse" type="text" class="mt-1 block w-full" 
                :value="old('adresse', $user->adresse)" autocomplete="street-address"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error class="mt-2" :messages="$errors->get('adresse')" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" 
                    class="px-6 py-2.5 rounded-lg font-semibold transition hover:scale-105 text-white"
                    style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                <i class="fas fa-save mr-2"></i> {{ __('Enregistrer') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="text-sm font-medium" style="color: #16A34A;">
                    <i class="fas fa-check-circle mr-1"></i> {{ __('Enregistré !') }}
                </p>
            @endif
        </div>
    </form>
</section>