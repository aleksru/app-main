<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OperatorRequest extends FormRequest
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
        $sipLogin = $this->route('operator') ? ",{$this->route('operator')->sip_login},sip_login" : '';

        return [
            'name' => 'required|string',
            'sip_login' => 'string|nullable|unique:operators,sip_login'.$sipLogin,
        ];
    }
}
