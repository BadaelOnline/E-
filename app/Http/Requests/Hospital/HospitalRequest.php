<?php

namespace App\Http\Requests\Hospital;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class HospitalRequest extends FormRequest
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
             'is_active'       =>'required|in:0,1',
             'is_approved'    =>'required|in:0,1',
             'general_hospital'=>'required|in:0,1',
             'private_hospital'=>'required|in:0,1',
             'location_id'     =>'required',


            'hospital'=>'required|array|min:1',
            'hospital.*.name'=>'required|min:3|string',
            'hospital.*.description'=>'required|min:10|max:255',
            'hospital.*.locale'=>'required',
            ];

    }

    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'name.min' => 'Your Hospital\'s Name Is Too Short',
            'name.max' => 'Your Hospital\'s Name Is Too Long',
            'name.unique' => 'This Name\'s Is Used By Another Hospital',
        ];
    }
}
