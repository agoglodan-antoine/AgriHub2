<section>
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Mot de passe actuel -->
        <div>
            <x-input-label for="update_password_current_password" :value="__('Mot de passe actuel')" style="color: var(--color-nav-text);" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" 
                class="mt-1 block w-full" autocomplete="current-password"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <!-- Nouveau mot de passe -->
        <div>
            <x-input-label for="update_password_password" :value="__('Nouveau mot de passe')" style="color: var(--color-nav-text);" />
            <x-text-input id="update_password_password" name="password" type="password" 
                class="mt-1 block w-full" autocomplete="new-password"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <!-- Confirmation du nouveau mot de passe -->
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le mot de passe')" style="color: var(--color-nav-text);" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                class="mt-1 block w-full" autocomplete="new-password"
                style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" 
                    class="px-6 py-2.5 rounded-lg font-semibold transition hover:scale-105 text-white"
                    style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                <i class="fas fa-key mr-2"></i> {{ __('Changer le mot de passe') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="text-sm font-medium" style="color: #16A34A;">
                    <i class="fas fa-check-circle mr-1"></i> {{ __('Mot de passe mis à jour !') }}
                </p>
            @endif
        </div>
    </form>
</section>