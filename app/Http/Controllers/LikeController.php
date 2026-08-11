<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * レビューへの「いいね！」切り替え（登録 / 解除）
     */
    public function store(Review $review)
    {
        // ログインユーザー（未ログイン時のテスト用に仮で先頭ユーザーを取得）
        $user = auth()->user() ?? \App\Models\User::first();

        // すでに「いいね」しているか確認
        $like = $review->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // すでに存在すれば削除（いいね解除）
            $like->delete();
            $message = 'いいねを取り消しました';
        } else {
            // 存在しなければ新規作成（いいね登録）
            $review->likes()->create([
                'user_id' => $user->id,
            ]);
            $message = 'いいね！しました';
        }

        return back()->with('success', $message);
    }
}