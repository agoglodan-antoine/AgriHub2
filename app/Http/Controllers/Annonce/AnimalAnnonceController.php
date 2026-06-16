<?php

namespace App\Http\Controllers\Annonce;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Animal;
use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnimalAnnonceController extends Controller
{
    /**
     * Afficher la liste des annonces d'animaux
     */
    public function index()
    {
        $annonces = Annonce::with(['auteur', 'animal.race', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_animal')
            ->latest()
            ->paginate(12);

        return view('annonces.animaux.index', compact('annonces'));
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

        // Annonces similaires (même race ou même type)
        $annoncesSimilaires = Annonce::with(['auteur', 'animal', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_animal')
            ->where('id', '!=', $id)
            ->where(function($query) use ($annonce) {
                if ($annonce->animal && $annonce->animal->id_race) {
                    $query->whereHas('animal', function($q) use ($annonce) {
                        $q->where('id_race', $annonce->animal->id_race);
                    });
                }
            })
            ->latest()
            ->take(6)
            ->get();

        return view('annonces.animaux.show', compact('annonce', 'annoncesSimilaires'));
    }

    /**
     * Afficher le formulaire de création d'annonce d'animal
     */
    public function create()
    {
        $races = Race::where('statut', 'actif')->orderBy('label')->get();
        return view('annonces.animaux.create', compact('races'));
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

        return redirect()->route('annonces.animaux.show', $annonce->id)
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

        return redirect()->route('annonces.animaux.show', $annonce->id)
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

        return redirect()->route('annonces.animaux.index')
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
}