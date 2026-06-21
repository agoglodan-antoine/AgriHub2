<?php

namespace App\Http\Controllers\Annonce;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Nourriture;
use App\Models\Espece;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlimentAnnonceController extends Controller
{
    /**
     * Afficher la liste des annonces d'aliments avec recherche et filtres
     */
    public function index(Request $request)
    {
        // Requête de base - comme dans HomeController
        $query = Annonce::with(['auteur', 'nourriture', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_nourriture');

        // Filtre par recherche (nom, description, etc.)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereHas('nourriture', function($q2) use ($search) {
                        $q2->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('type', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filtre par type d'aliment
        if ($request->filled('type_aliment')) {
            $query->whereHas('nourriture', function($q) use ($request) {
                $q->where('type', $request->type_aliment);
            });
        }

        // Filtre par espèce (pour les aliments adaptés à une espèce)
        if ($request->filled('espece')) {
            $query->whereHas('nourriture.especes', function($q) use ($request) {
                $q->where('especes.id', $request->espece);
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
        $typesAliments = Nourriture::select('type')->distinct()->pluck('type');
        $especes = Espece::where('statut', 'actif')->orderBy('nom')->get();
        
        // Liste des vendeurs qui ont des annonces d'aliments actives
        $vendeurs = User::whereHas('annonces', function($q) {
            $q->where('statut', 'active')
              ->whereNotNull('id_nourriture');
        })->orderBy('nom')->get();

        return view('annonces.aliments.index', compact('annonces', 'typesAliments', 'especes', 'vendeurs'));
    }

    /**
     * Afficher les détails d'une annonce d'aliment
     */
    public function show($id)
    {
        $annonce = Annonce::with(['auteur', 'nourriture', 'nourriture.especes', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_nourriture')
            ->findOrFail($id);

        // Annonces similaires (même type d'aliment)
        $annoncesSimilaires = Annonce::with(['auteur', 'nourriture', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_nourriture')
            ->where('id', '!=', $id)
            ->whereHas('nourriture', function($q) use ($annonce) {
                $q->where('type', $annonce->nourriture->type);
            })
            ->latest()
            ->take(6)
            ->get();

        // Si pas assez d'annonces du même type, prendre d'autres annonces d'aliments
        if ($annoncesSimilaires->count() < 6) {
            $additional = Annonce::with(['auteur', 'nourriture', 'piecesJointes'])
                ->where('statut', 'active')
                ->whereNotNull('id_nourriture')
                ->where('id', '!=', $id)
                ->whereNotIn('id', $annoncesSimilaires->pluck('id')->toArray())
                ->latest()
                ->take(6 - $annoncesSimilaires->count())
                ->get();
            
            $annoncesSimilaires = $annoncesSimilaires->merge($additional);
        }

        return view('annonces.aliments.show', compact('annonce', 'annoncesSimilaires'));
    }

    /**
     * Afficher le formulaire de création d'annonce d'aliment
     */
    public function create()
    {
        $especes = Espece::where('statut', 'actif')->orderBy('label')->get();
        $nourritures = Nourriture::where('id_user', Auth::id())->get();
        return view('annonces.aliments.create', compact('especes', 'nourritures'));
    }

    /**
     * Enregistrer une nouvelle annonce d'aliment
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_nourriture' => 'required|exists:nourriture,id',
            'quantite' => 'nullable|numeric|min:0',
            'prix' => 'required|numeric|min:0',
        ]);

        // Vérifier que l'aliment appartient à l'utilisateur connecté
        Nourriture::where('id', $request->id_nourriture)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $annonce = Annonce::create([
            'id_user' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'type' => 'nourriture',
            'id_animal' => null,
            'id_escrement' => null,
            'id_nourriture' => $request->id_nourriture,
            'id_accessoire' => null,
            'quantite' => $request->quantite ?? 1,
            'prix' => $request->prix,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('annonce.aliment.show', $annonce->id)
            ->with('success', 'Votre annonce a été créée avec succès et est en attente de validation.');
    }

    /**
     * Afficher le formulaire d'édition d'une annonce d'aliment
     */
    public function edit($id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_nourriture')
            ->findOrFail($id);

        $especes = Espece::where('statut', 'actif')->orderBy('label')->get();
        $nourritures = Nourriture::where('id_user', Auth::id())->get();

        return view('annonces.aliments.edit', compact('annonce', 'especes', 'nourritures'));
    }

    /**
     * Mettre à jour une annonce d'aliment
     */
    public function update(Request $request, $id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_nourriture')
            ->findOrFail($id);

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_nourriture' => 'required|exists:nourriture,id',
            'quantite' => 'nullable|numeric|min:0',
            'prix' => 'required|numeric|min:0',
        ]);

        Nourriture::where('id', $request->id_nourriture)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $annonce->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'id_nourriture' => $request->id_nourriture,
            'quantite' => $request->quantite ?? 1,
            'prix' => $request->prix,
        ]);

        return redirect()->route('annonce.aliment.show', $annonce->id)
            ->with('success', 'Votre annonce a été mise à jour avec succès.');
    }

    /**
     * Supprimer une annonce d'aliment
     */
    public function destroy($id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_nourriture')
            ->findOrFail($id);

        $annonce->delete();

        return redirect()->route('annonce.aliment.index')
            ->with('success', 'Votre annonce a été supprimée avec succès.');
    }

    /**
     * Afficher les annonces de l'utilisateur connecté
     */
    public function mesAnnonces()
    {
        $annonces = Annonce::with(['nourriture', 'piecesJointes'])
            ->where('id_user', Auth::id())
            ->whereNotNull('id_nourriture')
            ->latest()
            ->paginate(10);

        return view('annonces.aliments.mes-annonces', compact('annonces'));
    }

    /**
     * Afficher les produits de l'utilisateur connecté
     */
    public function mesProduits()
    {
        $nourritures = Nourriture::where('id_user', Auth::id())
            ->with(['annonce'])
            ->latest()
            ->paginate(10);

        return view('annonces.aliments.mes-produits', compact('nourritures'));
    }
}