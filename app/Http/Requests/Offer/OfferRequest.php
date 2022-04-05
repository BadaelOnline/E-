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
            'user_email'           =>'required|email',
            'storeProduct'         =>'required',
            'offer_price'          =>'required:integer',
            'selling_quantity'     =>'required:integer',
            'started_at'           =>'required',
            'ended_at'             =>'required',
            'is_active'            =>'required|in:1,0',
            'is_offer'             =>'required|in:1,0',
        ];
    }

    public function messages()
    {
        return[
            'user_email.required'       =>'this user_email is required',
            'storeProduct.required'     =>'this store products is required',
            'offer_price.required'      =>'this offer price is required',
            'selling_quantity.required' =>'this selling quantity is required',
            'started_at.required'       =>'this started at is required',
            'ended_at.required'         =>'this ended at is required',
            'is_active.required'        =>'this is_active is required',
            'is_offer.required'         =>'this is_offer is required',
            'is_active.in'              =>'this field must be 0(not active) 1(active)',
            'is_offer.in'               =>'this field must be 0(not offer) 1(offer)',
        ];
    }
}
