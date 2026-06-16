<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceVeterinaireController extends Controller
{
    /**
     * Afficher la liste des vétérinaires
     */
    public function index()
    {
        $veterinaires = User::with(['veterinaire'])
            ->whereHas('typeUser', function($query) {
                $query->where('type', 'veterinaire');
            })
            ->where('statut', 'actif')
            ->latest()
            ->paginate(12);

        return view('services.veterinaires.index', compact('veterinaires'));
    }

    /**
     * Afficher les détails d'un vétérinaire
     */
    public function show($id)
    {
        $veterinaire = User::with(['veterinaire', 'rendezVousVeto'])
            ->whereHas('typeUser', function($query) {
                $query->where('type', 'veterinaire');
            })
            ->where('statut', 'actif')
            ->findOrFail($id);

        // Autres vétérinaires dans la même zone
        $autresVeterinaires = User::with(['veterinaire'])
            ->whereHas('typeUser', function($query) {
                $query->where('type', 'veterinaire');
            })
            ->where('statut', 'actif')
            ->where('id', '!=', $id)
            ->where('departement', $veterinaire->departement)
            ->latest()
            ->take(6)
            ->get();

        return view('services.veterinaires.show', compact('veterinaire', 'autresVeterinaires'));
    }

    /**
     * Afficher le formulaire de prise de rendez-vous
     */
    public function prendreRendezVous($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour prendre un rendez-vous.');
        }

        $veterinaire = User::with(['veterinaire'])
            ->whereHas('typeUser', function($query) {
                $query->where('type', 'veterinaire');
            })
            ->findOrFail($id);

        return view('services.veterinaires.rendez-vous', compact('veterinaire'));
    }

    /**
     * Enregistrer un rendez-vous
     */
    public function storeRendezVous(Request $request)
    {
        $request->validate([
            'id_veterinaire' => 'required|exists:users,id',
            'sujet' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_prevue' => 'required|date|after:now',
        ]);

        // Vérifier que le vétérinaire existe bien
        $veterinaire = User::whereHas('typeUser', function($query) {
            $query->where('type', 'veterinaire');
        })->findOrFail($request->id_veterinaire);

        // Créer le rendez-vous
        $rendezVous = \App\Models\RendezVous::create([
            'id_veterinaire' => $request->id_veterinaire,
            'id_client' => Auth::id(),
            'sujet' => $request->sujet,
            'description' => $request->description,
            'date_prevue' => $request->date_prevue,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('services-veterinaires.show', $request->id_veterinaire)
            ->with('success', 'Votre rendez-vous a été demandé avec succès.');
    }

    /**
     * Afficher les rendez-vous du vétérinaire connecté
     */
    public function mesRendezVous()
    {
        $user = Auth::user();
        
        if ($user->isVeterinaire()) {
            // Pour les vétérinaires : rendez-vous reçus
            $rendezVous = \App\Models\RendezVous::with(['client'])
                ->where('id_veterinaire', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            // Pour les clients : rendez-vous demandés
            $rendezVous = \App\Models\RendezVous::with(['veterinaire'])
                ->where('id_client', $user->id)
                ->latest()
                ->paginate(10);
        }

        return view('services.veterinaires.mes-rendez-vous', compact('rendezVous'));
    }

    /**
     * Mettre à jour le statut d'un rendez-vous
     */
    public function updateStatut(Request $request, $id)
    {
        $rendezVous = \App\Models\RendezVous::where('id_veterinaire', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'statut' => 'required|in:confirme,realise,annule',
        ]);

        $rendezVous->update([
            'statut' => $request->statut,
        ]);

        return redirect()->route('veterinaire.mes-rendez-vous')
            ->with('success', 'Statut du rendez-vous mis à jour.');
    }
}