<?php
// Last Modified Date: 10-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)  
namespace App\Http\Requests\Fsm;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
            'road_code' => 'required',
            'house_number' => 'required',
            'customer_name' => '',
            'customer_gender' => '',
            'customer_contact' => 'nullable|integer|min:1',
            'applicant_name' => 'required',
            'applicant_gender' => 'required',
            'applicant_contact' => 'required|integer|min:1',
            'containment_code' => '',
            'ward' => 'integer|min:1',
            'proposed_emptying_date' => 'required|date|after_or_equal:'.date('m/d/Y'),
            'service_provider_id' => 'required|integer',
            'landmark' => '',
            'emergency_desludging_status' => 'required|boolean',
            'household_served' => 'integer|min:1',
            'population_served' => 'integer|min:1',
            'toilet_count' => 'integer|min:1',
        ];
    }

    /**
     * Get the error messages to display if validation fails.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'road_code.required' => 'The Street name is required.',
            'customer_name' => '',
            'customer_gender' => '',
            'customer_contact.integer' => 'The owner contact must be an integer',
            'applicant_name.required_unless' => 'The Applicant\'s name is required.',
            'applicant_gender.required_unless' => 'The Applicant\'s gender is required.',
            'applicant_contact.required_unless' => 'The Applicant\'s contact (phone) is required.',
            'containment_code' => '',
            'ward' => '',
            'proposed_emptying_date.required' => 'The Proposed emptying date field is required.',
            'service_provider_id.required' => 'The Service provider is required.',
            'landmark' => '',
            'emergency_desludging_status.required' => 'The Emergency Desludging Status is required.',
            'emergency_desludging_status.boolean' => 'The Emergency Desludging Status must be Yes or No.',
        ];
    }

}