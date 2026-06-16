<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        // Annonces d'animaux (6 dernières annonces actives)
        $annoncesAnimaux = Annonce::with(['auteur', 'animal.race', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_animal')
            ->latest()
            ->take(6)
            ->get();

        // Annonces de nourriture/provende (6 dernières annonces actives)
        $annoncesNourriture = Annonce::with(['auteur', 'nourriture', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_nourriture')
            ->latest()
            ->take(6)
            ->get();

        // Annonces d'accessoires (6 dernières annonces actives)
        $annoncesAccessoires = Annonce::with(['auteur', 'accessoire', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_accessoire')
            ->latest()
            ->take(6)
            ->get();

        // Annonces d'escrements/fumier (6 dernières annonces actives)
        $annoncesEscrements = Annonce::with(['auteur', 'escrement.espece', 'piecesJointes'])
            ->where('statut', 'active')
            ->whereNotNull('id_escrement')
            ->latest()
            ->take(6)
            ->get();

        // Vétérinaires (6 derniers vétérinaires actifs)
        $veterinaires = User::with(['veterinaire'])
            ->whereHas('typeUser', function($query) {
                $query->where('type', 'veterinaire');
            })
            ->where('statut', 'actif')
            ->latest()
            ->take(6)
            ->get();

        // Transporteurs (6 derniers transporteurs actifs)
        $transporteurs = User::with(['transporteur'])
            ->whereHas('typeUser', function($query) {
                $query->where('type', 'transporteur');
            })
            ->where('statut', 'actif')
            ->latest()
            ->take(6)
            ->get();

        return view('accueil.index', compact(
            'annoncesAnimaux',
            'annoncesNourriture',
            'annoncesAccessoires',
            'annoncesEscrements',
            'veterinaires',
            'transporteurs'
        ));
    }

    /**
     * Recherche globale
     */
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $annonces = Annonce::with(['auteur'])
            ->where('statut', 'active')
            ->where(function($query) use ($search) {
                $query->where('titre', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(12);
        
        return view('accueil.search', compact('annonces', 'search'));
    }
}