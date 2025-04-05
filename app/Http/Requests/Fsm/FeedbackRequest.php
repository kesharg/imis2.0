<?php
// Last Modified Date: 18-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Requests\Fsm;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
            'fsm_service_quality' => 'required|boolean',
            'wear_ppe' => 'required|boolean',
    ];
    }

    public function update()
    {
        return [
            
            
            'fsm_service_quality' => 'required|boolean',
            'wear_ppe' => 'required|boolean',
           
    ];
    }

}
