<?php

namespace App\Http\Requests\Fsm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KpiTargetRequest extends FormRequest
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
            'indicator_id' => 'required|integer',
            'year' => [
                'required',
                'integer',
                'digits:4',
                'before_or_equal:' . now()->format('Y'),
                Rule::unique('pgsql.fsm.kpi_targets', 'year')
                    ->where(function ($query) {
                        $query->where('indicator_id', request()->input('indicator_id'))
                              ->whereNull('deleted_at');
                    })
            ],
            'target' => 'required|integer|max:100 |min:0',
    ];
    }

    public function update()
    {
      
   $id = request()->route('kpi_target');
        return [
            'indicator_id' => 'required|integer',
            'year' => [
                'required',
                'integer',
                'digits:4',
                'before_or_equal:' . now()->format('Y'),
                Rule::unique('pgsql.fsm.kpi_targets', 'year')
                    ->where(function ($query) use($id)  {
                        $query->where('indicator_id', request()->input('indicator_id'))
                              ->whereNull('deleted_at');
                    })->ignore(request()->route('kpi_target'), 'id')
            ],
            'target' => 'required|integer|max:100 |min:0',
    ];
    }

    public function messages()
    {
        return[
            'indicator_id.required' => 'The Indicator is required.',
            'year.required' => 'The Year is required.',
            'year.unique' => 'The indicator for the year is already present.',
            'year.integer' => 'The Year must be an integer.',
            'year.before_or_equal' => 'The Year must not be greater than current year.',
            'year.digits' => 'The Year must be in 20** format.',
            'target.required' => 'The Target is required.',
            'target.integer' => 'The Target must be an integer.'
        ];
    }
}
