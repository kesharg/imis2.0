<?php

namespace App\Http\Requests\LayerInfo;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AtLeastOneFieldRequired;
use App\Rules\FatalitiesLessThanCases;
class LowIncomeCommunityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        $rules = ($this->isMethod('POST') ? $this->store() : $this->update());
        return $rules;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function store()
{
    return [
            'community_name' => 'required',
            'no_of_buildings' => 'required|integer|min:0',
            'population_total' => 'required|integer|min:0',
            'number_of_households' => 'required|integer|min:0',
            'population_male' => 'nullable|integer|min:0',
            'population_female' => 'nullable|integer|min:0',
            'population_others' => 'nullable|integer|min:0',
            'no_of_septic_tank' => 'nullable|integer|min:0',
            'no_of_holding_tank' => 'nullable|integer|min:0',
            'no_of_pit' => 'nullable|integer|min:0',
            'no_of_sewer_connection' => 'nullable|integer|min:0',
            'no_of_community_toilets' => 'nullable|integer|min:0',
            'geom' => 'required',
        
    ];
}


    public function update()
    {
        return [
            'community_name' => 'required',
            'no_of_buildings' => 'required|integer|min:0',
            'population_total' => 'required|integer|min:0',
            'number_of_households' => 'required|integer|min:0',
            'population_male' => 'nullable|integer|min:0',
            'population_female' => 'nullable|integer|min:0',
            'population_others' => 'nullable|integer|min:0',
            'no_of_septic_tank' => 'nullable|integer|min:0',
            'no_of_holding_tank' => 'nullable|integer|min:0',
            'no_of_pit' => 'nullable|integer|min:0',
            'no_of_sewer_connection' => 'nullable|integer|min:0',
            'no_of_community_toilets' => 'nullable|integer|min:0',
            'geom' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'hotspot_location.required' => 'The Hotspot Name is required.',
            'date.required' => 'The Date is required.',
            'no_of_cases.required' => 'The No. of cases is required.',
            'geom.required' => 'The Hotspot Area is required.',
            'no_cases.numeric' => 'The number of cases must be a numeric value.',
            'no_of_fatalities.required' =>  'The No. of Fatalities  is required.',
            'no_of_fatalities.numeric' => 'The number of Fatalities must be a numeric value.',
        ];
    }
}
// Last Modified Date: 10-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)  for .php files
