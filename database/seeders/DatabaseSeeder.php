<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UsersTableSeeder::class);

        // $this->call(Fsm\EmployeeInfoSeeder::class);


        // //BuildingInfo Seeders

        // $this->call(BuildingInfo\FunctionalUseSeeder::class);
        // $this->call(BuildingInfo\UseCategorySeeder::class);
        // $this->call(BuildingInfo\StructureTypeSeeder::class);
        // $this->call(BuildingInfo\SanitationSystemTypeSeeder::class);
        // $this->call(BuildingInfo\WaterSourceSeeder::class);

    }
}
