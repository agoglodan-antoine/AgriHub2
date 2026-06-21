<x-carousel-section 
    title="Escrements &" 
    subtitle="Fumier"
    icon="leaf"
    badge="🌱 Fertilisants"
    :items="$annoncesEscrements"
    route="{{ route('annonce.escrement.index') }}"
    routeText="Voir tous les escrements"
    type="annonce"
    bgColor="#2d2d2d"
    textColor="#ffffff"
    titleColor="#FFD700"
    badgeBg="rgba(255,255,255,0.1)"
    badgeText="#FFD700"
>
    Engrais naturels de qualité pour vos cultures
</x-carousel-section>