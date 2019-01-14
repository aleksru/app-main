<?php
/**
 * Created by PhpStorm.
 * User: aleksru
 * Date: 14.01.2019
 * Time: 21:13
 */

namespace App\Http\Requests\Admin;


use Illuminate\Foundation\Http\FormRequest;

class DeliveryTypeRequest extends FormRequest
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
            'type' => 'required|string',
            'price' => 'nullable|numeric',
        ];
    }
}