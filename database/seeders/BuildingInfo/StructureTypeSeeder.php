<?php

namespace Database\Seeders\BuildingInfo;

use Illuminate\Database\Seeder;
use App\Models\BuildingInfo\StructureType;
use DB;

class StructureTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types =  array(
            'CGI Sheet',
            'Katcha',
            'Load bearing',
            'RCC framed',
            'Semi Pucca',
            'Under Construction',
            'Wooden/Mud'

        );
     
     foreach ($types as $type) {
    
         $existPermission =  DB::table('building_info.structure_types')
                 ->where('type', $type)
                 ->first();
         if(!$existPermission) {
            StructureType::create([
             'type' => $type,
         ]);
         }
     }

    }
}
