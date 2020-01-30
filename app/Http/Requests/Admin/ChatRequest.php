<?php


namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
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
        $name = $this->route('chat') ? ",{$this->route('chat')->id},id" : '';
        return [
            'name' => 'string|required|unique:chats,name' . $name,
        ];
    }
}