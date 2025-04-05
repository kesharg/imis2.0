<?php

namespace App\Http\Requests\PublicHealth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AtLeastOneFieldRequired;
use App\Rules\FatalitiesLessThanCases;

class YearlyWaterborneRequest extends FormRequest
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
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'infected_disease' => 'required',
            'year' => 'required',
            'male_cases' => ['required', 'numeric', 'min:0', new AtLeastOneFieldRequired],
            'female_cases' => ['required', 'numeric', 'min:0', new AtLeastOneFieldRequired],
            'other_cases' => ['required', 'numeric', 'min:0', new AtLeastOneFieldRequired],
            'male_fatalities' => ['required', 'numeric', 'min:0', new FatalitiesLessThanCases('male_cases')],
            'female_fatalities' => ['required', 'numeric', 'min:0', new FatalitiesLessThanCases('female_cases')],
            'other_fatalities' => ['required', 'numeric', 'min:0', new FatalitiesLessThanCases('other_cases')],

        ];
    }

    public function messages()
    {
        return [
            'infected_disease.required' => 'The Infected Disease Name is required.',
            'year.required' => 'The Year is required.',

            // 'total_no_of_cases.required' => 'The Total Number Cases is required.',
        ];
    }
}
