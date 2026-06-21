<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Veterinaire;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VeterinaireController extends Controller
{
    /**
     * Afficher la liste des vétérinaires avec recherche et filtres
     */
    public function index(Request $request)
    {
        // Requête de base - vétérinaires actifs
        $query = User::with(['veterinaire', 'typeUser'])
            ->whereHas('typeUser', function($q) {
                $q->where('type', 'veterinaire');
            })
            ->where('statut', 'actif');

        // Filtre par recherche (nom, prénom, spécialité)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhereRaw("CONCAT(prenom, ' ', nom) LIKE ?", ["%{$search}%"])
                    ->orWhereHas('veterinaire', function($q2) use ($search) {
                        $q2->where('specialites', 'LIKE', "%{$search}%")
                            ->orWhere('zone_intervention', 'LIKE', "%{$search}%")
                            ->orWhere('numero_ordre', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filtre par spécialité
        if ($request->filled('specialite')) {
            $query->whereHas('veterinaire', function($q) use ($request) {
                $q->where('specialites', 'LIKE', "%{$request->specialite}%");
            });
        }

        // Filtre par zone d'intervention
        if ($request->filled('zone')) {
            $query->whereHas('veterinaire', function($q) use ($request) {
                $q->where('zone_intervention', 'LIKE', "%{$request->zone}%");
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
            case 'ancien':
                $query->orderBy('created_at', 'asc');
                break;
            case 'recent':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $veterinaires = $query->paginate(12)->withQueryString();

        // Données pour les filtres
        $specialites = Veterinaire::select('specialites')
            ->distinct()
            ->whereNotNull('specialites')
            ->where('specialites', '!=', '')
            ->pluck('specialites')
            ->map(function($item) {
                $items = explode(',', $item);
                return array_map('trim', $items);
            })
            ->flatten()
            ->unique()
            ->filter()
            ->values();

        $zones = Veterinaire::select('zone_intervention')
            ->distinct()
            ->whereNotNull('zone_intervention')
            ->where('zone_intervention', '!=', '')
            ->pluck('zone_intervention');

        $villes = User::whereHas('typeUser', function($q) {
            $q->where('type', 'veterinaire');
        })->whereNotNull('ville')->distinct()->pluck('ville');

        $communes = User::whereHas('typeUser', function($q) {
            $q->where('type', 'veterinaire');
        })->whereNotNull('commune')->distinct()->pluck('commune');

        return view('services.veterinaires.index', compact(
            'veterinaires',
            'specialites',
            'zones',
            'villes',
            'communes'
        ));
    }

    /**
     * Afficher les détails d'un vétérinaire
     */
    public function show($id)
    {
        $veterinaire = User::with(['veterinaire', 'typeUser'])
            ->whereHas('typeUser', function($q) {
                $q->where('type', 'veterinaire');
            })
            ->where('statut', 'actif')
            ->findOrFail($id);

        // Rendez-vous du vétérinaire (publics)
        $rendezVous = RendezVous::with(['client'])
            ->where('id_veterinaire', $id)
            ->where('statut', 'confirme')
            ->orderBy('date_prevue')
            ->take(5)
            ->get();

        // Autres vétérinaires (similaires par spécialité ou zone)
        $autresVeterinaires = User::with(['veterinaire', 'typeUser'])
            ->whereHas('typeUser', function($q) {
                $q->where('type', 'veterinaire');
            })
            ->where('statut', 'actif')
            ->where('id', '!=', $id)
            ->where(function($q) use ($veterinaire) {
                if ($veterinaire->veterinaire && $veterinaire->veterinaire->zone_intervention) {
                    $q->whereHas('veterinaire', function($q2) use ($veterinaire) {
                        $q2->where('zone_intervention', 'LIKE', "%{$veterinaire->veterinaire->zone_intervention}%");
                    });
                }
                if ($veterinaire->veterinaire && $veterinaire->veterinaire->specialites) {
                    $q->orWhereHas('veterinaire', function($q2) use ($veterinaire) {
                        $q2->where('specialites', 'LIKE', "%{$veterinaire->veterinaire->specialites}%");
                    });
                }
            })
            ->latest()
            ->take(6)
            ->get();

        return view('services.veterinaires.show', compact('veterinaire', 'rendezVous', 'autresVeterinaires'));
    }

    /**
     * Afficher le formulaire de prise de rendez-vous
     */
    public function rendezVousForm($id)
    {
        $veterinaire = User::with(['veterinaire', 'typeUser'])
            ->whereHas('typeUser', function($q) {
                $q->where('type', 'veterinaire');
            })
            ->where('statut', 'actif')
            ->findOrFail($id);

        return view('services.veterinaires.rendez-vous', compact('veterinaire'));
    }

    /**
     * Prendre un rendez-vous avec un vétérinaire
     */
    public function prendreRendezVous(Request $request, $id)
    {
        $request->validate([
            'sujet' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_prevue' => 'required|date|after:now',
            'heure_prevue' => 'required|string|regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/',
        ]);

        $veterinaire = User::whereHas('typeUser', function($q) {
            $q->where('type', 'veterinaire');
        })->findOrFail($id);

        // Combiner la date et l'heure
        $dateTime = $request->date_prevue . ' ' . $request->heure_prevue . ':00';

        // Vérifier que le vétérinaire n'a pas déjà un rendez-vous à cette date/heure
        $existingRendezVous = RendezVous::where('id_veterinaire', $id)
            ->where('date_prevue', $dateTime)
            ->whereIn('statut', ['en_attente', 'confirme'])
            ->first();

        if ($existingRendezVous) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ce créneau horaire est déjà pris. Veuillez choisir un autre horaire.');
        }

        // Créer le rendez-vous
        $rendezVous = RendezVous::create([
            'id_veterinaire' => $id,
            'id_client' => Auth::id(),
            'sujet' => $request->sujet,
            'description' => $request->description,
            'date_prevue' => $dateTime,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('service.veterinaire.show', $id)
            ->with('success', 'Votre demande de rendez-vous a été envoyée avec succès. Vous recevrez une confirmation sous 24h.');
    }

    /**
     * API pour obtenir les disponibilités d'un vétérinaire
     */
    public function getDisponibilites($id)
    {
        $veterinaire = User::whereHas('typeUser', function($q) {
            $q->where('type', 'veterinaire');
        })->findOrFail($id);

        // Récupérer les rendez-vous déjà pris
        $rendezVous = RendezVous::where('id_veterinaire', $id)
            ->whereIn('statut', ['en_attente', 'confirme'])
            ->where('date_prevue', '>', now())
            ->pluck('date_prevue')
            ->toArray();

        // Générer les créneaux disponibles
        $disponibilites = $this->genererCreneauxDisponibles($rendezVous);

        return response()->json($disponibilites);
    }

    /**
     * Générer les créneaux disponibles (méthode privée)
     */
    private function genererCreneauxDisponibles($rendezVousExistants)
    {
        $creneaux = [];
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $heures = ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '17:00'];

        // Générer les créneaux pour les 7 prochains jours
        for ($i = 0; $i < 7; $i++) {
            $date = now()->addDays($i);
            $dateStr = $date->format('Y-m-d');
            $jourNom = $jours[$date->dayOfWeek - 1] ?? 'Dimanche';
            
            // Sauter les dimanches
            if ($date->dayOfWeek == 0) {
                continue;
            }

            $creneaux[$dateStr] = [
                'date' => $dateStr,
                'jour' => $jourNom,
                'creneaux' => []
            ];

            foreach ($heures as $heure) {
                $dateTime = $dateStr . ' ' . $heure . ':00';
                $disponible = !in_array($dateTime, $rendezVousExistants);
                
                if ($disponible) {
                    $creneaux[$dateStr]['creneaux'][] = [
                        'heure' => $heure,
                        'disponible' => true
                    ];
                }
            }
        }

        return array_values($creneaux);
    }
}