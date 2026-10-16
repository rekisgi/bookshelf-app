<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    // ① 権限チェック（このリクエストを実行していい人か？）
    public function authorize(): bool
    {
        return true; // デフォルトの false から true に変更！
    }

    // ② バリデーションルール（何を入力必須・制限にするか？）
    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'author'         => ['required', 'string', 'max:255'],
            'isbn'           => ['required', 'digits:13', 'unique:books,isbn'],
            // published_at または published_date のどちらかがあればクリア
            'published_at'   => ['nullable', 'required_without:published_date', 'date'],
            'published_date' => ['nullable', 'required_without:published_at', 'date'],
            'genres'         => ['required', 'array', 'min:1'],
            'genres.*'       => ['exists:genres,id'],
            'image_url'      => ['nullable', 'url'],
            'description'    => ['nullable', 'string'],
        ];
    }

    // ③ エラーメッセージの日本語化（エラー時に何と表示するか？）
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