<?php

namespace App\Services\Fsm;

use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Yajra\DataTables\DataTables;
use App\Models\Fsm\TreatmentPlantPerformanceTest;
use App\Models\Fsm\CwisSetting;

class CwisSettingService
{

    protected $session;
    protected $instance;

    /**
     * Constructs a new LandfillSite object.
     *
     *
     */
    public function __construct()
    {
        /*Session code
        ....
         here*/
    }


    /**
     * Store or update a newly created resource in storage.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */


    public function storeOrUpdate($data)
    {
        $performance_test = CwisSetting::where('category', 'cwis_setting')->get();
        $updates = [
            "average_household_size" => $data['average_household_size'],
            "total_population" => $data['total_population'],
            "fs_generation_rate_for_septictank" => $data['fs_generation_rate_for_septictank'],
            "fs_generation_rate_for_pit" => $data['fs_generation_rate_for_pit'],
            "ww_generated_from_sewerconnection" => $data['ww_generated_from_sewerconnection'],
            "ww_generated_from_greywater" => $data['ww_generated_from_greywater'],
            "ww_generated_from_supernatant" => $data['ww_generated_from_supernatant'],
            "water_consumption_lpcd" => $data['water_consumption_lpcd'],
            "average_family_size" => $data['average_family_size'],
            "average_family_size_LIC" => $data['average_family_size_LIC'],
            "average_household_size_LIC" => $data['average_household_size_LIC'],


        ];
        foreach ($updates as $key => $value) {
            $setting = $performance_test->where('name', $key)->first();
            if ($setting) {
                $setting->value = $value;
                $setting->save();
            }
        }
    }
}
