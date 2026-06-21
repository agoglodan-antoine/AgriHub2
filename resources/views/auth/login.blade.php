<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold" style="color: var(--color-primary-dark);">
            {{ __('Bienvenue !') }}
        </h2>
        <p class="text-sm mt-1" style="color: var(--color-nav-text); opacity: 0.6;">
            {{ __('Connectez-vous à votre compte') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse email')" style="color: var(--color-nav-text);" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="exemple@email.com"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Mot de passe')" style="color: var(--color-nav-text);" />
                @if (Route::has('password.request'))
                    <a class="text-xs transition" 
                       style="color: var(--color-primary);" 
                       href="{{ route('password.request') }}"
                       onmouseover="this.style.color='var(--color-primary-dark)'"
                       onmouseout="this.style.color='var(--color-primary)'">
                        {{ __('Mot de passe oublié ?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••"
                            style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded shadow-sm focus:ring-2 transition cursor-pointer" 
                       style="border-color: var(--color-nav-border); accent-color: var(--color-primary);" name="remember">
                <span class="ms-2 text-sm" style="color: var(--color-nav-text); opacity: 0.8;">{{ __('Se souvenir de moi') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <button type="submit" 
                    class="w-full py-3 rounded-lg font-semibold transition hover:scale-[1.02] text-white flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                <i class="fas fa-sign-in-alt"></i>
                {{ __('Se connecter') }}
            </button>
        </div>
    </form>

    <!-- Lien vers l'inscription -->
    <div class="mt-6 pt-4 text-center border-t" style="border-color: var(--color-nav-border);">
        <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.7;">
            {{ __("Vous n'avez pas encore de compte ?") }}
            <a href="{{ route('register') }}" 
               class="font-semibold transition flex items-center justify-center gap-1 mt-1"
               style="color: var(--color-primary);"
               onmouseover="this.style.color='var(--color-primary-dark)'"
               onmouseout="this.style.color='var(--color-primary)'">
                {{ __('Créer un compte gratuitement') }}
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </p>
    </div>

    <!-- Lien vers la page d'accueil -->
    <div class="mt-4 text-center">
        <a href="{{ route('home') }}" 
           class="text-xs transition inline-flex items-center gap-1"
           style="color: var(--color-nav-text); opacity: 0.4;"
           onmouseover="this.style.opacity='0.8'"
           onmouseout="this.style.opacity='0.4'">
            <i class="fas fa-home"></i>
            {{ __('Retour à l\'accueil') }}
        </a>
    </div>
</x-guest-layout>