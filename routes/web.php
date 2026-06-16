<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Annonce\AnimalAnnonceController;
use App\Http\Controllers\Annonce\EscrementAnnonceController;
use App\Http\Controllers\Annonce\AlimentAnnonceController;
use App\Http\Controllers\Annonce\AccessoireAnnonceController;
use App\Http\Controllers\ServiceVeterinaireController;
use App\Http\Controllers\TransporteurController;
use App\Http\Controllers\RecompenseController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnnonceAdminController;
use App\Http\Controllers\Admin\TransactionAdminController;
use App\Http\Controllers\Admin\ParametreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController as UserDashboardController;
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

// Dashboard (redirige vers le dashboard selon le rôle)
Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

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
// ROUTES DES ANNONCES (PUBLIQUES - sans vérification de rôle)
// ============================================
Route::prefix('annonces')->name('annonces.')->group(function () {
    
    // Routes principales des annonces (tous types)
    Route::get('/', [AnnonceController::class, 'index'])->name('index');
    Route::get('/{id}', [AnnonceController::class, 'show'])->name('show');
    
    // Routes pour les annonces d'animaux
    Route::prefix('animaux')->name('animaux.')->group(function () {
        Route::get('/', [AnimalAnnonceController::class, 'index'])->name('index');
        Route::get('/{id}', [AnimalAnnonceController::class, 'show'])->name('show');
    });
    
    // Routes pour les annonces d'escrements/fumier
    Route::prefix('escrements')->name('escrements.')->group(function () {
        Route::get('/', [EscrementAnnonceController::class, 'index'])->name('index');
        Route::get('/{id}', [EscrementAnnonceController::class, 'show'])->name('show');
    });
    
    // Routes pour les annonces d'aliments/provendes
    Route::prefix('aliments')->name('aliments.')->group(function () {
        Route::get('/', [AlimentAnnonceController::class, 'index'])->name('index');
        Route::get('/{id}', [AlimentAnnonceController::class, 'show'])->name('show');
    });
    
    // Routes pour les annonces d'accessoires
    Route::prefix('accessoires')->name('accessoires.')->group(function () {
        Route::get('/', [AccessoireAnnonceController::class, 'index'])->name('index');
        Route::get('/{id}', [AccessoireAnnonceController::class, 'show'])->name('show');
    });
});

// ============================================
// ROUTES DES SERVICES (publiques)
// ============================================

// Services vétérinaires
Route::prefix('services-veterinaires')->name('services-veterinaires.')->group(function () {
    Route::get('/', [ServiceVeterinaireController::class, 'index'])->name('index');
    Route::get('/{id}', [ServiceVeterinaireController::class, 'show'])->name('show');
});

// Transporteurs
Route::prefix('transporteurs')->name('transporteurs.')->group(function () {
    Route::get('/', [TransporteurController::class, 'index'])->name('index');
    Route::get('/{id}', [TransporteurController::class, 'show'])->name('show');
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
    
    // Dashboard utilisateur
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Mes annonces (tous types)
    Route::get('/mes-annonces', [AnnonceController::class, 'mesAnnonces'])->name('mes-annonces');
    
    // Mes transactions
    Route::get('/mes-transactions', [TransactionController::class, 'mesTransactions'])->name('mes-transactions');
    
    // Points de fidélité
    Route::get('/mes-points', [RecompenseController::class, 'mesPoints'])->name('mes-points');
    Route::post('/echanger/{id}', [RecompenseController::class, 'echanger'])->name('echanger');
    
    // Rendez-vous vétérinaires
    Route::get('/prendre-rendez-vous/{id}', [ServiceVeterinaireController::class, 'prendreRendezVous'])->name('prendre-rendez-vous');
    Route::post('/rendez-vous', [ServiceVeterinaireController::class, 'storeRendezVous'])->name('store-rendez-vous');
    Route::get('/mes-rendez-vous', [ServiceVeterinaireController::class, 'mesRendezVous'])->name('mes-rendez-vous');
    
    // Devis transporteurs
    Route::get('/demander-devis/{id}', [TransporteurController::class, 'demanderDevis'])->name('demander-devis');
    Route::post('/devis', [TransporteurController::class, 'storeDevis'])->name('store-devis');
    Route::get('/mes-devis', [TransporteurController::class, 'mesDevis'])->name('mes-devis');
});

