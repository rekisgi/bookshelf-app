<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * レビューの保存処理
     */
    public function store(Request $request, Book $book)
    {
        // 1. バリデーション（星1〜5の数値、コメント必須）
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ], [
            'rating.required'  => '評価（星の数）を選択してください。',
            'rating.min'       => '評価は1以上で指定してください。',
            'rating.max'       => '評価は5以下で指定してください。',
            'comment.required' => 'レビューコメントを入力してください。',
            'comment.max'      => 'コメントは1000文字以内で入力してください。',
        ]);

        // 2. レビューの登録
        $book->reviews()->create([
            'user_id' => auth()->id() ?? 1, // ログイン中のID（未ログインなら仮で1）
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        // 3. 詳細画面へ戻り、完了メッセージを表示
        return redirect()->route('books.show', $book)->with('success', 'レビューを投稿しました！');
    }
}