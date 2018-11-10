<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $name = $this->route('user') ? ",{$this->route('user')->name},name" : '';
        $email = $this->route('user') ? ",{$this->route('user')->email},email" : '';
        $passRequired = $this->route('user') ? '|nullable' : '|required';

        return [
            'name' => 'required|string|max:255|unique:users,name'.$name,
            'email' => 'string|email|max:255|unique:users,email'.$email,
            'password' => 'string|min:6'.$passRequired,
            'description' => 'string|nullable',
            'roles' => 'array',
        ];
    }
}
