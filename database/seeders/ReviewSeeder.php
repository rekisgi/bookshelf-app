<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Book;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        if ($users->isEmpty() || $books->isEmpty()) {
            return;
        }

        // 基本コメントのサンプル
        $comments = [
            'とても素晴らしい本でした！',
            '大変勉強になりました。何度も読み返したいです。',
            '内容が非常にわかりやすく、一気に読めました。',
            '少し難しい部分もありましたが、概ね満足です。',
            '期待通りの内容で、学びが多かったです。',
        ];

        $reviewCount = 0;
        $targetTotal = 32;

        // 32件になるまでループして生成
        while ($reviewCount < $targetTotal) {
            foreach ($books as $book) {
                if ($reviewCount >= $targetTotal) break;

                // ランダムなユーザーを選択
                $user = $users->random();

                // すでに同じユーザーが同じ本にレビューを書いているかチェック（重複防止）
                $exists = Review::where('user_id', $user->id)
                                ->where('book_id', $book->id)
                                ->exists();

                if (!$exists) {
                    Review::create([
                        'user_id' => $user->id,
                        'book_id' => $book->id,
                        'rating' => rand(3, 5), // 基本要件：3〜5
                        'comment' => $comments[array_rand($comments)],
                    ]);
                    $reviewCount++;
                }
            }
        }
    }
}