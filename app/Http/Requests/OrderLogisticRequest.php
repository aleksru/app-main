<?php


namespace App\Http\Requests;

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
        if(Auth::user()->is_admin) {
            return [
                'courier_id' => 'integer|nullable',
                'comment_stock' => 'string|nullable',
                'courier_payment' => 'integer|nullable',
            ];
        }
        if(Auth::user()->isLogist()) {
            return [
                'courier_payment' => 'integer|nullable',
            ];
        }

        return [
            'courier_id' => 'integer|nullable',
            'comment_stock' => 'string|nullable'
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