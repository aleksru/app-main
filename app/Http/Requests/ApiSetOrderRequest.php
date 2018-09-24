<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiSetOrderRequest extends FormRequest
{
    protected $redirect = 'error';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_customer' =>'required|string',
            'store' =>'required|string',
            'phone' =>'required|string',
            'total' =>'required',
            'comment' =>'required|string',
            'products' =>'required',
            'quantity' => '',
            
        ];
    }
}
