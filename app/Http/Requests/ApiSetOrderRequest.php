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
            'store_text' =>'required|string',
            'store_id' =>'required|string',
            'phone' =>'required|string',
            'comment' =>'string|nullable',
            'products' =>'string|nullable',
            'quantity' => '',
            
        ];
    }
}