// ============================================
// ROUTES MESSAGERIE (authentifiées - tous rôles)
// ============================================
Route::middleware(['auth'])->prefix('messagerie')->name('messagerie.')->group(function () {
    Route::get('/', [MessageController::class, 'index'])->name('index');
    Route::get('/{id}', [MessageController::class, 'show'])->name('show');
    Route::post('/send', [MessageController::class, 'send'])->name('send');
    Route::put('/{id}', [MessageController::class, 'update'])->name('update'); 
    Route::post('/start-from-annonce', [MessageController::class, 'startFromAnnonce'])->name('start-from-annonce');
    Route::delete('/{id}', [MessageController::class, 'destroy'])->name('destroy');
    Route::post('/mark-all-read/{id}', [MessageController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/unread-count', [MessageController::class, 'unreadCount'])->name('unread-count');
    Route::get('/download-piece/{id}', [MessageController::class, 'downloadPiece'])->name('download-piece');
});

// ============================================
// GROUPE DES ROUTES POUR ÉLEVEURS
// ============================================
Route::middleware(['auth', 'role:eleveur'])->prefix('eleveur')->name('eleveur.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('eleveur.dashboard');
    })->name('dashboard');
    
    // Gestion des annonces d'animaux
    Route::get('/annonces/animaux/create', [AnimalAnnonceController::class, 'create'])->name('annonces.animaux.create');
    Route::post('/annonces/animaux', [AnimalAnnonceController::class, 'store'])->name('annonces.animaux.store');
    Route::get('/annonces/animaux/{id}/edit', [AnimalAnnonceController::class, 'edit'])->name('annonces.animaux.edit');
    Route::put('/annonces/animaux/{id}', [AnimalAnnonceController::class, 'update'])->name('annonces.animaux.update');
    Route::delete('/annonces/animaux/{id}', [AnimalAnnonceController::class, 'destroy'])->name('annonces.animaux.destroy');
    Route::get('/mes-annonces/animaux', [AnimalAnnonceController::class, 'mesAnnonces'])->name('annonces.animaux.mes-annonces');
    
    // Gestion des annonces d'escrements
    Route::get('/annonces/escrements/create', [EscrementAnnonceController::class, 'create'])->name('annonces.escrements.create');
    Route::post('/annonces/escrements', [EscrementAnnonceController::class, 'store'])->name('annonces.escrements.store');
    Route::get('/annonces/escrements/{id}/edit', [EscrementAnnonceController::class, 'edit'])->name('annonces.escrements.edit');
    Route::put('/annonces/escrements/{id}', [EscrementAnnonceController::class, 'update'])->name('annonces.escrements.update');
    Route::delete('/annonces/escrements/{id}', [EscrementAnnonceController::class, 'destroy'])->name('annonces.escrements.destroy');
    Route::get('/mes-annonces/escrements', [EscrementAnnonceController::class, 'mesAnnonces'])->name('annonces.escrements.mes-annonces');
    
    // Mes ventes
    Route::get('/mes-ventes', [TransactionController::class, 'mesVentes'])->name('mes-ventes');
    
    // Statistiques
    Route::get('/statistiques', [App\Http\Controllers\Eleveur\StatistiqueController::class, 'index'])->name('statistiques');
    
    // Gestion des animaux
    Route::resource('/animaux', App\Http\Controllers\Eleveur\AnimalController::class)->except(['show']);
    Route::get('/animaux/{id}', [App\Http\Controllers\Eleveur\AnimalController::class, 'show'])->name('animaux.show');
});

