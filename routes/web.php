<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Annonce\AllAnnonceController;
use App\Http\Controllers\Annonce\AnimalAnnonceController;
use App\Http\Controllers\Annonce\EscrementAnnonceController;
use App\Http\Controllers\Annonce\AlimentAnnonceController;
use App\Http\Controllers\Annonce\AccessoireAnnonceController;
use App\Http\Controllers\Service\VeterinaireController;
use App\Http\Controllers\Service\TransporteurController;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\RecompenseController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnnonceAdminController;
use App\Http\Controllers\Admin\PaiementAdminController;
use App\Http\Controllers\Admin\ParametreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// ROUTES PUBLIQUES
// ============================================

// Route d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');
// Recherche globale
Route::get('/search', [HomeController::class, 'search'])->name('global.search');

// ============================================
// ROUTES API POUR LES COMMUNES
// ============================================
Route::get('/api/communes/{departement}', [App\Http\Controllers\Auth\RegisteredUserController::class, 'getCommunesByDepartement'])->name('api.communes');

// FAQ
Route::get('/faq', [FAQController::class, 'index'])->name('faq');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Pages statiques
Route::get('/a-propos', function () {
    return view('pages.a-propos');
})->name('a-propos');

Route::get('/cgv', function () {
    return view('pages.cgv');
})->name('cgv');

Route::get('/confidentialite', function () {
    return view('pages.confidentialite');
})->name('confidentialite');

Route::get('/mentions-legales', function () {
    return view('pages.mentions-legales');
})->name('mentions-legales');

