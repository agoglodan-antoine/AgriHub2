<?php

namespace App\Http\Controllers\Annonce;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Escrement;
use App\Models\Espece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EscrementAnnonceController extends Controller
{
    /**
     * Afficher la liste des annonces d'escrements
     */
    public function index()
    {
        $annonces = Annonce::with(['auteur', 'escrement.espece', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_escrement')
            ->latest()
            ->paginate(12);

        return view('annonces.escrements.index', compact('annonces'));
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
        $annoncesSimilaires = Annonce::with(['auteur', 'escrement', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_escrement')
            ->where('id', '!=', $id)
            ->where(function($query) use ($annonce) {
                if ($annonce->escrement && $annonce->escrement->id_espece) {
                    $query->whereHas('escrement', function($q) use ($annonce) {
                        $q->where('id_espece', $annonce->escrement->id_espece);
                    });
                }
            })
            ->latest()
            ->take(6)
            ->get();

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

        return redirect()->route('annonces.escrements.show', $annonce->id)
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

        return redirect()->route('annonces.escrements.show', $annonce->id)
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

        return redirect()->route('annonces.escrements.index')
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