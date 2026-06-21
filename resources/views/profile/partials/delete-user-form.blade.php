<section class="space-y-6">
    <div class="p-4 rounded-lg" style="background-color: #FEF2F2; border: 1px solid #FECACA;">
        <div class="flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-lg mt-0.5" style="color: #DC2626;"></i>
            <div>
                <p class="text-sm" style="color: #991B1B;">
                    <strong>Attention :</strong> Cette action est irréversible. Toutes vos données (annonces, commandes, messages, etc.) seront définitivement supprimées.
                </p>
            </div>
        </div>
    </div>

    <button type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-6 py-2.5 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center gap-2"
            style="background: linear-gradient(135deg, #DC2626, #B91C1C);"
            onmouseover="this.style.background='linear-gradient(135deg, #B91C1C, #991B1B)'"
            onmouseout="this.style.background='linear-gradient(135deg, #DC2626, #B91C1C)'">
        <i class="fas fa-trash-alt"></i>
        {{ __('Supprimer mon compte') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #FEE2E2;">
                    <i class="fas fa-exclamation-triangle text-xl" style="color: #DC2626;"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold" style="color: var(--color-nav-text);">
                        {{ __('Supprimer votre compte ?') }}
                    </h2>
                    <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">
                        {{ __('Cette action est irréversible.') }}
                    </p>
                </div>
            </div>

            <p class="mt-2 text-sm" style="color: var(--color-nav-text); opacity: 0.7;">
                {{ __('Pour confirmer la suppression de votre compte, veuillez saisir votre mot de passe.') }}
            </p>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Mot de passe')" style="color: var(--color-nav-text);" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="{{ __('Votre mot de passe') }}"
                    style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" 
                        x-on:click="$dispatch('close')"
                        class="px-4 py-2 rounded-lg font-medium transition"
                        style="background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                        onmouseover="this.style.backgroundColor='var(--color-nav-border)'"
                        onmouseout="this.style.backgroundColor='var(--color-bg-gray)'">
                    {{ __('Annuler') }}
                </button>

                <button type="submit" 
                        class="px-4 py-2 rounded-lg font-semibold transition hover:scale-105 text-white flex items-center gap-2"
                        style="background: linear-gradient(135deg, #DC2626, #B91C1C);"
                        onmouseover="this.style.background='linear-gradient(135deg, #B91C1C, #991B1B)'"
                        onmouseout="this.style.background='linear-gradient(135deg, #DC2626, #B91C1C)'">
                    <i class="fas fa-trash-alt"></i>
                    {{ __('Confirmer la suppression') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>