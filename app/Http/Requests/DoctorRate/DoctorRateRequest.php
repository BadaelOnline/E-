<?php

namespace App\Http\Requests\DoctorRate;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRateRequest extends FormRequest
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
            'doctor_id'=>'required',
            'rate'=>'required'
        ];
    }
    public function messages()
    {
        return[
            'required'=>'this field is required',

        ];
    }
}
