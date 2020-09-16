<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
        $article = $this->route('product') ? ",{$this->route('product')->id},id" : '';
        return [
            'article' => 'string|nullable|unique:products,article' . $article,
            'product_name' => 'string|required',
            'category' => 'string|nullable',
            'type' => 'string|required',
            'fix_price' => 'numeric|nullable'
        ];
    }
}
