<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment_method' => 'required',
            'postal_order' => 'nullable',
            'address_order' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $postal_order = $this->input('postal_order');
            $address_order = $this->input('address_order');

            if (empty($postal_order) || empty($address_order)) {
                $validator->errors()->add('address', '配送先を入力してください');
            }
        });
    }
}
