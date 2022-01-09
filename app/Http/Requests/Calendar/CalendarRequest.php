<?php

namespace App\Http\Requests\Calendar;

use Illuminate\Foundation\Http\FormRequest;

class CalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=> 'require|min:5|max:255|unique:brands,name',
            'slug'=>'required',
            'description'=>'required|min:20|max:255',
            'image'=>'required',
            'is_active'=>'required',

            ];
    }
}
