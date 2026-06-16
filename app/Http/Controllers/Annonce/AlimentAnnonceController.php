<?php

namespace App\Http\Controllers\Annonce;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Nourriture;
use App\Models\Espece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlimentAnnonceController extends Controller
{
    /**
     * Afficher la liste des annonces d'aliments
     */
    public function index()
    {
        $annonces = Annonce::with(['auteur', 'nourriture', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_nourriture')
            ->latest()
            ->paginate(12);

        return view('annonces.aliments.index', compact('annonces'));
    }

    /**
     * Afficher les détails d'une annonce d'aliment
     */
    public function show($id)
    {
        $annonce = Annonce::with(['auteur', 'nourriture', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_nourriture')
            ->findOrFail($id);

        // Annonces similaires (même type d'aliment)
        $annoncesSimilaires = Annonce::with(['auteur', 'nourriture', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_nourriture')
            ->where('id', '!=', $id)
            ->where(function($query) use ($annonce) {
                if ($annonce->nourriture) {
                    $query->whereHas('nourriture', function($q) use ($annonce) {
                        $q->where('type', $annonce->nourriture->type);
                    });
                }
            })
            ->latest()
            ->take(6)
            ->get();

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

        return redirect()->route('annonces.aliments.show', $annonce->id)
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

        return redirect()->route('annonces.aliments.show', $annonce->id)
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

        return redirect()->route('annonces.aliments.index')
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