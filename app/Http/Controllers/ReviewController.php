<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * レビュー投稿処理
     */
    public function store(Request $request, Book $book)
    {
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

        $book->reviews()->create([
            'user_id' => auth()->id() ?? 1,
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('books.show', $book)->with('success', 'レビューを投稿しました。');
    }

    /**
     * 31. レビュー編集画面の表示
     */
    public function edit(Review $review)
    {
        $this->authorize('update', $review);

        return view('reviews.edit', compact('review'));
    }

    /**
     * 32. レビュー更新処理
     */
    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ], [
            'rating.required'  => '評価（星の数）を選択してください。',
            'rating.min'       => '評価は1以上で指定してください。',
            'rating.max'       => '評価は5以下で指定してください。',
            'comment.required' => 'レビューコメントを入力してください。',
            'comment.max'      => 'コメントは1000文字以内で入力してください。',
        ]);

        $review->update($validated);

        return redirect()
            ->route('books.show', $review->book_id)
            ->with('success', 'レビューを更新しました。');
    }

    /**
     * 34. レビュー削除処理
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $bookId = $review->book_id;
        $review->delete();

        return redirect()
            ->route('books.show', $bookId)
            ->with('success', 'レビューを削除しました。');
    }
}