<?php

namespace App\Http\Requests\Interaction;

use Illuminate\Foundation\Http\FormRequest;

class InteractionRequest extends FormRequest
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
            'user_id'=>'required',
            'offer_id'=>'required',
            'interaction_type'=>'required',
            'is_active'=>'required|in:1,0'
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
