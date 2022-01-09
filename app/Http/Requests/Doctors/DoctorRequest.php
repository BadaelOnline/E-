<?php

namespace App\Http\Requests\Doctors;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
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
            'is_active'      =>'required|in:0,1',
            'is_approved'    =>'required|in:0,1',
            'clinic_id'      =>'required',


            'doctor'=>'required|array|min:1',
            'doctor.*.description'=>'required|min:10|max:255',
            'doctor.*.locale'=>'required',
//            'doctor.*.doctor_id'=>'required'
            ];
    }
    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'doctor.*.Description.min' => 'Your Doctor Description\'s Is Too Short',
            'doctor.*.description.max' => 'Your Doctor Description\'s Is Too Long',

        ];
    }
}
