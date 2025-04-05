<?php

namespace Database\Seeders;

use App\Models\Swm\ServiceProvider;
use Illuminate\Database\Seeder;

class SwmServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServiceProvider::factory(5)->create();
    }
}
