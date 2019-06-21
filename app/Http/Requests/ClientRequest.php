<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientRequest extends FormRequest
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
        $phone = $this->route('client') ? ",{$this->route('client')->id},id" : '';

        return [
            'name' => 'required|string',
            'phone' => 'required|string|unique:clients,phone'.$phone,
            'description' => 'string|nullable',
        ];
    }

    /**
     *
     */
    protected function prepareForValidation()
    {
        if ($this->has('phone'))
            $this->merge(['phone' => preg_replace('/[^0-9]/', '', $this->phone)]);
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        if($this->ajax()){
            throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
        }

        parent::failedValidation($validator);
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'phone.required'   => 'Номер телефона клиента обязателен для заполнения',
            'phone.unique'     => 'Номер телефона уже существует',
            'name.required'    => 'Имя клиента обязателено для заполнения',
        ];
    }
}
