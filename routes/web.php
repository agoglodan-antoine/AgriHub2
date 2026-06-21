<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Annonce\AnimalAnnonceController;
use App\Http\Controllers\Annonce\EscrementAnnonceController;
use App\Http\Controllers\Annonce\AlimentAnnonceController;
use App\Http\Controllers\Annonce\AccessoireAnnonceController;
use App\Http\Controllers\ServiceVeterinaireController;
use App\Http\Controllers\TransporteurController;
use App\Http\Controllers\RecompenseController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnnonceAdminController;
use App\Http\Controllers\Admin\PaiementAdminController;
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
    
    // Route pour récupérer les infos d'une annonce (AJAX)
    Route::get('/{id}/info', [MessageController::class, 'getAnnonceInfo'])->name('info');
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

// Inclusion des routes d'authentification
require __DIR__.'/auth.php';