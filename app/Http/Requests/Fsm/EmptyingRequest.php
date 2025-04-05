<?php
// Last Modified Date: 10-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Requests\Fsm;

use Illuminate\Foundation\Http\FormRequest;

class EmptyingRequest extends FormRequest
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



    public function store()
    {
        return [
            'service_receiver_name' => 'required',
            'service_receiver_gender' => 'required',
            'service_receiver_contact' => 'required|integer|min:1',
            'emptying_reason' => 'required',
            'volume_of_sludge' => 'required|numeric|min:0',
            'distance_closest_well' => 'nullable|numeric|min:0',
            'desludging_vehicle_id' => 'required|integer',
            'treatment_plant_id' => 'required|integer',
            'driver' => 'required|integer',
            'emptier1' => 'required|integer',
            'emptier2' => 'nullable|integer',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'no_of_trips' => 'required|integer|min:1',
            'receipt_number' => 'required',
            'total_cost' => 'required|numeric|min:0',
            'house_image' => 'required|mimes:jpeg,png,jpg|max:2048',
            'receipt_image' => 'required|mimes:jpeg,png,jpg|max:2048',
        ];
    }
    public function update()
    {
        return [
            'service_receiver_name' => 'required',
            'service_receiver_gender' => 'required',
            'service_receiver_contact' => 'required|integer|min:1',
            'emptying_reason' => 'required',
            'volume_of_sludge' => 'required|numeric|min:0',
            'distance_closest_well' => 'nullable|numeric|min:0',
            'desludging_vehicle_id' => 'required|integer',
            'treatment_plant_id' => 'required|integer',
            'driver' => 'required|integer',
            'emptier1' => 'required|integer',
            'emptier2' => 'nullable|integer',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'no_of_trips' => 'required|integer|min:1',
            'receipt_number' => 'required',
            'total_cost' => 'required|numeric|min:0',
            'house_image' => 'mimes:jpeg,png,jpg|max:2048',
            'receipt_image' => 'mimes:jpeg,png,jpg|max:2048',

        ];
    }

    /**
     * Get the messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'service_receiver_name.required' => 'The service receiver name is required.',
            'service_receiver_gender.required' => 'The service receiver gender is required.',
            'service_receiver_contact.required' => 'The service receiver contact is required.',
            'emptying_reason.required' => 'The emptying reason is required.',
            'volume_of_sludge.required' => 'The sludge volume is required.',
            'volume_of_sludge.numeric' => 'The sludge volume must be numeric.',
            'distance_closest_well.numeric' => 'The distance to closest well must be numeric.',
            'desludging_vehicle_id.required' => 'The desludging vehicle number plate is required.',
            'treatment_plant_id.required' => 'The disposal place is required.',
            'driver.required' => 'The driver name is required.',
            'emptier1.required' => 'The emptier 1 name is required.',
            'emptier1.integer' => 'The emptier 1 name is invalid.',
            'emptier2.integer' => 'The emptier 2 name is invalid.',
            'start_time.required' => 'The start time is required.',
            'end_time.required' => 'The end time is required.',
            'end_time.after' => 'The end time must be after start time.',
            'no_of_trips.required' => 'The number of trips is required.',
            'no_of_trips.numeric' => 'The number of trips must be numeric.',
            'receipt_number.required' => 'The receipt number is required.',
            'total_cost.required' => 'The total cost is required.',
            'total_cost.numeric' => 'The total cost must be numeric.',
            'house_image.required' => 'The house image is required.',
            'house_image.file' => 'The house image must be an image file.',
            'house_image.mimetypes' => 'The house image type is not supported.',
            'receipt_image.required' => 'The receipt image is required.',
            'receipt_image.file' => 'The receipt image must be an image file.',
            'receipt_image.mimetypes' => 'The receipt image type is not supported. ',
        ];
    }
}
