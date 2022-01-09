<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'product'=>'required|array|min:1',
            'product.*.name'=>'required|min:3|max:255|string',
            'product.*.short_des'=>'required|min:3|max:255|string',
            'product.*.long_des'=>'required|min:3|max:255|string',
            'product.*.meta'=>'required|min:3|max:255|string',

            'is_active'=>'required|in:1,0',
            'slug'=>'required',
            'brand_id'=>'required',
            'barcode'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'product.name.min'=>'Your product\'s name Is Too Short',
            'product.name.max'=>'Your product\'s name Is Too Long',

            'product.short_des.min'=>'Your product\'s name Is Too Short',
            'product.short_des.max'=>'Your product\'s name Is Too Long',

            'product.long_des.min'=>'Your product\'s name Is Too Short',
            'product.long_des.max'=>'Your product\'s name Is Too Long',

            'product.meta.min'=>'Your product\'s name Is Too Short',
            'product.meta.max'=>'Your product\'s name Is Too Long',
        ];
    }
}
