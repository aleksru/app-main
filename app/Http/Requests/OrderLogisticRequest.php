<?php


namespace App\Http\Requests;

use App\Enums\RoleOrderEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderLogisticRequest extends FormRequest
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
        if(Auth::user()->is_admin || Auth::user()->hasRole(RoleOrderEnums::FULL_LOGISTIC)) {
            return [
                'courier_id' => 'integer|nullable',
                'comment_stock' => 'string|nullable',
                'courier_payment' => 'integer|nullable',
                'logistic_status_id' => 'integer|nullable',
                'stock_status_id' => 'integer|nullable|required_with:order_ids',
                'order_ids' => 'array|filled',
                'comment_logist' => 'string|nullable',
            ];
        }
        if(Auth::user()->isLogist()) {
            return [
                //временно
                'courier_id' => 'integer|nullable',

                'courier_payment' => 'integer|nullable',
                'logistic_status_id' => 'integer|nullable',
                'comment_logist' => 'string|nullable',
            ];
        }

        if(Auth::user()->isStock()) {
            return [
                'comment_stock' => 'string|nullable',
                'courier_id' => 'integer|nullable',
                'stock_status_id' => 'integer|nullable|required_with:order_ids',
                'order_ids' => 'array|filled',
            ];
        }

        return [];

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