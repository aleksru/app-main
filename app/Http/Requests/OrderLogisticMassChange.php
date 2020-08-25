<?php


namespace App\Http\Requests;


use App\Enums\RoleOrderEnums;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class OrderLogisticMassChange extends FormRequest
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
                'logistic_status_id' => '',
                'stock_status_id' => '',
                'order_ids' => 'array|filled',
            ];
        }
        if(Auth::user()->isLogist()) {
            return [
                'logistic_status_id' => '',
                'order_ids' => 'array|filled',
            ];
        }

        if(Auth::user()->isStock()) {
            return [
                'stock_status_id' => '',
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
