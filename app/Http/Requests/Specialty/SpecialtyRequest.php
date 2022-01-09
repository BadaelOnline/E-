<?php

namespace App\Http\Requests\Specialty;

use Illuminate\Foundation\Http\FormRequest;


class SpecialtyRequest extends FormRequest
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
            'graduation_year'=>'required',

            'specialty'=>'required_without:id|array|min:1',
            'specialty.*.name'=> 'required_without:id|string|min:3|max:255|unique:specialty_translation',
            'specialty.*.locale'=> 'required',
            'specialty.*.description'=> 'required|string|min:3|max:255',

        ];

    }
    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'specialty.*.name.min' => 'Your specialty\'s Name Is Too Short',
            'specialty.*.name.max' => 'Your specialty\'s Name Is Too Long',
            'specialty.*.name.unique' => 'This Name\'s Is Used By Another specialty',

            'specialty.*.description.min' => 'Your specialty\'s description Is Short',
            'specialty.*.description.max' => 'Your specialty\'s description Is Long',
            ];
    }
}
