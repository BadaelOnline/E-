<?php

namespace App\Http\Requests\MedicalDevice;

use Illuminate\Foundation\Http\FormRequest;

class MedicalDeviceRequest extends FormRequest
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
            'is_active'  =>'required|in:1,0',
            'is_approved'=>'required|in:1,0',
            'image'=>'required',

            'medicaldevice'=>'required|array|min:1',
            'medicaldevice.*.name'=> 'required|min:5|max:255',
            'medicaldevice.*.locale'=> 'required',
            'medicaldevice.*.description'=> 'required|min:5|max:255',
        ];
    }
    public function messages()
    {
        return [

            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'medicaldevice.*.name.min' => 'this medical Device\'s Name Is Too Short',
            'medicaldevice.*.name.max' => 'this medical Device\'s Name Is Too Long',
            'medicaldevice.*.name.unique' => 'This Name\'s Is Used By Another medical Device',

            'medicaldevice.*.description.min'=>'this medical Device\'s description Is Too Short',
            'medicaldevice.*.description.max'=>'this medical Device\'s description Is Too long'


        ];
    }
}
