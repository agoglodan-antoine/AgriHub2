<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-user text-4xl" style="color: var(--color-primary-light);"></i>
                    Mon profil
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">
                    Gérez vos informations personnelles et votre mot de passe
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Informations du profil -->
            <div class="rounded-xl shadow-md overflow-hidden" style="background-color: var(--color-bg-white);">
                <div class="p-6 border-b" style="border-color: var(--color-nav-border);">
                    <h2 class="text-lg font-semibold flex items-center gap-2" style="color: var(--color-nav-text);">
                        <i class="fas fa-user-edit" style="color: var(--color-primary);"></i>
                        Informations personnelles
                    </h2>
                    <p class="text-sm mt-1" style="color: var(--color-nav-text); opacity: 0.6;">
                        Mettez à jour vos informations personnelles
                    </p>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Changement de mot de passe -->
            <div class="rounded-xl shadow-md overflow-hidden" style="background-color: var(--color-bg-white);">
                <div class="p-6 border-b" style="border-color: var(--color-nav-border);">
                    <h2 class="text-lg font-semibold flex items-center gap-2" style="color: var(--color-nav-text);">
                        <i class="fas fa-lock" style="color: var(--color-primary);"></i>
                        Sécurité
                    </h2>
                    <p class="text-sm mt-1" style="color: var(--color-nav-text); opacity: 0.6;">
                        Changez votre mot de passe
                    </p>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Suppression du compte -->
            <div class="rounded-xl shadow-md overflow-hidden" style="background-color: var(--color-bg-white); border: 1px solid #FEE2E2;">
                <div class="p-6 border-b" style="border-color: #FEE2E2;">
                    <h2 class="text-lg font-semibold flex items-center gap-2" style="color: #DC2626;">
                        <i class="fas fa-trash-alt"></i>
                        Supprimer mon compte
                    </h2>
                    <p class="text-sm mt-1" style="color: #EF4444; opacity: 0.8;">
                        Cette action est irréversible
                    </p>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>