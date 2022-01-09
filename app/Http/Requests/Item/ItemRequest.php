<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
            'image'          =>'required',
            'product_id'      =>'required',

            'Item'=>'required|array|min:1',
            'Item.*.name'=>'required|min:3|string',
            'Item.*.short_description'=>'required|min:10|max:255',
            'Item.*.long_description'=>'required|min:10|max:255',
            'Item.*.locale'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'Item.*.name.min' => 'Your Item\'s name  Is Too Short',

            'Item.*.short_description.min' => 'Your Item Description\'s Is Too Short',
            'Item.*.short_description.max' => 'Your Item Description\'s Is Too Short',

            'Item.*.long_description.min' => 'Your Item Description\'s Is Too Long',
            'Item.*.long_description.max' => 'Your Item Description\'s Is Too Long',


           ];
    }
}
