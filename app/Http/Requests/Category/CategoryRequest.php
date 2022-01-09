<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Categories\Category;

class CategoryRequest extends FormRequest
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
            'is_active'=>'required|in:0,1',
            'slug'=>'required',

            'category'=>'required|array|min:1',
            'category.*.name'=>'required|min:3|string',
        ];
    }

    public function messages()
    {
        return [
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',

            'category.*.name.min' => 'Your brand\'s Name Is Too Short',
            'category.*.name.max' => 'Your brand\'s Name Is Too Long',
            'category.*.name.unique' => 'Your brand\'s Name Is Used By Another Brand',

            'category.*.description.min' => 'Your brand Description\'s Is Too Short',
            'category.*.description.max' => 'Your brand Description\'s Is Too Long',

            'slug.min'=>'Your Category\'s Slug Is Too Short ',
            'slug.max'=>'Your Category\'s Slug Is Too Long',

        ];
    }
}
