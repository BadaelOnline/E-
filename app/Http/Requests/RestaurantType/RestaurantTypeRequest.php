<?php

namespace App\Http\Requests\RestaurantType;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantTypeRequest extends FormRequest
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

            'restaurantType'=>'required|array|min:1',
            'restaurantType.*.title'=>'required|min:3|string',
            'restaurantType.*.short_description'=>'required|min:10|max:255',
            'restaurantType.*.long_description'=>'required|min:10|max:255',
            'restaurantType.*.locale'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'restaurantType.*.title.min' => 'Your restaurantType\'s title  Is Too Short',

            'restaurantType.*.short_description.min' => 'Your restaurantType Description\'s Is Too Short',
            'restaurantType.*.short_description.max' => 'Your restaurantType Description\'s Is Too Short',

            'restaurantType.*.long_description.min' => 'Your restaurantType Description\'s Is Too Long',
            'restaurantType.*.long_description.max' => 'Your restaurantType Description\'s Is Too Long',

        ];
    }
}
