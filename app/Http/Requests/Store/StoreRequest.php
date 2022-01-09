<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'street_id'     =>'required',
            'loc_id'        =>'required',
            'country_id'    =>'required',
            'gov_id'        =>'required',
            'city_id'       =>'required',
//            'offer_id'      =>'required',
            'socialMedia_id'=>'required',
//            'followers_id'  =>'required',
            'is_active'     =>'required',
//            'is_approved'   =>'required',
//            'delivery'      =>'required',
            'edalilyPoint'  =>'required',
//            'rating'        =>'required',
            'workingHours'  =>'required',
            'logo'          =>'required',
            'store.*.title'          =>'required|min:5|max:255|string',

        ];
    }

    public function messages()
    {
        return [

            'required'=>'this field is required',

            'store.title.min'=>'Your store\'s Name Is Too Short',
            'store.title.max'=>'Your store\'s Name Is Too Long',


        ];
    }
}
