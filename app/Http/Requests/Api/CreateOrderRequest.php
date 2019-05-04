<?php

namespace App\Http\Requests\Api;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateOrderRequest extends FormRequest
{
    protected $redirect = 'error';

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
            'name_customer' => 'string|nullable',
            'phone'         => 'required|string',
            'store_text'    => 'required|string',
            'store_phone'   => 'required|string',
            'comment'       => 'string|nullable',
            'products'      => 'string|nullable',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }

    public function messages()
    {
        return [
            'phone.required'         => 'A phone is required',
            'store_text.required'    => 'A store_text is required',
            'store_phone.required'   => 'A store_phone is required',
        ];
    }
}