<?php

namespace Database\Seeders\Swm;

use App\Models\Swm\LandfillSite;
use Illuminate\Database\Seeder;

class LandfillSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LandfillSite::factory(5)->create();
    }
}
