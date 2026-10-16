<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    // ① 権限チェック
    public function authorize(): bool
    {
        return true; // 常に許可
    }

    // ② バリデーションルール
    public function rules(): array
    {
        return [
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    // ③ エラーメッセージの日本語化
    public function messages(): array
    {
        return [
            'rating.required' => '評価（星の数）を選択してください。',
            'rating.integer'  => '評価は数値で指定してください。',
            'rating.min'      => '評価は1〜5の間で指定してください。',
            'rating.max'      => '評価は1〜5の間で指定してください。',
            'comment.max'     => 'コメントは1000文字以内で入力してください。',
        ];
    }
}