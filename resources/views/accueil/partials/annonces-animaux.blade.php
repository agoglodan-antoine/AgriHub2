<x-carousel-section 
    title="Animaux" 
    subtitle="disponibles"
    icon="paw"
    badge="🐾 Annonces"
    :items="$annoncesAnimaux"
    route="{{ route('annonces.animaux.index') }}"
    routeText="Voir tous les animaux"
    type="annonce"
    bgColor="#f8f6f0"
    textColor="#1a1a1a"
    titleColor="var(--color-primary)"
    badgeBg="rgba(212,175,55,0.15)"
    badgeText="var(--color-primary-dark)"
>
    Découvrez notre sélection d'animaux d'élevage de qualité auprès des éleveurs certifiés
</x-carousel-section>