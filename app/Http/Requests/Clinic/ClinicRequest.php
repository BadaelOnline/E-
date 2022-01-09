<?php

namespace App\Http\Requests\Clinic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
class ClinicRequest extends FormRequest
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
            'is_active'=>'required|in:1,0',
            'is_approved'=>'required|in:1,0',
            'doctor_id'=>'required',
            'phone_number'=>'required',
            'active_times_id'=>'required',
            'location_id'=>'required',
            'name'=>'required|string|min:3|max:255'


        ];
    }
    public function  messages()
    {
        return [

            'required' => 'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'min' => 'Your clinic\'s  Name Is Too Short',
            'max' => 'Your clinic\'s  Name Is Too Long',

        ];
    }
}
