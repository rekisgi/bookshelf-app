<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\GenreController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// トップページ（アクセスしたら本の一覧画面へ）
Route::get('/', [BookController::class, 'index'])->name('books.index');

// 本に関するルーティング（一覧、詳細、登録、編集、削除）
Route::resource('books', BookController::class);

// レビュー投稿
Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// お気に入り登録・解除
Route::post('/books/{book}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');
Route::delete('/books/{book}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

// 例：ログイン画面のルートがある場合
Route::get('/login', function () {
    return 'ログイン画面（準備中）';
})->name('login');

Route::post('/reviews/{review}/like', [LikeController::class, 'store'])->name('reviews.like');

Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');

Route::resource('genres', GenreController::class);