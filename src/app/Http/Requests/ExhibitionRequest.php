<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'item_name' => 'required',
            'content' => 'required|max:255',
            'item_image' => 'required|mimes:jpeg,png|max:5120',
            'category_id' => 'required|array|min:1',
            'category_id.*' => 'integer|exists:categories,id',
            'situation' => 'required',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'item_name.required' => '商品名を入力してください',
            'content.required' => '商品説明を入力してください',
            'content.max' => '商品説明は255文字以内で入力してください',
            'item_image.required' => '商品画像をアップロードしてください',
            'item_image.mimes' => '画像はjpegまたはpng形式でアップロードしてください',
            'item_image.max' => '画像サイズは5120KB以下にしてください。',
            'category_id.required' => 'カテゴリーを選択してください',
            'category_id.min' => 'カテゴリーを選択してください',
            'category_id.*.integer' => 'カテゴリーの選択が不正です',
            'situation.required' => '商品状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数字で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
        ];
    }
}
