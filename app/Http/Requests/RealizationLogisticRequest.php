<?php


namespace App\Http\Requests;

use App\Enums\RoleOrderEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RealizationLogisticRequest extends FormRequest
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
                'supplier_id' => 'integer|nullable',
                'imei' => 'string|nullable',
                'price_opt' => 'numeric|nullable',
            ];
        }
        if(Auth::user()->isCourier()) {
            return [
                'imei' => 'string|nullable',
            ];
        }
        if(Auth::user()->isStock()) {
            return [
                'supplier_id' => 'integer|nullable',
                'imei' => 'string|nullable',
                'price_opt' => 'numeric|nullable',
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