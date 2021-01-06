<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'phone' => 'required|string',
            'address' => 'string|nullable',
            'description' => 'string|nullable',
            'price_type_id' => 'integer|nullable',
            'url' => 'url|nullable',
            'is_disable_api_price' => 'integer|nullable',
            'default_order_status_id' => 'integer|nullable',
        ];
    }
}
