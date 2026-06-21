<?php

namespace App\Http\Controllers\Annonce;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Accessoire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessoireAnnonceController extends Controller
{
    /**
     * Afficher la liste des annonces d'accessoires avec recherche et filtres
     */
    public function index(Request $request)
    {
        // Requête de base - comme dans HomeController
        $query = Annonce::with(['auteur', 'accessoire', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_accessoire');

        // Filtre par recherche (nom, description, etc.)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereHas('accessoire', function($q2) use ($search) {
                        $q2->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('categorie', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filtre par catégorie d'accessoire
        if ($request->filled('categorie')) {
            $query->whereHas('accessoire', function($q) use ($request) {
                $q->where('categorie', $request->categorie);
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
        $categories = Accessoire::select('categorie')->distinct()->pluck('categorie');
        
        // Liste des vendeurs qui ont des annonces d'accessoires actives
        $vendeurs = User::whereHas('annonces', function($q) {
            $q->where('statut', 'active')
              ->whereNotNull('id_accessoire');
        })->orderBy('nom')->get();

        return view('annonces.accessoires.index', compact('annonces', 'categories', 'vendeurs'));
    }

    /**
     * Afficher les détails d'une annonce d'accessoire
     */
    public function show($id)
    {
        $annonce = Annonce::with(['auteur', 'accessoire', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_accessoire')
            ->findOrFail($id);

        // Annonces similaires (même catégorie)
        $annoncesSimilaires = Annonce::with(['auteur', 'accessoire', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_accessoire')
            ->where('id', '!=', $id)
            ->whereHas('accessoire', function($q) use ($annonce) {
                $q->where('categorie', $annonce->accessoire->categorie);
            })
            ->latest()
            ->take(6)
            ->get();

        // Si pas assez d'annonces de la même catégorie, prendre d'autres annonces d'accessoires
        if ($annoncesSimilaires->count() < 6) {
            $additional = Annonce::with(['auteur', 'accessoire', 'piecesJointes'])
                ->where('statut', 'active')
                ->whereNotNull('id_accessoire')
                ->where('id', '!=', $id)
                ->whereNotIn('id', $annoncesSimilaires->pluck('id')->toArray())
                ->latest()
                ->take(6 - $annoncesSimilaires->count())
                ->get();
            
            $annoncesSimilaires = $annoncesSimilaires->merge($additional);
        }

        return view('annonces.accessoires.show', compact('annonce', 'annoncesSimilaires'));
    }

    /**
     * Afficher le formulaire de création d'annonce d'accessoire
     */
    public function create()
    {
        $categories = [
            'Alimentation',
            'Hydratation',
            'Enclos',
            'Reproduction',
            'Équipement',
            'Bien-être animal',
            'Transport',
            'Hygiène',
            'Autre'
        ];
        
        $accessoires = Accessoire::where('id_user', Auth::id())->get();
        return view('annonces.accessoires.create', compact('categories', 'accessoires'));
    }

    /**
     * Enregistrer une nouvelle annonce d'accessoire
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_accessoire' => 'required|exists:accessoire,id',
            'quantite' => 'nullable|numeric|min:0',
            'prix' => 'required|numeric|min:0',
        ]);

        // Vérifier que l'accessoire appartient à l'utilisateur connecté
        Accessoire::where('id', $request->id_accessoire)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $annonce = Annonce::create([
            'id_user' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'type' => 'accessoire',
            'id_animal' => null,
            'id_escrement' => null,
            'id_nourriture' => null,
            'id_accessoire' => $request->id_accessoire,
            'quantite' => $request->quantite ?? 1,
            'prix' => $request->prix,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('annonce.accessoire.show', $annonce->id)
            ->with('success', 'Votre annonce a été créée avec succès et est en attente de validation.');
    }

    /**
     * Afficher le formulaire d'édition d'une annonce d'accessoire
     */
    public function edit($id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_accessoire')
            ->findOrFail($id);

        $categories = [
            'Alimentation',
            'Hydratation',
            'Enclos',
            'Reproduction',
            'Équipement',
            'Bien-être animal',
            'Transport',
            'Hygiène',
            'Autre'
        ];
        
        $accessoires = Accessoire::where('id_user', Auth::id())->get();

        return view('annonces.accessoires.edit', compact('annonce', 'categories', 'accessoires'));
    }

    /**
     * Mettre à jour une annonce d'accessoire
     */
    public function update(Request $request, $id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_accessoire')
            ->findOrFail($id);

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_accessoire' => 'required|exists:accessoire,id',
            'quantite' => 'nullable|numeric|min:0',
            'prix' => 'required|numeric|min:0',
        ]);

        Accessoire::where('id', $request->id_accessoire)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $annonce->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'id_accessoire' => $request->id_accessoire,
            'quantite' => $request->quantite ?? 1,
            'prix' => $request->prix,
        ]);

        return redirect()->route('annonce.accessoire.show', $annonce->id)
            ->with('success', 'Votre annonce a été mise à jour avec succès.');
    }

    /**
     * Supprimer une annonce d'accessoire
     */
    public function destroy($id)
    {
        $annonce = Annonce::where('id_user', Auth::id())
            ->whereNotNull('id_accessoire')
            ->findOrFail($id);

        $annonce->delete();

        return redirect()->route('annonce.accessoire.index')
            ->with('success', 'Votre annonce a été supprimée avec succès.');
    }

    /**
     * Afficher les annonces de l'utilisateur connecté
     */
    public function mesAnnonces()
    {
        $annonces = Annonce::with(['accessoire', 'piecesJointes'])
            ->where('id_user', Auth::id())
            ->whereNotNull('id_accessoire')
            ->latest()
            ->paginate(10);

        return view('annonces.accessoires.mes-annonces', compact('annonces'));
    }

    /**
     * Afficher les produits de l'utilisateur connecté
     */
    public function mesProduits()
    {
        $accessoires = Accessoire::where('id_user', Auth::id())
            ->with(['annonce'])
            ->latest()
            ->paginate(10);

        return view('annonces.accessoires.mes-produits', compact('accessoires'));
    }
}