<?php

namespace Database\Seeders\Swm;

use App\Models\Swm\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Route::factory(5)->create();
    }
}
