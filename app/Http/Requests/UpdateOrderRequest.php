<?php

namespace App\Http\Requests;

use App\Order;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'user_id' => 'integer',
            'status_id' => 'integer|nullable',
            'client_id' => 'integer|nullable',
            'courier_id' => 'integer|nullable',
            'delivery_period_id' => 'integer|nullable',
            'operator_id' => 'integer|nullable',
            'store_id' => 'required|integer',
            'comment' => 'string|nullable',
            'date_delivery' => 'date|nullable',
            'metro_id' => 'integer|nullable',
            'store_text' => 'string|nullable',
            'flag_denial_acc' => 'string|nullable',
            'communication_time' => 'numeric|nullable',
            'denial_reason_id' => 'nullable|integer',
            'delivery_type_id' => 'nullable|integer',
            'flag_send_sms' => 'nullable|integer',
            'address_city' => 'string|nullable',
            'address_street' => 'string|nullable',
            'address_home' => 'string|nullable',
            'address_apartment' => 'string|nullable',
            'address_other' => 'string|nullable'
        ];
    }
}