// ============================================
// ROUTES DES ANNONCES
// ============================================
Route::prefix('annonce')->name('annonce.')->group(function () {

    // ============================================
    // ANNONCES D'ANIMAUX
    // ============================================
    Route::prefix('animal')->name('animal.')->group(function () {
        // Routes publiques
        Route::get('/', [AnimalAnnonceController::class, 'index'])->name('index');
        Route::get('/{id}', [AnimalAnnonceController::class, 'show'])->name('show');
        
        // Routes protégées (authentification requise)
        Route::middleware(['auth'])->group(function () {
            Route::get('/creer', [AnimalAnnonceController::class, 'create'])->name('create');
            Route::post('/', [AnimalAnnonceController::class, 'store'])->name('store');
            Route::get('/{id}/modifier', [AnimalAnnonceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AnimalAnnonceController::class, 'update'])->name('update');
            Route::delete('/{id}', [AnimalAnnonceController::class, 'destroy'])->name('destroy');
            
            // Mes annonces et animaux
            Route::get('/mes-annonces', [AnimalAnnonceController::class, 'mesAnnonces'])->name('mes-annonces');
            Route::get('/mes-animaux', [AnimalAnnonceController::class, 'mesAnimaux'])->name('mes-animaux');
        });
    });

    // ============================================
    // ANNONCES D'ESCREMENTS / FUMIER
    // ============================================
    Route::prefix('escrement')->name('escrement.')->group(function () {
        // Routes publiques
        Route::get('/', [EscrementAnnonceController::class, 'index'])->name('index');
        Route::get('/{id}', [EscrementAnnonceController::class, 'show'])->name('show');
        
        // Routes protégées (authentification requise)
        Route::middleware(['auth'])->group(function () {
            Route::get('/creer', [EscrementAnnonceController::class, 'create'])->name('create');
            Route::post('/', [EscrementAnnonceController::class, 'store'])->name('store');
            Route::get('/{id}/modifier', [EscrementAnnonceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [EscrementAnnonceController::class, 'update'])->name('update');
            Route::delete('/{id}', [EscrementAnnonceController::class, 'destroy'])->name('destroy');
            
            // Mes annonces
            Route::get('/mes-annonces', [EscrementAnnonceController::class, 'mesAnnonces'])->name('mes-annonces');
        });
    });

    // ============================================
    // ANNONCES D'ALIMENTS / PROVENDES
    // ============================================
    Route::prefix('aliment')->name('aliment.')->group(function () {
        // Routes publiques
        Route::get('/', [AlimentAnnonceController::class, 'index'])->name('index');
        Route::get('/{id}', [AlimentAnnonceController::class, 'show'])->name('show');
        
        // Routes protégées (authentification requise)
        Route::middleware(['auth'])->group(function () {
            Route::get('/creer', [AlimentAnnonceController::class, 'create'])->name('create');
            Route::post('/', [AlimentAnnonceController::class, 'store'])->name('store');
            Route::get('/{id}/modifier', [AlimentAnnonceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AlimentAnnonceController::class, 'update'])->name('update');
            Route::delete('/{id}', [AlimentAnnonceController::class, 'destroy'])->name('destroy');
            
            // Mes annonces et produits
            Route::get('/mes-annonces', [AlimentAnnonceController::class, 'mesAnnonces'])->name('mes-annonces');
            Route::get('/mes-produits', [AlimentAnnonceController::class, 'mesProduits'])->name('mes-produits');
        });
    });

    // ============================================
    // ANNONCES D'ACCESSOIRES
    // ============================================
    Route::prefix('accessoire')->name('accessoire.')->group(function () {
        // Routes publiques
        Route::get('/', [AccessoireAnnonceController::class, 'index'])->name('index');
        Route::get('/{id}', [AccessoireAnnonceController::class, 'show'])->name('show');
        
        // Routes protégées (authentification requise)
        Route::middleware(['auth'])->group(function () {
            Route::get('/creer', [AccessoireAnnonceController::class, 'create'])->name('create');
            Route::post('/', [AccessoireAnnonceController::class, 'store'])->name('store');
            Route::get('/{id}/modifier', [AccessoireAnnonceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AccessoireAnnonceController::class, 'update'])->name('update');
            Route::delete('/{id}', [AccessoireAnnonceController::class, 'destroy'])->name('destroy');
            
            // Mes annonces et produits
            Route::get('/mes-annonces', [AccessoireAnnonceController::class, 'mesAnnonces'])->name('mes-annonces');
            Route::get('/mes-produits', [AccessoireAnnonceController::class, 'mesProduits'])->name('mes-produits');
        });
    });

    // ============================================
    // TOUTES LES ANNONCES
    // ============================================
    Route::get('/', [AllAnnonceController::class, 'index'])->name('all.index');
    Route::get('/{id}', [AllAnnonceController::class, 'show'])->name('all.show');

    // ============================================
    // ROUTE POUR RÉCUPÉRER LES INFOS D'UNE ANNONCE (AJAX)
    // ============================================
    Route::get('/{id}/info', [MessageController::class, 'getAnnonceInfo'])->name('info');
});

// ============================================
// ROUTES API POUR LES ANNONCES
// ============================================
Route::prefix('api/annonce')->name('api.annonce.')->group(function () {
    // Récupérer les races d'une espèce
    Route::get('/races/{especeId}', [AnimalAnnonceController::class, 'getRacesByEspece'])->name('races');
    
    // Récupérer les animaux d'un utilisateur (authentifié)
    Route::get('/animaux', [AnimalAnnonceController::class, 'getAnimauxByUser'])
        ->name('animaux')
        ->middleware('auth');
});

// ============================================
// ROUTES DES SERVICES
// ============================================
Route::prefix('service')->name('service.')->group(function () {
    
    // Routes pour tous les services
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    
    // ============================================
    // ROUTES POUR LES VÉTÉRINAIRES
    // ============================================
    Route::prefix('veterinaire')->name('veterinaire.')->group(function () {
        // Routes publiques
        Route::get('/', [VeterinaireController::class, 'index'])->name('index');
        Route::get('/{id}', [VeterinaireController::class, 'show'])->name('show');
        Route::get('/{id}/disponibilites', [VeterinaireController::class, 'getDisponibilites'])->name('disponibilites');
        
        // Routes protégées (authentification requise)
        Route::middleware(['auth'])->group(function () {
            // Route GET pour afficher le formulaire de rendez-vous
            Route::get('/{id}/rendez-vous', [VeterinaireController::class, 'rendezVousForm'])->name('rendez-vous.form');
            // Route POST pour soumettre la demande de rendez-vous
            Route::post('/{id}/rendez-vous', [VeterinaireController::class, 'prendreRendezVous'])->name('rendez-vous');
        });
    });
    
    // ============================================
    // ROUTES POUR LES TRANSPORTEURS
    // ============================================
    Route::prefix('transporteur')->name('transporteur.')->group(function () {
        // Routes publiques
        Route::get('/', [TransporteurController::class, 'index'])->name('index');
        Route::get('/{id}', [TransporteurController::class, 'show'])->name('show');
        
        // Routes protégées (authentification requise)
        Route::middleware(['auth'])->group(function () {
            // Route GET pour afficher le formulaire de devis
            Route::get('/{id}/devis', [TransporteurController::class, 'devisForm'])->name('devis.form');
            // Route POST pour soumettre la demande de devis
            Route::post('/{id}/devis', [TransporteurController::class, 'demanderDevis'])->name('devis');
        });
    });
});

// ============================================
// ROUTES DE FIDÉLITÉ ET RÉCOMPENSES (publiques)
// ============================================
Route::prefix('recompenses')->name('recompenses.')->group(function () {
    Route::get('/', [RecompenseController::class, 'index'])->name('index');
});

// ============================================
// ROUTES PROFIL (authentifiées - tous rôles)
// ============================================
Route::middleware(['auth'])->group(function () {
    // Gestion du profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// ROUTES DASHBOARD (authentifiées - tous rôles)
// ============================================
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // Tableau de bord
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    
    // Commandes
    Route::get('/commandes', [DashboardController::class, 'commandes'])->name('commandes');
    Route::get('/commandes/{id}', [DashboardController::class, 'commandeShow'])->name('commande.show');
    
    // Paiements
    Route::get('/paiements', [DashboardController::class, 'paiements'])->name('paiements');
    Route::get('/paiements/{id}', [DashboardController::class, 'paiementShow'])->name('paiement.show');
    
    // Points de fidélité
    Route::get('/points-fidelite', [DashboardController::class, 'pointsFidelite'])->name('points-fidelite');
    Route::post('/points-fidelite/echanger', [DashboardController::class, 'echangerPoints'])->name('points-fidelite.echanger');
    Route::get('/mes-recompenses', [DashboardController::class, 'mesRecompenses'])->name('mes-recompenses');
});

// ============================================
// ROUTES MESSAGERIE (authentifiées - tous rôles)
// ============================================
Route::get('messagerie/unread-count', [MessageController::class, 'unreadCount'])->name('messagerie.unread-count');

Route::middleware(['auth'])->prefix('messagerie')->name('messagerie.')->group(function () {
    // Routes principales
    Route::get('/', [MessageController::class, 'index'])->name('index');
    Route::get('/{id}', [MessageController::class, 'show'])->name('show');
    Route::post('/send', [MessageController::class, 'send'])->name('send');
    Route::put('/{id}', [MessageController::class, 'update'])->name('update');
    Route::delete('/{id}', [MessageController::class, 'destroy'])->name('destroy');
    
    // Routes pour les commandes via messages
    Route::post('/initier-commande', [MessageController::class, 'initierCommande'])->name('initier-commande');
    Route::post('/creer-commande', [MessageController::class, 'creerCommande'])->name('creer-commande');
    Route::post('/demander-paiement', [MessageController::class, 'demanderPaiement'])->name('demander-paiement');
    
    // Autres routes
    Route::post('/start-from-annonce', [MessageController::class, 'startFromAnnonce'])->name('start-from-annonce');
    Route::post('/mark-all-read/{id}', [MessageController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/download-piece/{id}', [MessageController::class, 'downloadPiece'])->name('download-piece');
});

// ============================================
// ROUTES COMMANDES (API/AJAX) - Utilise PaiementController
// ============================================
Route::middleware(['auth'])->prefix('commandes')->name('commandes.')->group(function () {
    // Route pour récupérer les infos d'une commande (AJAX)
    Route::get('/{id}/info', [PaiementController::class, 'getCommandeInfo'])->name('info');
    // Route pour ajuster le paiement (AJAX)
    Route::post('/{id}/ajuster', [PaiementController::class, 'ajusterPaiement'])->name('ajuster');
    // Route pour afficher une commande
    Route::get('/{id}', [PaiementController::class, 'showCommande'])->name('show');
});

// ============================================
// ROUTES DE PAIEMENT - COMPLÈTES
// ============================================
Route::middleware(['auth'])->prefix('paiement')->name('paiement.')->group(function () {
    // Page de paiement
    Route::get('/{commandeId}', [PaiementController::class, 'pagePaiement'])->name('page');
    // Traitement du paiement
    Route::post('/{commandeId}/process', [PaiementController::class, 'processPaiement'])->name('process');
    // Page de succès
    Route::get('/{commandeId}/succes', [PaiementController::class, 'paiementSucces'])->name('succes');
    // Page d'échec
    Route::get('/{commandeId}/echec', [PaiementController::class, 'paiementEchec'])->name('echec');
    // Vérifier le statut d'un paiement
    Route::get('/{commandeId}/statut', [PaiementController::class, 'verifierStatut'])->name('statut');
});

// ============================================
// ROUTES ADMINISTRATION (rôle admin requis)
// ============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard admin
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{id}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
    
    // Gestion des annonces (administration)
    Route::prefix('annonces')->name('annonces.')->group(function () {
        Route::get('/', [AnnonceAdminController::class, 'index'])->name('index');
        Route::get('/{id}', [AnnonceAdminController::class, 'show'])->name('show');
        Route::post('/{id}/valider', [AnnonceAdminController::class, 'valider'])->name('valider');
        Route::post('/{id}/rejeter', [AnnonceAdminController::class, 'rejeter'])->name('rejeter');
        Route::delete('/{id}', [AnnonceAdminController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/mettre-en-avant', [AnnonceAdminController::class, 'mettreEnAvant'])->name('mettre-en-avant');
    });
    
    // Gestion des paiements (administration)
    Route::prefix('paiements')->name('paiements.')->group(function () {
        Route::get('/', [PaiementAdminController::class, 'index'])->name('index');
        Route::get('/{id}', [PaiementAdminController::class, 'show'])->name('show');
        Route::post('/{id}/valider', [PaiementAdminController::class, 'valider'])->name('valider');
        Route::post('/{id}/annuler', [PaiementAdminController::class, 'annuler'])->name('annuler');
    });
    
    // Paramètres du site
    Route::get('/parametres', [ParametreController::class, 'edit'])->name('parametres.edit');
    Route::put('/parametres', [ParametreController::class, 'update'])->name('parametres.update');
    Route::post('/parametres/logo', [ParametreController::class, 'updateLogo'])->name('parametres.logo');
});

// Inclusion des routes d'authentification
require __DIR__.'/auth.php';