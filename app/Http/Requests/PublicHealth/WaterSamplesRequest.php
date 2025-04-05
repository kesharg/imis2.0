<?php
// Last Modified Date: 07-05-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Requests\PublicHealth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AtLeastOneFieldRequired;
use App\Rules\FatalitiesLessThanCases;

class WaterSamplesRequest extends FormRequest
{/**
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
        
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'sample_date' => 'required|date|before_or_equal:today',
                        'sample_location'=> 'required|string',
                        'no_of_samples_taken' => 'required|numeric|min:0',
                        'water_coliform_test_result'=> 'required|string',
                        'geom' => 'required',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'sample_date' => 'required|date|before_or_equal:today',
                        'sample_location'=> 'required|string',
                        'no_of_samples_taken' => 'required|numeric|min:0',
                        'water_coliform_test_result'=> 'required|string',
                        'geom' => 'required',
                       
                    ];
                }
            default:break;
        }
    }
     public function messages()
    {
        return [
            'sample_date.required' => 'The Sample Date is required.',
            'sample_location.required' => 'The Sample Location is required.',
            'no_of_samples_taken.required' => 'The Number of Sample Taken is required.',
            'no_of_samples_taken.string' => 'The Number of Sample Taken must be a string.',
            'water_coliform_test_result.required' => 'The Water Coliform Test Result is required.',
            'geom.required' => 'The sample point is required.',
            'no_of_samples_taken.min' => 'Number of Samples Taken should be positive value.',
            ];
    }
}
