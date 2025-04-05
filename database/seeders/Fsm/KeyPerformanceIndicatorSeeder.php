<?php

namespace Database\Seeders\Fsm;

use App\Models\Fsm\KeyPerformanceIndicator;
use Illuminate\Database\Seeder;

class KeyPerformanceIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $indicators = [
            [
                "indicator" => "Application Response",
                "target" => 80,
            ],
            [
                "indicator" => "Safe Desludging",
                "target" => 100,
            ],
            [
                "indicator" => "Customer Satisfaction",
                "target" => 60,
            ],
            [
                "indicator" => "OHS Compliance(PPE)",
                "target" => 100,
            ],
        ];

        foreach ($indicators as $indicator){
            KeyPerformanceIndicator::create($indicator);
        }
    }
}