// ============================================
// GROUPE DES ROUTES POUR VENDEURS DE NOURRITURE
// ============================================
Route::middleware(['auth', 'role:vendeur_nourriture'])->prefix('vendeur-nourriture')->name('vendeur.nourriture.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('vendeur-nourriture.dashboard');
    })->name('dashboard');
    
    // Gestion des annonces d'aliments
    Route::get('/annonces/aliments/create', [AlimentAnnonceController::class, 'create'])->name('annonces.aliments.create');
    Route::post('/annonces/aliments', [AlimentAnnonceController::class, 'store'])->name('annonces.aliments.store');
    Route::get('/annonces/aliments/{id}/edit', [AlimentAnnonceController::class, 'edit'])->name('annonces.aliments.edit');
    Route::put('/annonces/aliments/{id}', [AlimentAnnonceController::class, 'update'])->name('annonces.aliments.update');
    Route::delete('/annonces/aliments/{id}', [AlimentAnnonceController::class, 'destroy'])->name('annonces.aliments.destroy');
    Route::get('/mes-annonces/aliments', [AlimentAnnonceController::class, 'mesAnnonces'])->name('annonces.aliments.mes-annonces');
    
    // Mes produits
    Route::get('/mes-produits', [AlimentAnnonceController::class, 'mesProduits'])->name('mes-produits');
    
    // Commandes reçues
    Route::get('/commandes', [TransactionController::class, 'mesCommandesRecues'])->name('commandes');
    
    // Gestion du stock
    Route::get('/stock', [App\Http\Controllers\VendeurNourriture\StockController::class, 'index'])->name('stock');
    Route::put('/stock/{id}', [App\Http\Controllers\VendeurNourriture\StockController::class, 'update'])->name('stock.update');
});

// ============================================
// GROUPE DES ROUTES POUR VENDEURS D'ACCESSOIRES
// ============================================
Route::middleware(['auth', 'role:vendeur_accessoire'])->prefix('vendeur-accessoire')->name('vendeur.accessoire.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('vendeur-accessoire.dashboard');
    })->name('dashboard');
    
    // Gestion des annonces d'accessoires
    Route::get('/annonces/accessoires/create', [AccessoireAnnonceController::class, 'create'])->name('annonces.accessoires.create');
    Route::post('/annonces/accessoires', [AccessoireAnnonceController::class, 'store'])->name('annonces.accessoires.store');
    Route::get('/annonces/accessoires/{id}/edit', [AccessoireAnnonceController::class, 'edit'])->name('annonces.accessoires.edit');
    Route::put('/annonces/accessoires/{id}', [AccessoireAnnonceController::class, 'update'])->name('annonces.accessoires.update');
    Route::delete('/annonces/accessoires/{id}', [AccessoireAnnonceController::class, 'destroy'])->name('annonces.accessoires.destroy');
    Route::get('/mes-annonces/accessoires', [AccessoireAnnonceController::class, 'mesAnnonces'])->name('annonces.accessoires.mes-annonces');
    
    // Mes produits
    Route::get('/mes-produits', [AccessoireAnnonceController::class, 'mesProduits'])->name('mes-produits');
    
    // Commandes reçues
    Route::get('/commandes', [TransactionController::class, 'mesCommandesRecues'])->name('commandes');
});

// ============================================
// GROUPE DES ROUTES POUR ACHETEURS
// ============================================
Route::middleware(['auth', 'role:acheteur'])->prefix('acheteur')->name('acheteur.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('acheteur.dashboard');
    })->name('dashboard');
    
    // Mes commandes
    Route::get('/mes-commandes', [TransactionController::class, 'mesCommandes'])->name('mes-commandes');
    
    // Favoris
    Route::get('/favoris', [App\Http\Controllers\Acheteur\FavoriController::class, 'index'])->name('favoris');
    Route::post('/favoris/{id}', [App\Http\Controllers\Acheteur\FavoriController::class, 'toggle'])->name('favoris.toggle');
});

// ============================================
// GROUPE DES ROUTES POUR VÉTÉRINAIRES
// ============================================
Route::middleware(['auth', 'role:veterinaire'])->prefix('veterinaire')->name('veterinaire.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('veterinaire.dashboard');
    })->name('dashboard');
    
    // Gestion des rendez-vous (reçus)
    Route::get('/mes-rendez-vous', [ServiceVeterinaireController::class, 'mesRendezVous'])->name('mes-rendez-vous');
    Route::put('/rendez-vous/{id}/statut', [ServiceVeterinaireController::class, 'updateStatut'])->name('update-rendez-vous');
    
    // Disponibilités
    Route::get('/disponibilites', [App\Http\Controllers\Veterinaire\DisponibiliteController::class, 'index'])->name('disponibilites');
    Route::post('/disponibilites', [App\Http\Controllers\Veterinaire\DisponibiliteController::class, 'store'])->name('disponibilites.store');
    Route::delete('/disponibilites/{id}', [App\Http\Controllers\Veterinaire\DisponibiliteController::class, 'destroy'])->name('disponibilites.destroy');
});

