<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGenreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // 編集時のためにルートパラメータからジャンルIDを取得
        $genreId = $this->route('genre') ? $this->route('genre')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                // ★ 新規登録時は重複不可、編集時は自分自身を除外して重複チェック
                Rule::unique('genres', 'name')->ignore($genreId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'ジャンル名を入力してください。',
            'name.max'      => 'ジャンル名は50文字以内で入力してください。',
            'name.unique'   => 'このジャンル名は既に登録されています。',
        ];
    }
}