<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WaterSupplyInfo\WaterSupply;
use App\Models\WaterSupplyInfo\DueYear;

class WaterSupplyInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DueYear::truncate();

        $dueYear =  [
            [
                'name' => 'No Due',
                'value' => 0
            ],
            [
                'name' => '1 Year',
                'value' => 1
            ],
            [
                'name' => '2 Years',
                'value' => 2
            ],
            [
                'name' => '3 Years',
                'value' => 3
            ],
            [
                'name' => '4 Years',
                'value' => 4
            ],
            [
                'name' => '5 Years+',
                'value' => 5
            ],
            [
                'name' => 'No Data',
                'value' => 99
            ],
          ];

          DueYear::insert($dueYear);
    }
}
