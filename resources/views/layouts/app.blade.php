<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AgriHub') }} - Plateforme Agricole Connectée</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome for Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- AOS Animation -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        
        <!-- Swiper JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Variables pour le mode clair (défaut) */
            :root {
                /* Couleurs principales - Thème Agricole */
                --color-primary: #D4AF37;      /* Or */
                --color-primary-light: #FFD700; /* Jaune doré */
                --color-primary-dark: #B8960F;  /* Or foncé */
                --color-secondary: #C2B280;     /* Sable */
                --color-secondary-light: #E8D5A3; /* Sable clair */
                --color-secondary-dark: #A6945C;  /* Sable foncé */
                --color-tertiary: #4CAF50;      /* Vert */
                --color-tertiary-dark: #2E7D32; /* Vert foncé */
                
                /* Couleurs de navigation - Mode Clair */
                --color-nav-bg-start: #FFF8E7;    /* Fond dégradé début */
                --color-nav-bg-end: #FFFDE0;      /* Fond dégradé fin */
                --color-nav-border: #FDE68A;      /* Bordure */
                --color-nav-text: #374151;        /* Texte */
                --color-nav-text-hover: #D4AF37;  /* Texte au survol */
                --color-nav-highlight: #F59E0B;   /* Surbrillance */
                
                /* Couleurs de la bande décorative */
                --color-strip-1: #FBBF24;
                --color-strip-2: #F59E0B;
                --color-strip-3: #D4AF37;
                --color-strip-4: #B8960F;
                --color-strip-5: #C2B280;
                
                /* Couleurs d'arrière-plan */
                --color-bg-body: #F5F5F5;
                --color-bg-white: #FFFFFF;
                --color-bg-gray: #F9FAFB;
                
                /* Ombres */
                --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                
                /* Transitions */
                --transition-fast: 150ms ease-in-out;
                --transition-normal: 250ms ease-in-out;
                --transition-slow: 350ms ease-in-out;

                /* Couleurs de fond des sections */
                --section-dark: #1a1a1a;
                --section-dark-alt: #2d2d2d;
                --section-light: #f8f6f0;
                --section-light-alt: #f0ede4;
            }
            
            /* Variables pour le mode sombre */
            body.dark {
                --color-nav-bg-start: #1F2937;
                --color-nav-bg-end: #111827;
                --color-nav-border: #374151;
                --color-nav-text: #9CA3AF;
                --color-nav-text-hover: #D4AF37;
                --color-nav-highlight: #F59E0B;
                --color-bg-body: #111827;
                --color-bg-white: #1F2937;
                --color-bg-gray: #374151;
            }
            
            /* Classes utilitaires génériques */
            .bg-primary { background-color: var(--color-primary); }
            .bg-primary-light { background-color: var(--color-primary-light); }
            .bg-primary-dark { background-color: var(--color-primary-dark); }
            .bg-secondary { background-color: var(--color-secondary); }
            .bg-secondary-light { background-color: var(--color-secondary-light); }
            .bg-tertiary { background-color: var(--color-tertiary); }
            .bg-tertiary-dark { background-color: var(--color-tertiary-dark); }
            
            .text-primary { color: var(--color-primary); }
            .text-primary-light { color: var(--color-primary-light); }
            .text-secondary { color: var(--color-secondary); }
            .text-tertiary { color: var(--color-tertiary); }
            
            .hover\:bg-primary-dark:hover { background-color: var(--color-primary-dark); }
            .hover\:text-primary:hover { color: var(--color-primary); }
            
            .border-primary { border-color: var(--color-primary); }
            
            .hero-gradient {
                background: linear-gradient(135deg, var(--color-tertiary) 0%, var(--color-tertiary-dark) 100%);
            }
            
            .card-hover {
                transition: all var(--transition-normal);
            }
            
            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: var(--shadow-lg);
            }
            
            /* Transition pour le changement de mode */
            body, body * {
                transition: background-color var(--transition-fast), border-color var(--transition-fast), color var(--transition-fast), box-shadow var(--transition-fast);
            }

            /* Pour les sections sombres - amélioration du contraste */
            .section-dark .card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .section-dark .card:hover {
                background: rgba(255, 255, 255, 0.1);
                border-color: var(--color-primary);
            }
            
            /* Animation des cartes au survol */
            .carousel-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .carousel-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }
            
            /* Section sombre - texte */
            .section-dark .section-description {
                color: rgba(255, 255, 255, 0.7);
            }
            
            .section-dark .section-title {
                color: #ffffff;
            }
            
            .section-dark .section-subtitle {
                color: #FFD700;
            }
            
            /* Section claire - texte */
            .section-light .section-description {
                color: rgba(0, 0, 0, 0.6);
            }
            
            .section-light .section-title {
                color: #1a1a1a;
            }
            
            .section-light .section-subtitle {
                color: var(--color-primary);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" style="background-color: var(--color-bg-body);">
            @include('layouts.nav.app')

            <!-- Page Header -->
            @isset($header)
                <header>
                    {{ $header }}
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Modal de contact -->
            @auth
                @include('components.contact-modal')
            @endauth
            
            @include('layouts.foot.app')
        </div>

        <!-- Scripts -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            AOS.init({
                duration: 1000,
                once: true
            });

            // Gestion dynamique des couleurs de survol en mode sombre/clair
            const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

            function updateHoverColors(isDark) {
                const dropdownItems = document.querySelectorAll('.group\\/dropdown div.absolute a');
                const mobileItems = document.querySelectorAll('#mobile-menu a, #mobile-menu button');
                
                if (isDark) {
                    dropdownItems.forEach(item => {
                        item.setAttribute('data-hover-bg', '#374151');
                        item.setAttribute('data-original-bg', 'transparent');
                    });
                    mobileItems.forEach(item => {
                        item.setAttribute('data-hover-bg', '#374151');
                        item.setAttribute('data-original-bg', 'transparent');
                    });
                } else {
                    dropdownItems.forEach(item => {
                        item.setAttribute('data-hover-bg', '#F3F4F6');
                        item.setAttribute('data-original-bg', 'transparent');
                    });
                    mobileItems.forEach(item => {
                        item.setAttribute('data-hover-bg', '#F3F4F6');
                        item.setAttribute('data-original-bg', 'transparent');
                    });
                }
            }

            // Écouter les changements de thème
            darkModeMediaQuery.addEventListener('change', (e) => {
                updateHoverColors(e.matches);
            });
        </script>
        
        @stack('scripts')
    </body>
</html>