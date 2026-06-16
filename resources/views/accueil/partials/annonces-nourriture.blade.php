<x-carousel-section 
    title="Nourriture &" 
    subtitle="Provende"
    icon="apple-alt"
    badge="🌾 Alimentation"
    :items="$annoncesNourriture"
    route="{{ route('annonces.aliments.index') }}"
    routeText="Voir tous les aliments"
    type="annonce"
    bgColor="#1a1a1a"
    textColor="#ffffff"
    titleColor="#FFD700"
    badgeBg="rgba(255,255,255,0.1)"
    badgeText="#FFD700"
>
    Aliments de qualité pour vos animaux d'élevage
</x-carousel-section>