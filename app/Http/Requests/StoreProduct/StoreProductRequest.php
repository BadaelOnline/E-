<?php

namespace App\Http\Requests\StoreProduct;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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

            'store_id'  =>'required',
            'product_id'=>'required',
            'price'     =>'required',
            'quantity'  =>'required',
            'is_active' =>'required|in:1,0',
            'is_appear' =>'required|in:1,0',

        ];
    }

    public function messages()
    {
        return[
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

        ];
    }
}
