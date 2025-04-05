<?php

namespace Database\Seeders\BuildingInfo;

use Illuminate\Database\Seeder;
use App\Models\BuildingInfo\WaterSource;
use DB;

class WaterSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types =  array(
            'Jar Water',
            'Rainwater',
            'Spring/River/Canal',
            'Others',
            'Private Tanker water',
            'Tube well',
            'Deep boring',
            'Well',
            'Dug well',
            'Stone spout/Pond',
            'Municipal/Public water supply'

        );
     
     foreach ($types as $type) {
    
         $existPermission =  DB::table('building_info.water_sources')
                 ->where('source', $type)
                 ->first();
         if(!$existPermission) {
            WaterSource::create([
             'source' => $type,
         ]);
         }
     }
    }
}
