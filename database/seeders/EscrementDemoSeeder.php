<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EscrementDemoSeeder extends Seeder
{
    public function run(): void
    {
        $eleveur = DB::table('users')
            ->join('type_user', 'users.id_type_user', '=', 'type_user.id')
            ->where('type_user.type', 'eleveur')
            ->select('users.id as user_id')
            ->first();

        if ($eleveur) {
            $especes = DB::table('especes')->pluck('id', 'nom');
            
            $escrements = [
                ['nom' => 'Fumier de bovin', 'espece' => 'bovin', 'description' => 'Fumier de bovin, excellent engrais organique'],
                ['nom' => 'Fiente de poulet', 'espece' => 'poulet', 'description' => 'Fiente de poulet, riche en azote'],
                ['nom' => 'Fumier de mouton', 'espece' => 'ovin', 'description' => 'Fumier de mouton, idéal pour le maraîchage'],
                ['nom' => 'Fumier de chèvre', 'espece' => 'caprin', 'description' => 'Fumier de chèvre, amendement de qualité'],
                ['nom' => 'Fiente de pintade', 'espece' => 'pintade', 'description' => 'Fiente de pintade, très concentrée'],
                ['nom' => 'Fumier de lapin', 'espece' => 'lapin', 'description' => 'Fumier de lapin, excellent pour les jardins'],
            ];

            foreach ($escrements as $escrement) {
                $especeId = $especes[$escrement['espece']] ?? null;
                
                if ($especeId) {
                    DB::table('escrement')->insert([
                        'id_user' => $eleveur->user_id,
                        'id_espece' => $especeId,
                        'nom' => $escrement['nom'],
                        'description' => $escrement['description'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
    }
}