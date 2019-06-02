<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourierRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'string|nullable',
            'passport_number' => 'string|nullable',
            'passport_date' => 'date|nullable',
            'birth_day' => 'date|nullable',
            'passport_issued_by' => 'string|nullable',
            'passport_address' => 'string|nullable',
        ];
    }
}
