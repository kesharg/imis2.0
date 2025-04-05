<?php

namespace Database\Seeders\BuildingInfo;

use Illuminate\Database\Seeder;
use App\Models\BuildingInfo\FunctionalUse;
use DB;

class FunctionalUseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names =  array(
            'Residential',
            'Commercial',
            'Public',
            'Mixed (Residential + Other Uses)',
            'Government Institution',
            ' Social Organization',
            'Industrial',
            'Cultural & Religious',
            'Health'

        );
     
     foreach ($names as $name) {
    
         $existPermission =  DB::table('building_info.functional_uses')
                 ->where('name', $name)
                 ->first();
         if(!$existPermission) {
            FunctionalUse::create([
             'name' => $name,
         ]);
         }
     }
    }
}
