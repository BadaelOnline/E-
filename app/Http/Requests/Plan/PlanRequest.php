<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
            'price_per_month'=>'required',

            'plan'=>'required|array|min:1',
            'plan.*.name'=>'required|min:3|string',
            'plan.*.local'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'plan.*.name.min' => 'Your plan\'s Name Is Too Short',
            'plan.*.name.max' => 'Your plan\'s Name Is Too Long',
            'plan.*.name.unique' => 'Your plan\'s Name Is Used By Another plan',
        ];
    }
}
