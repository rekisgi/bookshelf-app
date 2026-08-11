<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index()
    {
        // レビューの平均評価が高い順（TOP 10）で本を取得
        $rankedBooks = Book::withAvg('reviews', 'rating') // reviews テーブルの rating カラムの平均を計算（reviews_avg_rating が作られる）
            ->withCount('reviews')                         // レビュー件数を取得（reviews_count が作られる）
            ->having('reviews_count', '>', 0)              // レビューが1件以上ある本に絞り込む
            ->orderBy('reviews_avg_rating', 'desc')        // 平均評価が高い順に並び替え
            ->take(10)                                     // 上位10件
            ->get();

        return view('ranking.index', compact('rankedBooks'));
    }
}