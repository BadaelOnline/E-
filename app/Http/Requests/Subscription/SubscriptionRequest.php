<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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

            'is_active'=>'required',
            'store_id'=>'required',
            'plan_id'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'transaction_id'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'required'=>'this field is required',
        ];
    }
}
