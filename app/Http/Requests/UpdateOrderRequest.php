<?php

namespace App\Http\Requests;

use App\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        if(Auth::user()->is_admin) {
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
                'flag_denial_acc' => 'integer|nullable',
                'communication_time' => 'string|nullable',
                'denial_reason_id' => 'nullable|integer',
                'delivery_type_id' => 'nullable|integer',
                'flag_send_sms' => 'nullable|integer',
                'address_city' => 'string|nullable',
                'address_street' => 'string|nullable',
                'address_home' => 'string|nullable',
                'address_apartment' => 'string|nullable',
                'address_other' => 'string|nullable',
                'substatus_id' => 'integer|nullable',
                'logistic_status_id' => 'integer|nullable',
                'stock_status_id' => 'integer|nullable',
                'city_id' => 'integer|nullable',
            ];
        }

        if(Auth::user()->isLogist()) {
            return [
                'courier_id' => 'integer|nullable',
                'status_id' => 'integer|nullable',
                'logistic_status_id' => 'integer|nullable',
            ];
        }

        if(Auth::user()->isStock()) {
            return [
                'status_id' => 'integer|nullable',
                'stock_status_id' => 'integer|nullable',
            ];
        }

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
            'flag_denial_acc' => 'integer|nullable',
            'communication_time' => 'string|nullable',
            'denial_reason_id' => 'nullable|integer',
            'delivery_type_id' => 'nullable|integer',
            'flag_send_sms' => 'nullable|integer',
            'address_city' => 'string|nullable',
            'address_street' => 'string|nullable',
            'address_home' => 'string|nullable',
            'address_apartment' => 'string|nullable',
            'address_other' => 'string|nullable',
            'substatus_id' => 'integer|nullable',
            'city_id' => 'integer|nullable',
        ];
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
}
