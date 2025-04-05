<?php
// Last Modified Date: 18-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Requests\Fsm;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
class TreatmentPlantRequest extends FormRequest
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

    public function messages()
    {
        return[
            'name.required' => 'The Name is required.',
            'email.required' => 'The Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'latitude.required' => 'The latitiude is required.',
            'longitude.required' => 'The longitude is required.',
            'name.regex' => 'The Name may only contain letters and spaces.',
            'latitude.numeric' => 'The latitiude must be numeric.',
            'longitude.numeric' => 'The longitude must be numeric.',
            'capacity_per_day.numeric' => 'The capacity must be numeric.',
            'caretaker_name.required' => 'The caretaker name is required.',
            'caretaker_name.regex' => 'The contact person name may only contain letters and spaces.',
            'caretaker_number.integer' => 'The contact number must be integer.',

        ];
    }

    public function rules()
    {

      $rules= ($this->isMethod('POST')? $this->store() : $this->update());
     return $rules;
    }




    public function store()
    {
        $rules = [
            'name' => 'required',
            'location' => 'required',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'capacity_per_day' => 'required|numeric|min:0.01',
            'caretaker_number' => 'required|integer|min:1',
            'caretaker_name' => 'nullable',
            'type'=> 'required',
            'email' => [
                'required_if:create_user,on',
                'nullable',
                'string',
                'email',
                'max:255',
                'unique:pgsql.auth.users',
                'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'
            ],
            'status' => 'required',

        ];

        if (request('create_user') == 'on') {
            $rules['password'] = ['required', Password::min(10)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
                'confirmed'
            ];
        }

        return $rules;
    }


    public function update()
    {
        return [
            'name' => 'required',
            'location' => 'required',
            'capacity_per_day' => 'required|numeric|min:0.01',
            'caretaker_number' => 'required|integer|min:1',
            'type'=> 'required',
            'caretaker_name' => 'nullable',
            'status' => 'required',
        ];


    }
}
