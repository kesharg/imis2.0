<?php

namespace Database\Seeders\Swm;

use App\Models\Swm\TransferStation;
use Illuminate\Database\Seeder;

class TransferStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransferStation::factory(5)->create();
    }
}
