<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderStatusRequest extends FormRequest
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
        $status = $this->route('order_status') ? ",{$this->route('order_status')->status},status" : '';

        return [
            'status' => 'required|string|unique:order_statuses,status'.$status,
            'description' => 'string|nullable',
            'color' => 'string|nullable',
        ];
    }
}
