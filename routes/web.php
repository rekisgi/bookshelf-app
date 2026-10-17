<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReadingPlanController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// トップページ（/）を書籍一覧にする
Route::get('/', [BookController::class, 'index'])->name('books.index');

// /books にアクセスしても書籍一覧へ
Route::get('/books', [BookController::class, 'index']);

// ランキング画面
Route::get('/ranking', [BookController::class, 'ranking'])->name('ranking.index');

// Breezeのダッシュボードルート
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ログイン・認証が必要な機能
Route::middleware('auth')->group(function () {

    Route::get('/books/isbn/{isbn}', [BookController::class, 'searchByIsbn'])->name('books.isbn');
    // 書籍関連（登録・詳細・レビュー等）
    Route::resource('books', BookController::class)->except(['index']);

    // レビュー機能（投稿・編集・更新・削除・いいね）
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::resource('reviews', ReviewController::class)->only(['edit', 'update', 'destroy']);
    Route::post('/reviews/{review}/like', [ReviewController::class, 'like'])->name('reviews.like');

    // お気に入り機能（一覧・登録・削除）
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/books/{book}/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/books/{book}/favorites', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // ジャンル管理機能
    Route::resource('genres', GenreController::class);

    // プロフィール管理（Breeze既存）
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 読書レポート機能
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // 読書計画機能
    Route::resource('reading-plans', ReadingPlanController::class);
    Route::post('/reading-plans/{reading_plan}/complete', [ReadingPlanController::class, 'complete'])->name('reading-plans.complete');

    // 通知機能
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

require __DIR__.'/auth.php';