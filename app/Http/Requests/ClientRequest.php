<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    protected function prepareForValidation()
    {
        if ($this->has('phone'))
            $this->merge(['phone' => preg_replace('/[^0-9]/', '', $this->phone)]);
    }
}
