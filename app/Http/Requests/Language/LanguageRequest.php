<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name'                =>'require:languages',
            'iso_code'            =>'require:languages',
            'lang_code '          =>'require:languages',

        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Please Enter  Language\'s Name',
            'iso_code.required'=>'Please Enter Iso_code',
            'lang_code '=>'Please Enter language code',
        ];
    }
}
