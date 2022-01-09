<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'comment'=>'required|string|min:5|Max:255',
            'is_active'=>'required|in:1,0',
            'is_approved'=>'required|in:1,0'
        ];
    }
    public function messages()
    {
       return[
           'required'=>'this field is required',
           'in'=>'this field must be 0(not active),1(active)',

           'min' => 'Your comment\'s  Name Is Too Short',
           'max' => 'Your comment\'s  Name Is Too Long',
       ];
    }
}
