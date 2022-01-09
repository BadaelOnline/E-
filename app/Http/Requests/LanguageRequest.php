<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language\Language;


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
                'name' => 'required|min:3|max:50|unique:languages',
                'abbr' => 'required|min:2|max:5:languages',
                'native' => 'required|min:3|max:50|unique:languages',
                'locale' => 'required:languages',
                'iso_code' => 'required:languages',
                'flag' => 'required:languages',
                'rtl' => 'required:languages',



            ];
        }
        public function messages()
        {
            return [
                'name.required'=>'Please Enter Your Language\'s name',
                'name.min'=>'Your Language\'s name Is Too Short',
                'name.max'=>'Your Language\'s name Is Too Long',
                'name.unique'=>'This name\'s Is Used By Another Language',
                
                'abbr.required'=>'Please Enter Your Language\'s abbr',
                'abbr.min'=>'Your Language\'s abbr Is Too Short',
                'abbr.max'=>'Your Language\'s abbr Is Too Long',
                'abbr.unique'=>'This abbr\'s Is Used By Another Language',

                'native.required'=>'Please Enter Your Product\'s Native',
                'native.min'=>'Your Category\'s Native Is Too Short ',
                'native.max'=>'Your Category\'s Native Is Too Long',
                'native.unique'=>'This native\'s Is Used By Another Language',

                'locale.required'=>'Please Specify The parent',
                'iso_code.required'=>'Please Specify The parent',
                'flag.required'=>'Please Specify The parent',
                'rtl.required'=>'Please Specify The parent',

            ];
        }
}
