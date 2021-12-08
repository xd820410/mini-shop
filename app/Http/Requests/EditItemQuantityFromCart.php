<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditItemQuantityFromCart extends FormRequest
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
            'goods_id' => 'required|integer|min:1|max:2147483647',
            'quantity' => 'required|integer|min:1|max:2147483647',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'quantity' => (Int) $this->quantity,
        ]);
    }
}