<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    // ① 権限チェック
    public function authorize(): bool
    {
        return true; // true に変更！
    }

    // ② バリデーションルール（編集時専用の工夫あり）
    public function rules(): array
    {
        // 現在編集しようとしている本のIDを取得
        $bookId = $this->route('book') ? $this->route('book')->id : null;

        return [
            'title'        => ['required', 'string', 'max:255'],
            'author'       => ['required', 'string', 'max:255'],
            'isbn'         => [
                'required',
                'digits:13',
                // ★ ここが超重要！「自分以外のISBNと重複していないか」をチェック
                Rule::unique('books', 'isbn')->ignore($bookId),
            ],
            'published_at' => ['required', 'date'],
            'genres'       => ['required', 'array', 'min:1'],
            'genres.*'     => ['exists:genres,id'],
            'image_url'    => ['nullable', 'url'],
            'description'  => ['nullable', 'string'],
        ];
    }

    // ③ エラーメッセージの日本語化
    public function messages(): array
    {
        return [
            'title.required'        => 'タイトルを入力してください。',
            'title.max'             => 'タイトルは255文字以内で入力してください。',
            'author.required'       => '著者名を入力してください。',
            'author.max'            => '著者名は255文字以内で入力してください。',
            'isbn.required'         => 'ISBNを入力してください。',
            'isbn.digits'           => 'ISBNは13桁の半角数字で入力してください。',
            'isbn.unique'           => 'このISBNは既に登録されています。',
            'published_at.required' => '出版日を入力してください。',
            'published_at.date'     => '正しい日付形式で入力してください。',
            'genres.required'       => 'ジャンルを1つ以上選択してください。',
            'genres.min'            => 'ジャンルを1つ以上選択してください。',
            'genres.*.exists'       => '選択されたジャンルが無効です。',
            'image_url.url'         => '正しいURL形式で入力してください。',
        ];
    }
}