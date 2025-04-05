<?php
// Last Modified Date: 19-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Requests\Fsm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CtptRequest extends FormRequest
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

    public function messages()
    {
        return[
            'type.required' => 'Toilet type is required.',
            'name.required' => 'Toilet name is required.',
            'name.unique' => 'Toilet name already in use.',
            'name.string' => 'Toilet name must be a string.',
            'ward.required' => 'Ward is required.',
            'location_name.string' => 'The location name mjust be a string.',
            'bin.required' => 'The House Nuber is required.',
            'owner.required' => 'The Owning Institution is required.',
            'owner.string' => 'The Owning Institution must be a string.',
            'operator_or_maintainer.required' => 'The operator or maintainer is required.',
            'caretaker_name.required' => 'The caretaker name is required.',
            'caretaker_name.string' => 'The caretaker name must be a string.',
            'owning_institution_name.string' => 'The name of owning institution must be a string.',
            'operator_or_maintainer_name.string' => 'The name of operate and maintained by must be a string.',
            'caretaker_gender.required' => 'The caretaker gender is required.',
            'caretaker_contact_number.required' => 'The caretaker contact number is required.',
            'no_of_hh_connected.integer' => 'The number of Households served must be an integer.',
            'no_of_male_users.integer' => 'The number of male users must be an integer.',
            'no_of_female_users.integer' => 'The number of female users must be an integer.',
            'no_of_children_users.integer' => 'The number of children users must be an integer.',
            'no_of_pwd_users.integer' => 'The number of pwd users must be an integer.',
            'total_no_of_toilets.integer' => 'The total number of seats must be an integer.',
            'total_no_of_urinals.integer' => 'The total number of urinals must be an integer.',
            'access_frm_nearest_road.numeric' => 'The access from nearest road must be numeric.',
            'male_seats.integer' => 'The male seats must be integer.',
            'female_seats.integer' => 'The female seats must be integer.',
            'pwd_seats.integer' => 'The pwd seats must be integer.',
            'status.required' => 'The status is required.',
            'amount_of_fee_collected.numeric' => 'The fee collected per house hold must be numeric.',
            'bin.required' => 'The bin is required.',
            'no_of_hh_connected.min' => 'Total number of Households served should be positive value.',
            'no_of_male_users.min' => 'Total number of male users should be positive value.',
            'no_of_female_users.min' => 'Total number of female users should be positive value.',
            'no_of_children_users.min' => 'Total number of children users should be positive value.',
            'no_of_pwd_users.min' => 'Total number of people with disability users should be positive value.',
            'total_no_of_toilets.min' => 'Total number of seats users should be positive value.',
            'total_no_of_urinals.min' => 'Total number of urinals should be positive value.',
            'male_seats.min' => 'Total number of male seats should be positive value.',
            'female_seats.min' => 'Total number of female seats should be positive value.',
            'pwd_seats.min' => 'Total number of people with disability should be positive value.',
            'amount_of_fee_collected.min' => 'Total amount of fee collected should be positive value.',
            'access_frm_nearest_road.min' => 'The access from nearest road should be positive value.',

        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function store()
    {

        return [
            'type' => 'required| string',
            'name' => 'nullable|string|unique:pgsql.fsm.toilets,name,NULL,id',
            'ward' => 'required',
            'location_name' => 'nullable| string',
            'bin' => 'required',
            'owner' => 'required| string',
            'operator_or_maintainer' => 'required',
            'operator_or_maintainer_name' => 'nullable| string',
            'owning_institution_name' => 'nullable| string',
            'caretaker_name' => 'required|string',
            'caretaker_gender' => 'required',
            'caretaker_contact_number' => 'required| numeric|digits:10',
            'no_of_hh_connected' => 'nullable|integer|min:0',
            'no_of_male_users' => 'nullable|integer|min:0',
            'no_of_female_users' => 'nullable|integer|min:0',
            'no_of_children_users' => 'nullable|integer|min:0',
            'no_of_pwd_users' => 'nullable|integer|min:0',
            'total_no_of_toilets' => 'nullable|integer|min:0',
            'total_no_of_urinals' => 'nullable|integer|min:0',
            'separate_facility_with_universal_design'=>'nullable|boolean',
            'access_frm_nearest_road' => 'nullable|numeric|min:0',
            'male_or_female_facility' => 'nullable|boolean',
            'handicap_facility' => 'nullable|boolean',
            'children_facility' => 'nullable|boolean',
            'male_seats' => 'nullable|integer|min:0',
            'female_seats' => 'nullable|integer|min:0',
            'pwd_seats' => 'nullable|integer|min:0',
            'sanitary_supplies_disposal_facility' =>'nullable|boolean',
            'status' => 'required',
            'indicative_sign' =>'nullable|boolean',
            'fee_collected' => 'nullable|boolean',
            'amount_of_fee_collected' => 'nullable|numeric|min:0',
            'frequency_of_fee_collected' => 'nullable'

        ];
    }

    public function update()
    {
        $id = request()->route('ctpt');
        return [
            'type' => 'required| string',
            'name' => [
                'nullable', 
                'string',   
                Rule::unique('pgsql.fsm.toilets', 'name') 
                    ->where(function ($query) {
                        return $query->whereNull('deleted_at'); 
                    })
                    ->ignore($id), 
            ],
            'ward' => 'required',
            'location_name' => 'nullable| string',
            'bin' => 'required',
            'owner' => 'required| string',
            'operator_or_maintainer' => 'required',
            'caretaker_name' => 'required|string',
            'caretaker_gender' => 'required',
            'caretaker_contact_number' => 'required| numeric|digits:10',
            'operator_or_maintainer_name' => 'nullable| string',
            'owning_institution_name' => 'nullable| string',
            'no_of_hh_connected' => 'nullable|integer|min:0',
            'no_of_male_users' => 'nullable|integer|min:0',
            'no_of_female_users' => 'nullable|integer|min:0',
            'no_of_children_users' => 'nullable|integer|min:0',
            'no_of_pwd_users' => 'nullable|integer|min:0',
            'total_no_of_toilets' => 'nullable|integer|min:0',
            'access_frm_nearest_road' => 'nullable|numeric|min:0',
            'male_or_female_facility' => 'nullable|boolean',
            'separate_facility_with_universal_design'=>'nullable|boolean',
            'total_no_of_urinals' => 'nullable|integer|min:0',
            'handicap_facility' => 'nullable|boolean',
            'children_facility' => 'nullable|boolean',
            'male_seats' => 'nullable|integer|min:0',
            'female_seats' => 'nullable|integer|min:0',
            'pwd_seats' => 'nullable|integer|min:0',
            'sanitary_supplies_disposal_facility' =>'nullable|boolean',
            'status' => 'required',
            'indicative_sign' =>'nullable|boolean',
            'fee_collected' => 'nullable|boolean',
            'amount_of_fee_collected' => 'nullable|numeric|min:0',
            'frequency_of_fee_collected' => 'nullable'
        ];
    }
}
