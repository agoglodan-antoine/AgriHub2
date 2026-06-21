<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TypeUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Liste des départements du Bénin
     */
    private function getDepartements(): array
    {
        return [
            'Alibori' => 'Alibori',
            'Atacora' => 'Atacora',
            'Atlantique' => 'Atlantique',
            'Borgou' => 'Borgou',
            'Collines' => 'Collines',
            'Donga' => 'Donga',
            'Kouffo' => 'Kouffo',
            'Littoral' => 'Littoral',
            'Mono' => 'Mono',
            'Ouémé' => 'Ouémé',
            'Plateau' => 'Plateau',
            'Zou' => 'Zou'
        ];
    }

    /**
     * Liste des communes du Bénin par département
     */
    private function getCommunes(): array
    {
        return [
            'Alibori' => [
                'Banikoara', 'Gogounou', 'Kandi', 'Karimama', 'Malanville', 'Ségbana'
            ],
            'Atacora' => [
                'Boukoumbé', 'Cobly', 'Kérou', 'Kouandé', 'Matéri', 'Natitingou', 'Péhunco', 'Tanguiéta', 'Toucountouna'
            ],
            'Atlantique' => [
                'Abomey-Calavi', 'Allada', 'Kpomassè', 'Ouidah', 'Sô-Ava', 'Toffo', 'Tori-Bossito', 'Zè'
            ],
            'Borgou' => [
                'Bembèrèkè', 'Kalalé', 'N\'Dali', 'Nikki', 'Parakou', 'Pèrèrè', 'Sinendé', 'Tchaourou'
            ],
            'Collines' => [
                'Bantè', 'Dassa-Zoumè', 'Glazoué', 'Ouèssè', 'Savalou', 'Savè'
            ],
            'Donga' => [
                'Bassila', 'Copargo', 'Djougou', 'Ouaké'
            ],
            'Kouffo' => [
                'Aplahoué', 'Djakotomey', 'Dogbo', 'Klouékanmè', 'Lalo', 'Toviklin'
            ],
            'Littoral' => [
                'Cotonou'
            ],
            'Mono' => [
                'Athiémé', 'Bopa', 'Comè', 'Grand-Popo', 'Houéyogbé', 'Lokossa'
            ],
            'Ouémé' => [
                'Adjarra', 'Adjohoun', 'Aguégués', 'Akpro-Missérété', 'Avrankou', 'Bonou', 'Dangbo', 'Porto-Novo', 'Sèmè-Kpodji'
            ],
            'Plateau' => [
                'Adja-Ouèrè', 'Ifangni', 'Kétou', 'Pobè', 'Sakété'
            ],
            'Zou' => [
                'Abomey', 'Agbangnizoun', 'Bohicon', 'Covè', 'Djidja', 'Ouinhi', 'Zagnanado', 'Za-Kpota', 'Zogbodomey'
            ]
        ];
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $departements = $this->getDepartements();
        $communes = $this->getCommunes();
        
        // Solution 5: Liste blanche explicite des types d'utilisateurs autorisés
        $allowedTypes = ['eleveur', 'acheteur', 'veterinaire', 'vendeur_nourriture','vendeur_accessoire','transporteur'];
        $typesUsers = TypeUser::where('statut', 'actif')
            ->whereIn('type', $allowedTypes)
            ->get();

        return view('auth.register', compact('departements', 'communes', 'typesUsers'));
    }

    /**
     * API: Récupérer les communes d'un département
     */
    public function getCommunesByDepartement($departement)
    {
        $communes = $this->getCommunes();
        return response()->json($communes[$departement] ?? []);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'telephone' => ['nullable', 'string', 'max:20'],
            'departement' => ['required', 'string', 'in:Alibori,Atacora,Atlantique,Borgou,Collines,Donga,Kouffo,Littoral,Mono,Ouémé,Plateau,Zou'],
            'commune' => ['required', 'string', 'max:100'],
            'id_type_user' => ['nullable', 'exists:type_user,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);

        // Vérification supplémentaire pour empêcher l'inscription avec un rôle admin
        if ($request->id_type_user) {
            $typeUser = TypeUser::find($request->id_type_user);
            $allowedTypes = ['eleveur', 'acheteur', 'veterinaire', 'transporteur'];
            if ($typeUser && !in_array($typeUser->type, $allowedTypes)) {
                throw ValidationException::withMessages([
                    'id_type_user' => 'Type d\'utilisateur invalide.',
                ]);
            }
        }

        $user = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'departement' => $request->departement,
            'ville' => $request->commune,
            'commune' => $request->commune,
            'id_type_user' => $request->id_type_user ?? null,
            'mot_de_passe_hash' => Hash::make($request->password),
            'statut' => 'actif',
            'date_inscription' => now(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard.index', absolute: false));
    }
}