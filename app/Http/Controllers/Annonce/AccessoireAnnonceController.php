<?php

namespace App\Http\Controllers\Annonce;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Accessoire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessoireAnnonceController extends Controller
{
    /**
     * Afficher la liste des annonces d'accessoires
     */
    public function index()
    {
        $annonces = Annonce::with(['auteur', 'accessoire', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_accessoire')
            ->latest()
            ->paginate(12);

        return view('annonces.accessoires.index', compact('annonces'));
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
            ->where(function($query) use ($annonce) {
                if ($annonce->accessoire && $annonce->accessoire->categorie) {
                    $query->whereHas('accessoire', function($q) use ($annonce) {
                        $q->where('categorie', $annonce->accessoire->categorie);
                    });
                }
            })
            ->latest()
            ->take(6)
            ->get();

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

        return redirect()->route('annonces.accessoires.show', $annonce->id)
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

        return redirect()->route('annonces.accessoires.show', $annonce->id)
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

        return redirect()->route('annonces.accessoires.index')
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