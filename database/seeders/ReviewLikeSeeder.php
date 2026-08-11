<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Like; // ※もしいいねのモデル名が ReviewLike 等なら書き換えてね

class ReviewLikeSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = Review::all();

        foreach ($reviews as $review) {
            // 自分のレビュー以外（自分のuser_idを除外したユーザー）を取得
            $otherUsers = User::where('id', '!=', $review->user_id)->get();

            if ($otherUsers->isNotEmpty()) {
                // 0〜3人のランダムなユーザーを選択
                $likeCount = rand(0, min(3, $otherUsers->count()));
                if ($likeCount > 0) {
                    $likers = $otherUsers->random($likeCount);

                    foreach ($likers as $user) {
                        Like::firstOrCreate([
                            'user_id' => $user->id,
                            'review_id' => $review->id,
                        ]);
                    }
                }
            }
        }
    }
}