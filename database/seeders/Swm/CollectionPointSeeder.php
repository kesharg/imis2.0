<?php

namespace Database\Seeders\Swm;

use App\Models\Swm\CollectionPoint;
use App\Models\Swm\Route;
use Illuminate\Database\Seeder;

class CollectionPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CollectionPoint::factory(5)->create();
    }
}
