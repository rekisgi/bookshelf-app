<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * お気に入り登録
     */
    public function store(Book $book)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        if (!$user->favoriteBooks()->where('book_id', $book->id)->exists()) {
            $user->favoriteBooks()->attach($book->id);
        }

        return back()->with('success', 'お気に入りに追加しました！');
    }

    /**
     * お気に入り解除
     */
    public function destroy(Book $book)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $user->favoriteBooks()->detach($book->id);

        return back()->with('success', 'お気に入りから削除しました！');
    }
}