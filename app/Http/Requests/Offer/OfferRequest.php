<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
            'image'           =>'required|string',
            'user_email'      =>'required|email',
            'store_id'        =>'required:integer',
            'store_product_id'=>'required:integer',
            'price'           =>'required:integer',
            'selling_price'   =>'required:integer',
            'quantity'        =>'required:integer',
            'position'        =>'required:integer',
            'started_at'      =>'required',
            'ended_at'        =>'required',
            'is_active'       =>'required|in:1,0',
            'is_offer'        =>'required|in:1,0',

        ];
    }
    public function messages()
    {
        return[
            'required'=>'this field is required',
            'in'=>'this field must be 0(not active) 1(active)',
        ];
    }
}
