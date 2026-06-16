<x-app-layout>
    <!-- Header Slot -->
    <x-slot name="header">
        @include('accueil.partials.hero')
    </x-slot>

    <!-- Section Annonces Animaux -->
    @include('accueil.partials.annonces-animaux')

    <!-- Section Annonces Nourriture / Provende -->
    @include('accueil.partials.annonces-nourriture')

    <!-- Section Annonces Accessoires -->
    @include('accueil.partials.annonces-accessoires')

    <!-- Section Annonces Escrements / Fumier -->
    @include('accueil.partials.annonces-escrements')

    <!-- Section Vétérinaires -->
    @include('accueil.partials.services-veterinaires')

    <!-- Section Transporteurs -->
    @include('accueil.partials.services-transport')
</x-app-layout>