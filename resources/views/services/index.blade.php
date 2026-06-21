<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-concierge-bell text-4xl" style="color: var(--color-primary-light);"></i>
                    Services
                </h1>
                <p class="mt-2" style="color: var(--color-secondary-light);">Des professionnels à votre service pour l'élevage</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="background-color: var(--color-bg-body);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Statistiques -->
            <div class="grid grid-cols-2 gap-6 mb-10">
                <div class="rounded-xl shadow-md p-6 text-center" style="background-color: var(--color-bg-white);">
                    <i class="fas fa-stethoscope text-3xl mb-2" style="color: var(--color-primary);"></i>
                    <div class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ $totalVeterinaires }}</div>
                    <div class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">Vétérinaires disponibles</div>
                </div>
                <div class="rounded-xl shadow-md p-6 text-center" style="background-color: var(--color-bg-white);">
                    <i class="fas fa-truck text-3xl mb-2" style="color: var(--color-primary);"></i>
                    <div class="text-2xl font-bold" style="color: var(--color-nav-text);">{{ $totalTransporteurs }}</div>
                    <div class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">Transporteurs disponibles</div>
                </div>
            </div>

            <!-- Section Vétérinaires -->
            <section class="mb-16">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold" style="color: var(--color-nav-text);">
                            <i class="fas fa-stethoscope mr-2" style="color: var(--color-primary);"></i>
                            Vétérinaires
                        </h2>
                        <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">Des professionnels à votre service pour la santé de vos animaux</p>
                    </div>
                    <a href="{{ route('service.veterinaire.index') }}" class="font-semibold transition" style="color: var(--color-primary);" onmouseover="this.style.color='var(--color-primary-dark)'" onmouseout="this.style.color='var(--color-primary)'">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($veterinaires->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($veterinaires as $veterinaire)
                            @include('components.carousel-veterinaire', ['veterinaire' => $veterinaire])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 rounded-xl" style="background-color: var(--color-bg-white);">
                        <i class="fas fa-stethoscope text-4xl mb-2" style="color: var(--color-primary-light);"></i>
                        <p style="color: var(--color-nav-text); opacity: 0.6;">Aucun vétérinaire disponible pour le moment</p>
                    </div>
                @endif
            </section>

            <!-- Section Transporteurs -->
            <section>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold" style="color: var(--color-nav-text);">
                            <i class="fas fa-truck mr-2" style="color: var(--color-primary);"></i>
                            Transporteurs
                        </h2>
                        <p class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">Transport fiable et sécurisé pour vos animaux et produits agricoles</p>
                    </div>
                    <a href="{{ route('service.transporteur.index') }}" class="font-semibold transition" style="color: var(--color-primary);" onmouseover="this.style.color='var(--color-primary-dark)'" onmouseout="this.style.color='var(--color-primary)'">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($transporteurs->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($transporteurs as $transporteur)
                            @include('components.carousel-transporteur', ['transporteur' => $transporteur])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 rounded-xl" style="background-color: var(--color-bg-white);">
                        <i class="fas fa-truck text-4xl mb-2" style="color: var(--color-primary-light);"></i>
                        <p style="color: var(--color-nav-text); opacity: 0.6;">Aucun transporteur disponible pour le moment</p>
                    </div>
                @endif
            </section>

        </div>
    </div>
</x-app-layout>