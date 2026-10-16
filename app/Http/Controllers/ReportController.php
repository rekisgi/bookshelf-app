<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ユーザーの全レビューを取得
        $reviews = $user ? $user->reviews : collect();

        // 評価分布（1〜5）を集計
        $ratingDistribution = collect([5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0]);
        if ($reviews->isNotEmpty()) {
            $counts = $reviews->groupBy('rating')->map->count();
            $ratingDistribution = $ratingDistribution->merge($counts);
        }

        $stats = [
            'total_books'     => $user ? $user->favoriteBooks()->count() : 0,
            'total_reviews'   => $reviews->count(),
            'total_favorites' => $user ? $user->favoriteBooks()->count() : 0,
            'reading_plans'   => $user ? $user->readingPlans()->count() : 0,
            'summary' => [
                'total_reviews'  => $reviews->count(),
                'books_read'     => $user ? $user->favoriteBooks()->count() : 0,
                'average_rating' => $reviews->avg('rating') ? round($reviews->avg('rating'), 1) : 0,
                'total_books'    => $user ? $user->favoriteBooks()->count() : 0,
                'read_count'     => $user ? $user->favoriteBooks()->count() : 0,
                'review_count'   => $reviews->count(),
            ],
            'rating_distribution' => $ratingDistribution,
            'top_rated_books'     => collect(),
            'genre_ratings'       => collect(),
            'monthly_trends'      => collect(),
            'monthly'             => collect(),
            'genres'              => collect(),
        ];

        return view('reports.index', compact('stats'));
    }
}