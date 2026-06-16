<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Types d'utilisateurs disponibles
     */
    const ROLES = [
        'eleveur' => 'isEleveur',
        'acheteur' => 'isAcheteur',
        'veterinaire' => 'isVeterinaire',
        'transporteur' => 'isTransporteur',
        'vendeur_nourriture' => 'isVendeurNourriture',
        'vendeur_accessoire' => 'isVendeurAccessoire',
        'admin' => 'isAdmin',
        'super_admin' => 'isSuperAdmin',
        'active' => 'isActive',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $user = Auth::user();

        // Si aucun rôle n'est spécifié, vérifier seulement l'authentification
        if (empty($roles)) {
            return $next($request);
        }

        // Vérifier si l'utilisateur a le droit d'accéder à la route
        $hasRole = false;
        
        foreach ($roles as $role) {
            $role = trim($role);
            
            // Vérifier si le rôle existe dans la liste
            if (isset(self::ROLES[$role])) {
                $method = self::ROLES[$role];
                if (method_exists($user, $method) && $user->$method()) {
                    $hasRole = true;
                    break;
                }
            }
            
            // Permettre aussi l'utilisation directe de méthodes
            if (method_exists($user, $role) && $user->$role()) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            // Rôle requis pour l'affichage du message d'erreur
            $rolesList = implode(', ', $roles);
            $message = 'Accès non autorisé. Rôle requis : ' . $rolesList;
            
            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 403);
            }
            
            abort(403, $message);
        }

        // Vérifier si le compte est actif (sauf si on vérifie spécifiquement 'active')
        if (!in_array('active', $roles) && !$user->isActive()) {
            Auth::logout();
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Votre compte est désactivé.'], 403);
            }
            
            return redirect()->route('login')->with('error', 'Votre compte est désactivé. Veuillez contacter l\'administrateur.');
        }

        return $next($request);
    }
}