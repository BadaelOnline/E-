<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
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
            'social_media_id'=>'required',
            'appointment_id' =>'required',
            'location_id'    =>'required',
            'active_time_id'      =>'required',

            'restaurant'=>'required|array|min:1',
            'restaurant.*.title'=>'required|min:3|string',
            'restaurant.*.short_description'=>'required|min:10|max:255',
            'restaurant.*.long_description'=>'required|min:10|max:255',
            'restaurant.*.locale'=>'required',

            'RestaurantCategory'=>'required|array|min:1',
            
        ];
    }
    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'restaurant.*.title.min' => 'Your restaurant\'s title  Is Too Short',

            'restaurant.*.short_description.min' => 'Your restaurant Description\'s Is Too Short',
            'restaurant.*.short_description.max' => 'Your restaurant Description\'s Is Too Short',

            'restaurant.*.long_description.min' => 'Your restaurant Description\'s Is Too Long',
            'restaurant.*.long_description.max' => 'Your restaurant Description\'s Is Too Long',



        ];
    }
}
