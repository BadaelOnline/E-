<?php

namespace App\Http\Requests\RestaurantProduct;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantProductRequest extends FormRequest
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
            'item_id'        =>'required',

            'RestaurantProduct'=>'required|array|min:1',
            'RestaurantProduct.*.name'=>'required|min:3|string',
            'RestaurantProduct.*.short_description'=>'required|min:10|max:255',
            'RestaurantProduct.*.long_description'=>'required|min:10|max:255',
            'RestaurantProduct.*.locale'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'RestaurantProduct.*.name.min' => 'Your restaurant Product\'s name  Is Too Short',

            'RestaurantProduct.*.short_description.min' => 'Your Restaurant Product Description\'s Is Too Short',
            'RestaurantProduct.*.short_description.max' => 'Your Restaurant Product Description\'s Is Too Short',

            'RestaurantProduct.*.long_description.min' => 'Your Restaurant Product Description\'s Is Too Long',
            'RestaurantProduct.*.long_description.max' => 'Your Restaurant Product Description\'s Is Too Long',



        ];
    }
}
