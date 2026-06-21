<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Annonce;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\PointFidelite;
use App\Models\Message;
use App\Models\Recompense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord utilisateur
     */
    public function index()
    {
        $user = Auth::user();

        // Statistiques générales
        $statistiques = [
            'total_annonces' => Annonce::where('id_user', $user->id)->count(),
            'annonces_actives' => Annonce::where('id_user', $user->id)->where('statut', 'active')->count(),
            'total_commandes' => Commande::where('id_acheteur', $user->id)->count(),
            'commandes_en_cours' => Commande::where('id_acheteur', $user->id)->whereIn('statut_commande', ['en_attente', 'validee'])->count(),
            'commandes_livrees' => Commande::where('id_acheteur', $user->id)->where('statut_commande', 'livree')->count(),
            'messages_non_lus' => Message::where('id_destinataire', $user->id)->where('lu', false)->count(),
            'points_fidelite' => PointFidelite::where('id_user', $user->id)->where('type_operation', 'gain')->sum('montant_points') 
                                - PointFidelite::where('id_user', $user->id)->where('type_operation', 'depense')->sum('montant_points'),
        ];

        // Données pour les graphiques
        // 1. Commandes des 6 derniers mois
        $commandesParMois = [];
        $moisLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $mois = now()->subMonths($i);
            $moisLabels[] = $mois->format('M Y');
            $commandesParMois[] = Commande::where('id_acheteur', $user->id)
                ->whereYear('created_at', $mois->year)
                ->whereMonth('created_at', $mois->month)
                ->count();
        }

        // 2. Répartition des commandes par statut
        $commandesStatuts = [
            'en_attente' => Commande::where('id_acheteur', $user->id)->where('statut_commande', 'en_attente')->count(),
            'validee' => Commande::where('id_acheteur', $user->id)->where('statut_commande', 'validee')->count(),
            'en_cours' => Commande::where('id_acheteur', $user->id)->where('statut_commande', 'en_cours')->count(),
            'livree' => Commande::where('id_acheteur', $user->id)->where('statut_commande', 'livree')->count(),
            'annulee' => Commande::where('id_acheteur', $user->id)->where('statut_commande', 'annulee')->count(),
        ];

        // 3. Évolution des points des 6 derniers mois
        $pointsParMois = [];
        for ($i = 5; $i >= 0; $i--) {
            $mois = now()->subMonths($i);
            $pointsParMois[] = PointFidelite::where('id_user', $user->id)
                ->whereYear('created_at', $mois->year)
                ->whereMonth('created_at', $mois->month)
                ->where('type_operation', 'gain')
                ->sum('montant_points');
        }

        // Dernières commandes
        $dernieresCommandes = Commande::with(['annonce'])
            ->where('id_acheteur', $user->id)
            ->orWhere('id_vendeur', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Derniers messages
        $derniersMessages = Message::with(['expediteur', 'destinataire'])
            ->where('id_expediteur', $user->id)
            ->orWhere('id_destinataire', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Derniers paiements
        $derniersPaiements = Paiement::with(['commande', 'commande.annonce'])
            ->whereHas('commande', function($q) use ($user) {
                $q->where('id_acheteur', $user->id)
                  ->orWhere('id_vendeur', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        // Derniers points gagnés
        $derniersPoints = PointFidelite::with(['commande'])
            ->where('id_user', $user->id)
            ->where('type_operation', 'gain')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'statistiques',
            'dernieresCommandes',
            'derniersMessages',
            'derniersPaiements',
            'derniersPoints',
            'commandesParMois',
            'moisLabels',
            'commandesStatuts',
            'pointsParMois'
        ));
    }


    /**
     * Afficher la liste des commandes de l'utilisateur
     */
    public function commandes(Request $request)
    {
        $user = Auth::user();
        
        $query = Commande::with(['annonce', 'vendeur', 'transporteur'])
            ->where('id_acheteur', $user->id)
            ->orWhere('id_vendeur', $user->id);

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut_commande', $request->statut);
        }

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('annonce', function($q2) use ($search) {
                    $q2->where('titre', 'LIKE', "%{$search}%");
                })->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'ancien':
                $query->orderBy('created_at', 'asc');
                break;
            case 'prix_asc':
                $query->orderBy('montant_total', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('montant_total', 'desc');
                break;
            case 'recent':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $commandes = $query->paginate(10)->withQueryString();

        $statuts = [
            'en_attente' => 'En attente',
            'validee' => 'Validée',
            'en_cours' => 'En cours',
            'livree' => 'Livrée',
            'annulee' => 'Annulée'
        ];

        return view('dashboard.commandes', compact('commandes', 'statuts'));
    }

    /**
     * Afficher les détails d'une commande
     */
    public function commandeShow($id)
    {
        $user = Auth::user();
        
        $commande = Commande::with(['annonce', 'annonce.auteur', 'vendeur', 'acheteur', 'transporteur', 'paiements', 'pointsFidelite'])
            ->where(function($q) use ($user) {
                $q->where('id_acheteur', $user->id)
                  ->orWhere('id_vendeur', $user->id);
            })
            ->findOrFail($id);

        return view('dashboard.commande-show', compact('commande'));
    }

    /**
     * Afficher la liste des paiements de l'utilisateur
     */
    public function paiements(Request $request)
    {
        $user = Auth::user();

        $query = Paiement::with(['commande', 'commande.annonce'])
            ->whereHas('commande', function($q) use ($user) {
                $q->where('id_acheteur', $user->id)
                  ->orWhere('id_vendeur', $user->id);
            });

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut_paiement', $request->statut);
        }

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('commande', function($q) use ($search) {
                $q->whereHas('annonce', function($q2) use ($search) {
                    $q2->where('titre', 'LIKE', "%{$search}%");
                });
            });
        }

        // Tri
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'ancien':
                $query->orderBy('created_at', 'asc');
                break;
            case 'montant_asc':
                $query->orderBy('montant_paye', 'asc');
                break;
            case 'montant_desc':
                $query->orderBy('montant_paye', 'desc');
                break;
            case 'recent':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $paiements = $query->paginate(10)->withQueryString();

        $statuts = [
            'en_attente' => 'En attente',
            'reussi' => 'Réussi',
            'echoue' => 'Échoué',
            'rembourse' => 'Remboursé'
        ];

        return view('dashboard.paiements', compact('paiements', 'statuts'));
    }

    /**
     * Afficher les détails d'un paiement
     */
    public function paiementShow($id)
    {
        $user = Auth::user();

        $paiement = Paiement::with(['commande', 'commande.annonce', 'commande.annonce.auteur'])
            ->whereHas('commande', function($q) use ($user) {
                $q->where('id_acheteur', $user->id)
                  ->orWhere('id_vendeur', $user->id);
            })
            ->findOrFail($id);

        return view('dashboard.paiement-show', compact('paiement'));
    }

    /**
     * Afficher les points de fidélité de l'utilisateur
     */
    public function pointsFidelite(Request $request)
    {
        $user = Auth::user();

        // Points totaux
        $pointsGagnes = PointFidelite::where('id_user', $user->id)
            ->where('type_operation', 'gain')
            ->sum('montant_points');
        
        $pointsDepenses = PointFidelite::where('id_user', $user->id)
            ->where('type_operation', 'depense')
            ->sum('montant_points');
        
        $totalPoints = $pointsGagnes - $pointsDepenses;

        // Historique des points
        $query = PointFidelite::with(['commande'])
            ->where('id_user', $user->id);

        // Filtre par type d'opération
        if ($request->filled('type')) {
            $query->where('type_operation', $request->type);
        }

        // Tri
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'ancien':
                $query->orderBy('created_at', 'asc');
                break;
            case 'montant_asc':
                $query->orderBy('montant_points', 'asc');
                break;
            case 'montant_desc':
                $query->orderBy('montant_points', 'desc');
                break;
            case 'recent':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $historique = $query->paginate(10)->withQueryString();

        // Récompenses disponibles - Sans filtre de statut car la colonne n'existe pas
        $recompenses = Recompense::all();

        return view('dashboard.points-fidelite', compact(
            'user',
            'totalPoints',
            'pointsGagnes',
            'pointsDepenses',
            'historique',
            'recompenses'
        ));
    }

    /**
     * Échanger des points contre une récompense
     */
    public function echangerPoints(Request $request)
    {
        $request->validate([
            'recompense_id' => 'required|exists:recompense,id'
        ]);

        $user = Auth::user();
        $recompense = Recompense::findOrFail($request->recompense_id);

        // Calculer les points disponibles
        $pointsGagnes = PointFidelite::where('id_user', $user->id)
            ->where('type_operation', 'gain')
            ->sum('montant_points');
        
        $pointsDepenses = PointFidelite::where('id_user', $user->id)
            ->where('type_operation', 'depense')
            ->sum('montant_points');
        
        $totalPoints = $pointsGagnes - $pointsDepenses;

        // Vérifier si l'utilisateur a assez de points
        if ($totalPoints < $recompense->cout_points) {
            return redirect()->back()->with('error', 'Vous n\'avez pas assez de points pour échanger cette récompense.');
        }

        // Créer la transaction de dépense de points
        PointFidelite::create([
            'id_user' => $user->id,
            'montant_points' => -$recompense->cout_points,
            'type_operation' => 'depense',
            'id_commande' => null,
            'date_expiration' => null,
        ]);

        // Associer la récompense à l'utilisateur
        $user->recompenses()->attach($recompense->id, [
            'date_obtention' => now(),
            'statut' => 'actif'
        ]);

        return redirect()->route('dashboard.points-fidelite')
            ->with('success', "Félicitations ! Vous avez échangé {$recompense->nom_recompense} avec succès.");
    }

    /**
     * Afficher les récompenses de l'utilisateur
     */
    public function mesRecompenses()
    {
        $user = Auth::user();
        
        $recompenses = $user->recompenses()
            ->withPivot(['date_obtention', 'statut'])
            ->wherePivot('statut', 'actif')
            ->get();

        return view('dashboard.mes-recompenses', compact('recompenses'));
    }
}