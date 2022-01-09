<?php

namespace App\Http\Requests\RestaurantCategory;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantCategoryRequest extends FormRequest
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

            'RestaurantCategory'=>'required|array|min:1',
            'RestaurantCategory.*.name'=>'required|min:3|string',
            'RestaurantCategory.*.short_description'=>'required|min:10|max:255',
            'RestaurantCategory.*.long_description'=>'required|min:10|max:255',
            'RestaurantCategory.*.locale'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'RestaurantCategory.*.name.min' => 'Your restaurant\'s name  Is Too Short',

            'RestaurantCategory.*.short_description.min' => 'Your Restaurant Category Description\'s Is Too Short',
            'RestaurantCategory.*.short_description.max' => 'Your Restaurant Category Description\'s Is Too Short',

            'RestaurantCategory.*.long_description.min' => 'Your Restaurant Category Description\'s Is Too Long',
            'RestaurantCategory.*.long_description.max' => 'Your Restaurant Category Description\'s Is Too Long',



        ];
    }
}
