<?php

namespace App\Http\Controllers\Annonce;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\User;
use App\Models\Espece;
use App\Models\Race;
use Illuminate\Http\Request;

class AllAnnonceController extends Controller
{
    /**
     * Afficher la liste de toutes les annonces avec recherche et filtres
     */
    public function index(Request $request)
    {
        // Requête de base - toutes les annonces actives
        $query = Annonce::with(['auteur', 'animal.race', 'animal.race.espece', 'nourriture', 'accessoire', 'escrement.espece', 'piecesJointes'])
            ->where('statut', 'active');

        // Filtre par recherche (titre, description, etc.)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    // Recherche dans les relations
                    ->orWhereHas('animal', function($q2) use ($search) {
                        $q2->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('animal.race', function($q2) use ($search) {
                        $q2->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('label', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('animal.race.espece', function($q2) use ($search) {
                        $q2->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('label', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('nourriture', function($q2) use ($search) {
                        $q2->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('type', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('accessoire', function($q2) use ($search) {
                        $q2->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('categorie', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('escrement', function($q2) use ($search) {
                        $q2->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('escrement.espece', function($q2) use ($search) {
                        $q2->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('label', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filtre par type d'annonce
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par catégorie (pour les accessoires)
        if ($request->filled('categorie')) {
            $query->whereHas('accessoire', function($q) use ($request) {
                $q->where('categorie', $request->categorie);
            });
        }

        // Filtre par espèce (pour animaux et escrements)
        if ($request->filled('espece')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('animal.race.espece', function($q2) use ($request) {
                    $q2->where('id', $request->espece);
                })->orWhereHas('escrement.espece', function($q2) use ($request) {
                    $q2->where('id', $request->espece);
                });
            });
        }

        // Filtre par race (pour les animaux)
        if ($request->filled('race')) {
            $query->whereHas('animal', function($q) use ($request) {
                $q->where('id_race', $request->race);
            });
        }

        // Filtre par type d'aliment
        if ($request->filled('type_aliment')) {
            $query->whereHas('nourriture', function($q) use ($request) {
                $q->where('type', $request->type_aliment);
            });
        }

        // Filtre par vendeur
        if ($request->filled('vendeur')) {
            $query->where('id_user', $request->vendeur);
        }

        // Filtre par vendeur (recherche par nom)
        if ($request->filled('vendeur_nom')) {
            $vendeurNom = $request->vendeur_nom;
            $query->whereHas('auteur', function($q) use ($vendeurNom) {
                $q->where('nom', 'LIKE', "%{$vendeurNom}%")
                    ->orWhere('prenom', 'LIKE', "%{$vendeurNom}%")
                    ->orWhereRaw("CONCAT(prenom, ' ', nom) LIKE ?", ["%{$vendeurNom}%"]);
            });
        }

        // Filtre par prix min
        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }

        // Filtre par prix max
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Filtre par date (dernières 24h, 7 jours, 30 jours)
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->subDays(7), now()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->subDays(30), now()]);
                    break;
            }
        }

        // Tri
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'prix_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'ancien':
                $query->orderBy('created_at', 'asc');
                break;
            case 'recent':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $annonces = $query->paginate(12)->withQueryString();

        // Données pour les filtres
        $types = [
            'animal' => 'Animaux',
            'nourriture' => 'Aliments / Provendes',
            'accessoire' => 'Accessoires',
            'escrement' => 'Escrements / Fumier'
        ];

        $categories = \App\Models\Accessoire::select('categorie')->distinct()->pluck('categorie');
        $especes = Espece::where('statut', 'actif')->orderBy('nom')->get();
        $races = Race::where('statut', 'actif')->orderBy('nom')->get();
        $typesAliments = \App\Models\Nourriture::select('type')->distinct()->pluck('type');
        
        // Liste des vendeurs qui ont des annonces actives
        $vendeurs = User::whereHas('annonces', function($q) {
            $q->where('statut', 'active');
        })->orderBy('nom')->get();

        return view('annonces.index', compact(
            'annonces', 
            'types', 
            'categories', 
            'especes', 
            'races', 
            'typesAliments', 
            'vendeurs'
        ));
    }

    /**
     * Afficher les détails d'une annonce (redirige vers le type spécifique)
     */
    public function show($id)
    {
        $annonce = Annonce::with(['auteur', 'animal.race', 'animal.race.espece', 'nourriture', 'accessoire', 'escrement.espece', 'piecesJointes'])
            ->where('statut', 'active')
            ->findOrFail($id);

        // Rediriger vers la vue spécifique selon le type
        switch ($annonce->type) {
            case 'animal':
                return redirect()->route('annonce.animal.show', $annonce->id);
            case 'nourriture':
                return redirect()->route('annonce.aliment.show', $annonce->id);
            case 'accessoire':
                return redirect()->route('annonce.accessoire.show', $annonce->id);
            case 'escrement':
                return redirect()->route('annonce.escrement.show', $annonce->id);
            default:
                return view('annonces.show', compact('annonce'));
        }
    }
}