// ============================================
// GROUPE DES ROUTES POUR TRANSPORTEURS
// ============================================
Route::middleware(['auth', 'role:transporteur'])->prefix('transporteur')->name('transporteur.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('transporteur.dashboard');
    })->name('dashboard');
    
    // Gestion des devis (reçus)
    Route::get('/mes-devis', [TransporteurController::class, 'mesDevis'])->name('mes-devis');
    Route::put('/devis/{id}/repondre', [TransporteurController::class, 'repondreDevis'])->name('repondre-devis');
    
    // Gestion des véhicules
    Route::resource('/vehicules', App\Http\Controllers\Transporteur\VehiculeController::class);
    
    // Tarifs
    Route::get('/tarifs', [App\Http\Controllers\Transporteur\TarifController::class, 'index'])->name('tarifs');
    Route::put('/tarifs', [App\Http\Controllers\Transporteur\TarifController::class, 'update'])->name('tarifs.update');
});

// ============================================
// GROUPE DES ROUTES POUR ADMINISTRATEURS
// ============================================
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    
    // Gestion des annonces
    Route::resource('annonces', AnnonceAdminController::class);
    
    // Gestion des transactions
    Route::resource('transactions', TransactionAdminController::class);
    
    // Gestion des paramètres
    Route::get('/parametres', [ParametreController::class, 'edit'])->name('parametres.edit');
    Route::put('/parametres', [ParametreController::class, 'update'])->name('parametres.update');
    
    // Gestion des catégories
    Route::resource('categories', App\Http\Controllers\Admin\CategorieController::class);
    
    // Gestion des rapports
    Route::get('/rapports', [App\Http\Controllers\Admin\RapportController::class, 'index'])->name('rapports');
    Route::get('/rapports/export', [App\Http\Controllers\Admin\RapportController::class, 'export'])->name('rapports.export');
});

// ============================================
// GROUPE DES ROUTES POUR SUPER ADMINISTRATEURS
// ============================================
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super.admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('super-admin.dashboard');
    })->name('dashboard');
    
    // Gestion des administrateurs
    Route::resource('admins', App\Http\Controllers\SuperAdmin\AdminController::class);
    
    // Gestion des logs
    Route::get('/logs', [App\Http\Controllers\SuperAdmin\LogController::class, 'index'])->name('logs');
    Route::get('/logs/{id}', [App\Http\Controllers\SuperAdmin\LogController::class, 'show'])->name('logs.show');
    
    // Gestion des backups
    Route::get('/backups', [App\Http\Controllers\SuperAdmin\BackupController::class, 'index'])->name('backups');
    Route::post('/backups', [App\Http\Controllers\SuperAdmin\BackupController::class, 'store'])->name('backups.store');
    Route::delete('/backups/{filename}', [App\Http\Controllers\SuperAdmin\BackupController::class, 'destroy'])->name('backups.destroy');
    
    // Configuration système
    Route::get('/configuration', [App\Http\Controllers\SuperAdmin\ConfigController::class, 'index'])->name('configuration');
    Route::put('/configuration', [App\Http\Controllers\SuperAdmin\ConfigController::class, 'update'])->name('configuration.update');
});

// ============================================
// ROUTES ACCESSIBLES À PLUSIEURS RÔLES
// ============================================

// Calendrier professionnel (éleveurs, vétérinaires, transporteurs)
Route::middleware(['auth', 'role:eleveur,veterinaire,transporteur'])->prefix('professionnel')->name('professionnel.')->group(function () {
    Route::get('/calendrier', [App\Http\Controllers\CalendrierController::class, 'index'])->name('calendrier');
    Route::get('/statistiques', [App\Http\Controllers\StatistiqueController::class, 'index'])->name('statistiques');
});

// Inclusion des routes d'authentification
require __DIR__.'/auth.php';