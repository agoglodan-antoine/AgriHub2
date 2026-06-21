<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Annonce;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaiementController extends Controller
{
    /**
     * Récupérer les informations d'une commande (AJAX)
     */
    public function getCommandeInfo($id)
    {
        try {
            $commande = Commande::with([
                'acheteur',
                'vendeur',
                'annonce' => function($query) {
                    $query->with([
                        'piecesJointes',
                        'auteur',
                        'animal' => function($q) {
                            $q->with('race');
                        },
                        'nourriture',
                        'accessoire',
                        'escrement'
                    ]);
                }
            ])->findOrFail($id);

            // Vérifier que l'utilisateur est impliqué dans la commande
            $userId = Auth::id();
            if ($commande->id_acheteur !== $userId && $commande->id_vendeur !== $userId) {
                return response()->json([
                    'error' => 'Vous n\'êtes pas autorisé à voir cette commande.'
                ], 403);
            }

            // Récupérer l'image de l'annonce
            $annonce = $commande->annonce;
            $imageUrl = null;
            if ($annonce) {
                $imagePrincipale = $annonce->piecesJointes->where('est_principale', true)->first() ?? $annonce->piecesJointes->first();
                if ($imagePrincipale && $imagePrincipale->chemin_stockage && Storage::disk('public')->exists($imagePrincipale->chemin_stockage)) {
                    $imageUrl = asset('storage/' . $imagePrincipale->chemin_stockage);
                }
            }

            return response()->json([
                'commande' => [
                    'id' => $commande->id,
                    'quantite' => $commande->quantite,
                    'prix_unitaire' => number_format($commande->prix_unitaire, 0, ',', ' '),
                    'prix_unitaire_raw' => $commande->prix_unitaire,
                    'montant_total' => $commande->montant_total,
                    'montant_ajuste' => $commande->montant_ajuste,
                    'reduction' => $commande->reduction,
                    'statut_commande' => $commande->statut_commande,
                    'date_commande' => $commande->date_commande ? $commande->date_commande->format('d/m/Y H:i') : null,
                    'acheteur' => $commande->acheteur ? ($commande->acheteur->prenom . ' ' . $commande->acheteur->nom) : 'N/A',
                    'vendeur' => $commande->vendeur ? ($commande->vendeur->prenom . ' ' . $commande->vendeur->nom) : 'N/A',
                ],
                'annonce' => [
                    'id' => $annonce ? $annonce->id : null,
                    'titre' => $annonce ? $annonce->titre : 'Annonce supprimée',
                    'type' => $annonce ? $annonce->type : null,
                    'prix' => $annonce ? number_format($annonce->prix, 0, ',', ' ') : null,
                    'image' => $imageUrl,
                ]
            ]);

        } catch (Throwable $e) {
            \Log::error('Erreur getCommandeInfo:', [
                'commande_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Erreur lors du chargement des informations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ajuster le paiement d'une commande (AJAX)
     */
    public function ajusterPaiement(Request $request, $id)
    {
        try {
            $request->validate([
                'reduction' => 'required|numeric|min:0',
            ]);

            $commande = Commande::findOrFail($id);

            // Vérifier que l'utilisateur est le vendeur
            if ($commande->id_vendeur !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à ajuster ce paiement.'
                ], 403);
            }

            // Vérifier que la commande est en attente
            if ($commande->statut_commande !== 'en_attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande ne peut pas être ajustée.'
                ], 400);
            }

            $reduction = $request->reduction;
            $montantTotal = $commande->montant_total;

            if ($reduction > $montantTotal) {
                return response()->json([
                    'success' => false,
                    'message' => 'La réduction ne peut pas dépasser le montant total.'
                ], 400);
            }

            $commande->update([
                'reduction' => $reduction,
                'montant_ajuste' => $montantTotal - $reduction,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paiement ajusté avec succès.',
                'data' => [
                    'reduction' => $reduction,
                    'montant_ajuste' => $commande->montant_ajuste,
                ]
            ]);

        } catch (Throwable $e) {
            \Log::error('Erreur ajusterPaiement:', [
                'commande_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajustement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Page de paiement
     */
    public function pagePaiement($commandeId)
    {
        try {
            $commande = Commande::with([
                'acheteur',
                'vendeur',
                'annonce' => function($query) {
                    $query->with([
                        'piecesJointes',
                        'animal.race',
                        'nourriture',
                        'accessoire',
                        'escrement'
                    ]);
                }
            ])->findOrFail($commandeId);

            // Vérifier que l'utilisateur est l'acheteur
            if ($commande->id_acheteur !== Auth::id()) {
                abort(403, 'Vous n\'êtes pas autorisé à payer cette commande.');
            }

            // Vérifier que la commande est en attente
            if ($commande->statut_commande !== 'en_attente') {
                return redirect()->route('acheteur.mes-commandes')
                    ->with('error', 'Cette commande ne peut pas être payée.');
            }

            return view('paiement.index', compact('commande'));

        } catch (Throwable $e) {
            \Log::error('Erreur pagePaiement:', [
                'commande_id' => $commandeId,
                'error' => $e->getMessage()
            ]);
            abort(404, 'Commande non trouvée.');
        }
    }

    /**
     * Traiter le paiement
     */
    public function processPaiement(Request $request, $commandeId)
    {
        try {
            $commande = Commande::findOrFail($commandeId);

            // Vérifier que l'utilisateur est l'acheteur
            if ($commande->id_acheteur !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à payer cette commande.'
                ], 403);
            }

            // Vérifier que la commande est en attente
            if ($commande->statut_commande !== 'en_attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande ne peut pas être payée.'
                ], 400);
            }

            // Simulation de paiement (à remplacer par un vrai système de paiement)
            $paiementReussi = true;

            if ($paiementReussi) {
                DB::beginTransaction();

                $commande->update([
                    'statut_commande' => 'validee'
                ]);

                // Créer un enregistrement de paiement
                Paiement::create([
                    'id_commande' => $commande->id,
                    'montant_paye' => $commande->montant_ajuste,
                    'statut_paiement' => 'reussi',
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement effectué avec succès !',
                    'redirect' => route('paiement.succes', $commande->id)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Le paiement a échoué. Veuillez réessayer.'
                ], 400);
            }

        } catch (Throwable $e) {
            DB::rollBack();
            \Log::error('Erreur processPaiement:', [
                'commande_id' => $commandeId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du paiement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Page de succès du paiement
     */
    public function paiementSucces($commandeId)
    {
        $commande = Commande::findOrFail($commandeId);
        return view('paiement.succes', compact('commande'));
    }

    /**
     * Page d'échec du paiement
     */
    public function paiementEchec($commandeId)
    {
        $commande = Commande::findOrFail($commandeId);
        return view('paiement.echec', compact('commande'));
    }

    /**
     * Afficher une commande
     */
    public function showCommande($id)
    {
        try {
            $commande = Commande::with([
                'acheteur',
                'vendeur',
                'annonce' => function($query) {
                    $query->with([
                        'piecesJointes',
                        'auteur',
                        'animal.race',
                        'nourriture',
                        'accessoire',
                        'escrement'
                    ]);
                },
                'paiements'
            ])->findOrFail($id);

            $userId = Auth::id();
            if ($commande->id_acheteur !== $userId && $commande->id_vendeur !== $userId) {
                abort(403, 'Vous n\'êtes pas autorisé à voir cette commande.');
            }

            return view('commandes.show', compact('commande'));

        } catch (Throwable $e) {
            \Log::error('Erreur showCommande:', [
                'commande_id' => $id,
                'error' => $e->getMessage()
            ]);
            abort(404, 'Commande non trouvée.');
        }
    }

    /**
 * Liste des commandes de l'acheteur
 */
public function mesCommandes()
{
    try {
        $commandes = Commande::with([
            'annonce' => function($query) {
                $query->with('piecesJointes');
            },
            'vendeur',
            'paiements'
        ])
        ->where('id_acheteur', Auth::id())
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('acheteur.mes-commandes', compact('commandes'));

    } catch (Throwable $e) {
        \Log::error('Erreur mesCommandes:', [
            'error' => $e->getMessage()
        ]);
        return back()->with('error', 'Erreur lors du chargement des commandes.');
    }
}
}