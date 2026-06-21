<?php

namespace App\Http\Controllers\Annonce;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Animal;
use App\Models\Race;
use App\Models\Espece;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnimalAnnonceController extends Controller
{
    /**
     * Afficher la liste des annonces d'animaux avec recherche et filtres
     */
    public function index(Request $request)
    {
        // Requête de base - comme dans HomeController
        $query = Annonce::with(['auteur', 'animal.race', 'animal.race.espece', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_animal');

        // Filtre par recherche (nom, description, etc.)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
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
                    });
            });
        }

        // Filtre par espèce
        if ($request->filled('espece')) {
            $query->whereHas('animal.race.espece', function($q) use ($request) {
                $q->where('id', $request->espece);
            });
        }

        // Filtre par race
        if ($request->filled('race')) {
            $query->whereHas('animal', function($q) use ($request) {
                $q->where('id_race', $request->race);
            });
        }

        // Filtre par sexe
        if ($request->filled('sexe')) {
            $query->whereHas('animal', function($q) use ($request) {
                $q->where('sexe', $request->sexe);
            });
        }

        // Filtre par fournisseur (vendeur)
        if ($request->filled('vendeur')) {
            $query->where('id_user', $request->vendeur);
        }

        // Filtre par fournisseur (recherche par nom)
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

        // Données pour les filtres - comme dans HomeController
        $especes = Espece::where('statut', 'actif')->orderBy('nom')->get();
        $races = Race::where('statut', 'actif')->orderBy('nom')->get();
        
        // Liste des vendeurs qui ont des annonces d'animaux actives
        $vendeurs = User::whereHas('annonces', function($q) {
            $q->where('statut', 'active')
              ->whereNotNull('id_animal');
        })->orderBy('nom')->get();

        return view('annonces.animaux.index', compact('annonces', 'especes', 'races', 'vendeurs'));
    }

    /**
     * Afficher les détails d'une annonce d'animal
     */
    public function show($id)
    {
        $annonce = Annonce::with(['auteur', 'animal.race', 'animal.race.espece', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_animal')
            ->findOrFail($id);

        // Annonces similaires (même race)
        $annoncesSimilaires = Annonce::with(['auteur', 'animal.race', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_animal')
            ->where('id', '!=', $id)
            ->whereHas('animal', function($q) use ($annonce) {
                $q->where('id_race', $annonce->animal->id_race);
            })
            ->latest()
            ->take(6)
            ->get();

        // Si pas assez d'annonces de la même race, prendre d'autres annonces d'animaux
        if ($annoncesSimilaires->count() < 6) {
            $additional = Annonce::with(['auteur', 'animal.race', 'piecesJointes'])
                ->where('statut', 'active')
                ->whereNotNull('id_animal')
                ->where('id', '!=', $id)
                ->whereNotIn('id', $annoncesSimilaires->pluck('id')->toArray())
                ->latest()
                ->take(6 - $annoncesSimilaires->count())
                ->get();
            
            $annoncesSimilaires = $annoncesSimilaires->merge($additional);
        }

        return view('annonces.animaux.show', compact('annonce', 'annoncesSimilaires'));
    }

    /**
     * Afficher le formulaire de création d'annonce d'animal
     */
    public function create()
    {
        $races = Race::where('statut', 'actif')->orderBy('label')->get();
        $animaux = Animal::where('id_user', Auth::id())
            ->where('statut', 'disponible')
            ->get();
            
        return view('annonces.animaux.create', compact('races', 'animaux'));
    }

    /**
     * Enregistrer une nouvelle annonce d'animal
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_animal' => 'required|exists:animaux,id',
            'quantite' => 'nullable|numeric|min:0',
            'prix' => 'required|numeric|min:0',
        ]);

        // Vérifier que l'animal appartient à l'utilisateur connecté
        $animal = Animal::where('id', $request->id_animal)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $annonce = Annonce::create([
            'id_user' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'type' => 'animal',
            'id_animal' => $request->id_animal,
            'id_escrement' => null,
            'id_nourriture' => null,
            'id_accessoire' => null,
            'quantite' => $request->quantite ?? 1,
            'prix' => $request->prix,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('annonce.animal.show', $annonce->id)
            ->with('success', 'Votre annonce a été créée avec succès et est en attente de validation.');
    }

    /**
     * Afficher le formulaire d'édition d'une annonce d'animal
     */
    public function edit($id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_animal')
            ->findOrFail($id);

        $races = Race::where('statut', 'actif')->orderBy('label')->get();
        $animaux = Animal::where('id_user', Auth::id())
            ->where('statut', 'disponible')
            ->get();

        return view('annonces.animaux.edit', compact('annonce', 'races', 'animaux'));
    }

    /**
     * Mettre à jour une annonce d'animal
     */
    public function update(Request $request, $id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_animal')
            ->findOrFail($id);

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_animal' => 'required|exists:animaux,id',
            'quantite' => 'nullable|numeric|min:0',
            'prix' => 'required|numeric|min:0',
        ]);

        // Vérifier que l'animal appartient à l'utilisateur
        Animal::where('id', $request->id_animal)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $annonce->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'id_animal' => $request->id_animal,
            'quantite' => $request->quantite ?? 1,
            'prix' => $request->prix,
        ]);

        return redirect()->route('annonce.animal.show', $annonce->id)
            ->with('success', 'Votre annonce a été mise à jour avec succès.');
    }

    /**
     * Supprimer une annonce d'animal
     */
    public function destroy($id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_animal')
            ->findOrFail($id);

        $annonce->delete();

        return redirect()->route('annonce.animal.index')
            ->with('success', 'Votre annonce a été supprimée avec succès.');
    }

    /**
     * Afficher les annonces de l'utilisateur connecté
     */
    public function mesAnnonces()
    {
        $annonces = Annonce::with(['animal.race', 'piecesJointes'])
            ->where('id_user', Auth::id())
            ->whereNotNull('id_animal')
            ->latest()
            ->paginate(10);

        return view('annonces.animaux.mes-annonces', compact('annonces'));
    }

    /**
     * Afficher les animaux de l'utilisateur connecté
     */
    public function mesAnimaux()
    {
        $animaux = Animal::where('id_user', Auth::id())
            ->with(['race', 'annonce'])
            ->latest()
            ->paginate(10);

        return view('annonces.animaux.mes-animaux', compact('animaux'));
    }

    /**
     * API pour obtenir les races d'une espèce (pour les formulaires dynamiques)
     */
    public function getRacesByEspece($especeId)
    {
        $races = Race::where('id_espece', $especeId)
            ->where('statut', 'actif')
            ->orderBy('nom')
            ->get(['id', 'nom', 'label']);
        
        return response()->json($races);
    }

    /**
     * API pour obtenir les animaux d'un utilisateur (pour les formulaires)
     */
    public function getAnimauxByUser()
    {
        $animaux = Animal::where('id_user', Auth::id())
            ->where('statut', 'disponible')
            ->with(['race', 'race.espece'])
            ->orderBy('nom')
            ->get(['id', 'nom', 'id_race', 'sexe', 'age_mois']);
        
        return response()->json($animaux);
    }
}