<?php

namespace App\Http\Requests\SocialMedia;

use Illuminate\Foundation\Http\FormRequest;

class SocialMediaRequest extends FormRequest
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
            'instagram_account'=>'required|string',
            'email'=>'required',
            'user_id'=>'required',
            'is_active'=>'required|in:1,0',
            'phone_number'      =>'required|regex:/[0-9]/',
            'whatsapp_number'   =>'required|regex:/[0-9]/',
            'telegram_number'   =>'required|regex:/[0-9]/',
        ];
    }
    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'phone_number.required'=>'Please Enter Your  Phone Number',
            'whatsapp_number.required'=>'Please Enter Your  whatsapp Number',
            'telegram_number.required'=>'Please Enter Your  telegram Number',



        ];

            }

}
