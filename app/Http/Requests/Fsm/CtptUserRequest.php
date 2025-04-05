<?php
// Last Modified Date: 09-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Requests\fsm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CtptUserRequest extends FormRequest
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
        
       $rules= ($this->isMethod('POST')? $this->store() : $this->update());
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
             
            'toilet_id' => 'required',
            'no_male_user' => 'required|numeric|min:0',
            'no_female_user' => 'required|numeric|min:0',
            'date' => 'required|date|date_equals:today',
     ];
     }
 
     public function update()
     {
        return[
        'no_male_user' => 'required|numeric|min:0',
        'no_female_user' => 'required|numeric|min:0',

        ];
     }
 
     public function messages()
     {
         return[
             'toilet_id.required' => 'The Toilet is required.',
             'date.required' => 'The Date is required.',
             'no_male_user.required' => 'The no of male user is required.',
             'no_male_user.numeric' => 'The no of male user  must be numeric.',
             'no_female_user.required' => 'The no of female user is required.',
             'no_female_user.numeric' => 'The no of female user  must be numeric.',
             'no_female_user.min' => 'The no of female user  must be positive value.',
             'no_male_user.min' => 'The no of male user  must be positive value.',
             'date_equals' => 'The date must be todays.',
         ];
     }
 }

