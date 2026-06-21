<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transporteur;
use Illuminate\Http\Request;

class TransporteurController extends Controller
{
    /**
     * Afficher la liste des transporteurs avec recherche et filtres
     */
    public function index(Request $request)
    {
        // Requête de base - transporteurs actifs
        $query = User::with(['transporteur', 'typeUser'])
            ->whereHas('typeUser', function($q) {
                $q->where('type', 'transporteur');
            })
            ->where('statut', 'actif');

        // Filtre par recherche (nom, prénom, type de véhicule)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhereRaw("CONCAT(prenom, ' ', nom) LIKE ?", ["%{$search}%"])
                    ->orWhereHas('transporteur', function($q2) use ($search) {
                        $q2->where('type_vehicule', 'LIKE', "%{$search}%")
                            ->orWhere('zone_intervention', 'LIKE', "%{$search}%")
                            ->orWhere('licence_transport', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filtre par type de véhicule
        if ($request->filled('type_vehicule')) {
            $query->whereHas('transporteur', function($q) use ($request) {
                $q->where('type_vehicule', 'LIKE', "%{$request->type_vehicule}%");
            });
        }

        // Filtre par zone d'intervention
        if ($request->filled('zone')) {
            $query->whereHas('transporteur', function($q) use ($request) {
                $q->where('zone_intervention', 'LIKE', "%{$request->zone}%");
            });
        }

        // Filtre par capacité minimale
        if ($request->filled('capacite_min')) {
            $query->whereHas('transporteur', function($q) use ($request) {
                $q->where('capacite_transport', '>=', $request->capacite_min);
            });
        }

        // Filtre par capacité maximale
        if ($request->filled('capacite_max')) {
            $query->whereHas('transporteur', function($q) use ($request) {
                $q->where('capacite_transport', '<=', $request->capacite_max);
            });
        }

        // Filtre par ville
        if ($request->filled('ville')) {
            $query->where('ville', 'LIKE', "%{$request->ville}%");
        }

        // Filtre par commune
        if ($request->filled('commune')) {
            $query->where('commune', 'LIKE', "%{$request->commune}%");
        }

        // Tri
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'nom_asc':
                $query->orderBy('nom', 'asc');
                break;
            case 'nom_desc':
                $query->orderBy('nom', 'desc');
                break;
            case 'capacite_asc':
                $query->orderBy(
                    Transporteur::select('capacite_transport')
                        ->whereColumn('transporteur.id_user', 'users.id')
                        ->orderBy('capacite_transport', 'asc')
                        ->limit(1)
                );
                break;
            case 'capacite_desc':
                $query->orderBy(
                    Transporteur::select('capacite_transport')
                        ->whereColumn('transporteur.id_user', 'users.id')
                        ->orderBy('capacite_transport', 'desc')
                        ->limit(1)
                );
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
        $transporteurs = $query->paginate(12)->withQueryString();

        // Données pour les filtres
        $typesVehicules = Transporteur::select('type_vehicule')
            ->distinct()
            ->whereNotNull('type_vehicule')
            ->where('type_vehicule', '!=', '')
            ->pluck('type_vehicule');

        $zones = Transporteur::select('zone_intervention')
            ->distinct()
            ->whereNotNull('zone_intervention')
            ->where('zone_intervention', '!=', '')
            ->pluck('zone_intervention');

        $villes = User::whereHas('typeUser', function($q) {
            $q->where('type', 'transporteur');
        })->whereNotNull('ville')->distinct()->pluck('ville');

        $communes = User::whereHas('typeUser', function($q) {
            $q->where('type', 'transporteur');
        })->whereNotNull('commune')->distinct()->pluck('commune');

        return view('services.transporteurs.index', compact(
            'transporteurs',
            'typesVehicules',
            'zones',
            'villes',
            'communes'
        ));
    }

    /**
     * Afficher les détails d'un transporteur
     */
    public function show($id)
    {
        $transporteur = User::with(['transporteur', 'typeUser'])
            ->whereHas('typeUser', function($q) {
                $q->where('type', 'transporteur');
            })
            ->where('statut', 'actif')
            ->findOrFail($id);

        // Autres transporteurs (similaires par zone ou type de véhicule)
        $autresTransporteurs = User::with(['transporteur', 'typeUser'])
            ->whereHas('typeUser', function($q) {
                $q->where('type', 'transporteur');
            })
            ->where('statut', 'actif')
            ->where('id', '!=', $id)
            ->where(function($q) use ($transporteur) {
                if ($transporteur->transporteur && $transporteur->transporteur->zone_intervention) {
                    $q->whereHas('transporteur', function($q2) use ($transporteur) {
                        $q2->where('zone_intervention', 'LIKE', "%{$transporteur->transporteur->zone_intervention}%");
                    });
                }
                if ($transporteur->transporteur && $transporteur->transporteur->type_vehicule) {
                    $q->orWhereHas('transporteur', function($q2) use ($transporteur) {
                        $q2->where('type_vehicule', $transporteur->transporteur->type_vehicule);
                    });
                }
            })
            ->latest()
            ->take(6)
            ->get();

        return view('services.transporteurs.show', compact('transporteur', 'autresTransporteurs'));
    }

    /**
     * Afficher le formulaire de demande de devis
     */
    public function devisForm($id)
    {
        $transporteur = User::with(['transporteur', 'typeUser'])
            ->whereHas('typeUser', function($q) {
                $q->where('type', 'transporteur');
            })
            ->where('statut', 'actif')
            ->findOrFail($id);

        return view('services.transporteurs.devis', compact('transporteur'));
    }

    /**
     * Demander un devis à un transporteur
     */
    public function demanderDevis(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'lieu_depart' => 'required|string|max:255',
            'lieu_arrivee' => 'required|string|max:255',
            'poids' => 'nullable|numeric|min:0',
            'date_souhaitee' => 'nullable|date|after:now',
        ]);

        $transporteur = User::whereHas('typeUser', function($q) {
            $q->where('type', 'transporteur');
        })->findOrFail($id);

        // Ici, vous pouvez créer une demande de devis ou envoyer un message
        // Pour l'instant, on redirige avec un message de succès

        return redirect()->route('service.transporteur.show', $id)
            ->with('success', 'Votre demande de devis a été envoyée au transporteur. Vous serez contacté dans les plus brefs délais.');
    }
}