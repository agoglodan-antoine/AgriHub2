<?php

namespace App\Http\Controllers\Annonce;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Escrement;
use App\Models\Espece;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EscrementAnnonceController extends Controller
{
    /**
     * Afficher la liste des annonces d'escrements avec recherche et filtres
     */
    public function index(Request $request)
    {
        // Requête de base - comme dans HomeController
        $query = Annonce::with(['auteur', 'escrement.espece', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_escrement');

        // Filtre par recherche (nom, description, etc.)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
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

        // Filtre par espèce
        if ($request->filled('espece')) {
            $query->whereHas('escrement', function($q) use ($request) {
                $q->where('id_espece', $request->espece);
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

        // Données pour les filtres
        $especes = Espece::where('statut', 'actif')->orderBy('nom')->get();
        
        // Liste des vendeurs qui ont des annonces d'escrements actives
        $vendeurs = User::whereHas('annonces', function($q) {
            $q->where('statut', 'active')
              ->whereNotNull('id_escrement');
        })->orderBy('nom')->get();

        return view('annonces.escrements.index', compact('annonces', 'especes', 'vendeurs'));
    }

    /**
     * Afficher les détails d'une annonce d'escrement
     */
    public function show($id)
    {
        $annonce = Annonce::with(['auteur', 'escrement.espece', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_escrement')
            ->findOrFail($id);

        // Annonces similaires (même espèce)
        $annoncesSimilaires = Annonce::with(['auteur', 'escrement.espece', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_escrement')
            ->where('id', '!=', $id)
            ->whereHas('escrement', function($q) use ($annonce) {
                $q->where('id_espece', $annonce->escrement->id_espece);
            })
            ->latest()
            ->take(6)
            ->get();

        // Si pas assez d'annonces de la même espèce, prendre d'autres annonces d'escrements
        if ($annoncesSimilaires->count() < 6) {
            $additional = Annonce::with(['auteur', 'escrement.espece', 'piecesJointes'])
                ->where('statut', 'active')
                ->whereNotNull('id_escrement')
                ->where('id', '!=', $id)
                ->whereNotIn('id', $annoncesSimilaires->pluck('id')->toArray())
                ->latest()
                ->take(6 - $annoncesSimilaires->count())
                ->get();
            
            $annoncesSimilaires = $annoncesSimilaires->merge($additional);
        }

        return view('annonces.escrements.show', compact('annonce', 'annoncesSimilaires'));
    }

    /**
     * Afficher le formulaire de création d'annonce d'escrement
     */
    public function create()
    {
        $especes = Espece::where('statut', 'actif')->orderBy('label')->get();
        $escrements = Escrement::where('id_user', Auth::id())->get();
        return view('annonces.escrements.create', compact('especes', 'escrements'));
    }

    /**
     * Enregistrer une nouvelle annonce d'escrement
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_escrement' => 'required|exists:escrement,id',
            'quantite' => 'nullable|numeric|min:0',
            'prix' => 'required|numeric|min:0',
        ]);

        // Vérifier que l'escrement appartient à l'utilisateur connecté
        Escrement::where('id', $request->id_escrement)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $annonce = Annonce::create([
            'id_user' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'type' => 'escrement',
            'id_animal' => null,
            'id_escrement' => $request->id_escrement,
            'id_nourriture' => null,
            'id_accessoire' => null,
            'quantite' => $request->quantite ?? 1,
            'prix' => $request->prix,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('annonce.escrement.show', $annonce->id)
            ->with('success', 'Votre annonce a été créée avec succès et est en attente de validation.');
    }

    /**
     * Afficher le formulaire d'édition d'une annonce d'escrement
     */
    public function edit($id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_escrement')
            ->findOrFail($id);

        $especes = Espece::where('statut', 'actif')->orderBy('label')->get();
        $escrements = Escrement::where('id_user', Auth::id())->get();

        return view('annonces.escrements.edit', compact('annonce', 'especes', 'escrements'));
    }

    /**
     * Mettre à jour une annonce d'escrement
     */
    public function update(Request $request, $id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_escrement')
            ->findOrFail($id);

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_escrement' => 'required|exists:escrement,id',
            'quantite' => 'nullable|numeric|min:0',
            'prix' => 'required|numeric|min:0',
        ]);

        Escrement::where('id', $request->id_escrement)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $annonce->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'id_escrement' => $request->id_escrement,
            'quantite' => $request->quantite ?? 1,
            'prix' => $request->prix,
        ]);

        return redirect()->route('annonce.escrement.show', $annonce->id)
            ->with('success', 'Votre annonce a été mise à jour avec succès.');
    }

    /**
     * Supprimer une annonce d'escrement
     */
    public function destroy($id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_escrement')
            ->findOrFail($id);

        $annonce->delete();

        return redirect()->route('annonce.escrement.index')
            ->with('success', 'Votre annonce a été supprimée avec succès.');
    }

    /**
     * Afficher les annonces de l'utilisateur connecté
     */
    public function mesAnnonces()
    {
        $annonces = Annonce::with(['escrement.espece', 'piecesJointes'])
            ->where('id_user', Auth::id())
            ->whereNotNull('id_escrement')
            ->latest()
            ->paginate(10);

        return view('annonces.escrements.mes-annonces', compact('annonces'));
    }
}