<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransporteurController extends Controller
{
    /**
     * Afficher la liste des transporteurs
     */
    public function index()
    {
        $transporteurs = User::with(['transporteur'])
            ->whereHas('typeUser', function($query) {
                $query->where('type', 'transporteur');
            })
            ->where('statut', 'actif')
            ->latest()
            ->paginate(12);

        return view('services.transporteurs.index', compact('transporteurs'));
    }

    /**
     * Afficher les détails d'un transporteur
     */
    public function show($id)
    {
        $transporteur = User::with(['transporteur'])
            ->whereHas('typeUser', function($query) {
                $query->where('type', 'transporteur');
            })
            ->where('statut', 'actif')
            ->findOrFail($id);

        // Autres transporteurs dans la même zone
        $autresTransporteurs = User::with(['transporteur'])
            ->whereHas('typeUser', function($query) {
                $query->where('type', 'transporteur');
            })
            ->where('statut', 'actif')
            ->where('id', '!=', $id)
            ->where('departement', $transporteur->departement)
            ->latest()
            ->take(6)
            ->get();

        return view('services.transporteurs.show', compact('transporteur', 'autresTransporteurs'));
    }

    /**
     * Afficher le formulaire de demande de devis
     */
    public function demanderDevis($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour demander un devis.');
        }

        $transporteur = User::with(['transporteur'])
            ->whereHas('typeUser', function($query) {
                $query->where('type', 'transporteur');
            })
            ->findOrFail($id);

        return view('services.transporteurs.devis', compact('transporteur'));
    }

    /**
     * Enregistrer une demande de devis
     */
    public function storeDevis(Request $request)
    {
        $request->validate([
            'id_transporteur' => 'required|exists:users,id',
            'description' => 'required|string',
            'date_souhaitee' => 'required|date|after:now',
            'poids_estime' => 'required|numeric|min:0',
            'adresse_depart' => 'required|string|max:255',
            'adresse_arrivee' => 'required|string|max:255',
        ]);

        // Vérifier que le transporteur existe bien
        $transporteur = User::whereHas('typeUser', function($query) {
            $query->where('type', 'transporteur');
        })->findOrFail($request->id_transporteur);

        // TODO: Créer le modèle Devis et enregistrer
        // Pour l'instant, rediriger avec un message de succès

        return redirect()->route('transporteurs.show', $request->id_transporteur)
            ->with('success', 'Votre demande de devis a été envoyée avec succès.');
    }

    /**
     * Afficher les devis du transporteur connecté
     */
    public function mesDevis()
    {
        // TODO: Implémenter la récupération des devis
        $devis = collect([]); // Temporaire

        return view('services.transporteurs.mes-devis', compact('devis'));
    }

    /**
     * Répondre à un devis
     */
    public function repondreDevis(Request $request, $id)
    {
        // TODO: Implémenter la réponse à un devis
        
        return redirect()->route('transporteur.mes-devis')
            ->with('success', 'Votre réponse a été envoyée.');
    }
}