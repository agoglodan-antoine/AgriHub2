<x-carousel-section 
    title="Accessoires &" 
    subtitle="Équipements"
    icon="tools"
    badge="🔧 Équipements"
    :items="$annoncesAccessoires"
    route="{{ route('annonces.accessoires.index') }}"
    routeText="Voir tous les accessoires"
    type="annonce"
    bgColor="#f0ede4"
    textColor="#1a1a1a"
    titleColor="var(--color-primary)"
    badgeBg="rgba(212,175,55,0.15)"
    badgeText="var(--color-primary-dark)"
>
    Tout le matériel nécessaire pour votre exploitation agricole
</x-carousel-section>