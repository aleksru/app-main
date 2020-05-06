<?php

namespace App\Http\Requests\Admin;


use App\Enums\OtherStatusEnums;
use Illuminate\Foundation\Http\FormRequest;

class OtherStatusRequest extends FormRequest
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
        $statusTypes = implode(',', OtherStatusEnums::getAllTypesOtherStatuses());

        return [
            'name' => 'string|required',
            'color' => 'string|nullable',
            'ordering' => 'integer|nullable',
            'result' => 'integer|nullable',
            'type' => 'string|required|in:' . $statusTypes
        ];
    }
}