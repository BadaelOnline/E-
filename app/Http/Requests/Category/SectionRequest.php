<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
            'slug'=>'required',

            'section'=>'required|array|min:1',
            'section.*.name'=>'required|min:3|string',
            'section.*.description'=>'required|min:10|max:255',
            'section.*.local'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'section.*.name.min' => 'Your section\'s Name Is Too Short',
            'section.*.name.max' => 'Your section\'s Name Is Too Long',
            'section.*.name.unique' => 'Your section\'s Name Is Used By Another section',

            'section.*.description.min' => 'Your section Description\'s Is Too Short',
            'section.*.description.max' => 'Your section Description\'s Is Too Long',

        ];
    }
}
