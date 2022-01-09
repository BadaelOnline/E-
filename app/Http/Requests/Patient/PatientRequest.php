<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
            'gender' =>'required',
            'social_status'   =>'required',
            'note' =>'required',
            'blood_type'   =>'required',
            'medical_file_number'  =>'required',
            'medical_file_date'    =>'required',
            'review_date'  =>'required',
            'pdf'          =>'required',
            'is_active'  =>'required|in:0,1',
            'is_approved'=>'required|in:0,1',
        ];
    }
    public function messages()
    {
        return[
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',
        ];

    }
}
