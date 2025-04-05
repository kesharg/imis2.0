<?php

namespace Database\Seeders\BuildingInfo;

use Illuminate\Database\Seeder;
use App\Models\BuildingInfo\SanitationSystem;
use DB;

class SanitationSystemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types =  array(
            'Directly To Storm Water Drain',
            'Pit',
            'Dont Know',
            'Directly To Sewerage System',
            'Biogas',
            'Ecosan',
            'No toilet',
            'Directly To Open Environment',
            'Directly To Water Bodies',
            'Septic Tank',
            'Holding Tank'

        );
     
     foreach ($types as $type) {
    
         $existPermission =  DB::table('building_info.sanitation_system_types')
                 ->where('type', $type)
                 ->first();
         if(!$existPermission) {
            SanitationSystem::create([
             'type' => $type,
         ]);
         }
     }

    }
}
