<?php

namespace App\Http\Requests\CustomField;

use Illuminate\Foundation\Http\FormRequest;

class CustomFieldRequest extends FormRequest
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
            'is_active'=>'required|in:0,1',
            'custom_field'=>'required|array|min:1',
            'custom_field.*.name'=>'required|min:3|string',
            'custom_field.*.description'=>'required|min:3|max:255',
            'custom_field.*.local'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',
            'name.min'=>'Your custom_field\'s name Is Too Short',
            'name.max'=>'Your custom_field\'s name Is Too Long',
            'custom_field.*.description.min' => 'Your brand Description\'s Is Too Short',
            'custom_field.*.description.max' => 'Your brand Description\'s Is Too Long',
        ];
    }
}
