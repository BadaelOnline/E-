<?php

namespace App\Http\Requests\Brands;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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

            'is_active'=>'required',
            'image'=>'required',
            'slug'=>'required',

            'brand'=>'required|array|min:1',
            'brand.*.name'=>'required|min:3|string',
            'brand.*.description'=>'required|min:10|max:255',
            'brand.*.local'=>'required'
 ];
    }

    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'brand.*.name.min' => 'Your brand\'s Name Is Too Short',
            'brand.*.name.max' => 'Your brand\'s Name Is Too Long',
            'brand.*.name.unique' => 'Your brand\'s Name Is Used By Another Brand',

            'brand.*.description.min' => 'Your brand Description\'s Is Too Short',
            'brand.*.description.max' => 'Your brand Description\'s Is Too Long',
 ];
    }
}
