<?php

namespace App\Http\Requests\ActivityType;

use Illuminate\Foundation\Http\FormRequest;

class ActivityTypeRequest extends FormRequest
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

            'activity_type'=>'required|array|min:1',
            'activity_type.*.name'=>'required|min:3|string',
            'activity_type.*.local'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'activity_type.*.name.min' => 'Your activity_type\'s Name Is Too Short',
            'activity_type.*.name.max' => 'Your activity_type\'s Name Is Too Long',
            'activity_type.*.name.unique' => 'Your activity_type\'s Name Is Used By Another activity_type',
        ];
    }
}
