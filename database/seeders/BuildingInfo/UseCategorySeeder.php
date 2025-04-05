<?php

namespace Database\Seeders\BuildingInfo;

use Illuminate\Database\Seeder;
use App\Models\BuildingInfo\UseCategory;
use DB;

class UseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $types =  array(
            ['Residential','0'],
            ['Shop','1'],
            ['Restaurant','1'],
            ['Hotel','1'],
            ['Cinema','1'],
            ['Theater','1'],
            ['Other Service Oriented Businesses','1'],
            ['Club','2'],
            ['Farm','2'],
            ['City Hall','2'],
            ['Library','2'],
            ['Mixed','3'],
            ['Public/ Community Toilet','3'],
            ['Municipal Office','4'],
            ['Ward Office','4'],
            ['Government Office','4'],
            ['Police Office','4'],
            [ 'NGO','5'],
            ['INGO','5'],
            [ 'Social','5'],
            [ 'Political','5'],
            [ 'Industry','6'],
            ['Factory','6'],
            ['Mosque','7'],
            ['Church','7'],
            ['Temple','7'],
            ['Stupa','7'],
            ['Hermitage','7'],
            [ 'Hospital','8'],
            ['Health Post','8'],
            ['Health Post','8'],
            ['Nursing Home','8'],
            ['Private Clinic','8'],
            ['Aaryurvedic Hospital','8']

        );
     
     foreach ($types as $type) {
    
         $existPermission =  DB::table('building_info.use_categorys')
                 ->where('name', $type)
                 ->first();
         if(!$existPermission) {
            UseCategory::create([
             'name' => $type[0],
             'functional_use_id' => $type[1]
         ]);
         }
     }

    }
}
