<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Afficher la liste de tous les services (vétérinaires + transporteurs)
     */
    public function index(Request $request)
    {
        // Requête pour les vétérinaires
        $veterinaires = User::with(['veterinaire', 'typeUser'])
            ->whereHas('typeUser', function($q) {
                $q->where('type', 'veterinaire');
            })
            ->where('statut', 'actif')
            ->latest()
            ->take(6)
            ->get();

        // Requête pour les transporteurs
        $transporteurs = User::with(['transporteur', 'typeUser'])
            ->whereHas('typeUser', function($q) {
                $q->where('type', 'transporteur');
            })
            ->where('statut', 'actif')
            ->latest()
            ->take(6)
            ->get();

        // Statistiques
        $totalVeterinaires = User::whereHas('typeUser', function($q) {
            $q->where('type', 'veterinaire');
        })->where('statut', 'actif')->count();

        $totalTransporteurs = User::whereHas('typeUser', function($q) {
            $q->where('type', 'transporteur');
        })->where('statut', 'actif')->count();

        return view('services.index', compact(
            'veterinaires',
            'transporteurs',
            'totalVeterinaires',
            'totalTransporteurs'
        ));
    }
}