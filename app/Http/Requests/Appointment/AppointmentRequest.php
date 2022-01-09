<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
                   'doctor_id'  =>'required',
                   'customer_id'=>'required',
                   'is_approved' =>'required|in:1,0',
                   'is_active'   =>'required|in:1,0',
                   'morning_evening' =>'required|in:1,0',
                   'active_times_id'=>'required',
        ];
    }
    public function messages()
    {
        return[
            'required'=>'this field is required',
            'in'=>'this field must be 0 (is not active) or 1 (is active)',
        ];
    }
}
