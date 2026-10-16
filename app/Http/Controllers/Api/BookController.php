<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    // ① 書籍一覧（検索・絞り込み・ページネーション・平均評価等を含む）
    public function index(Request $request)
    {
        $query = Book::with('genres')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // キーワード検索
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('author', 'like', "%{$keyword}%");
            });
        }

        // ジャンル絞り込み
        if ($request->filled('genre_id')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genres.id', $request->genre_id);
            });
        }

        // 10件ずつのページネーションを取得
        $books = $query->latest()->paginate(10);

        return response()->json($books, 200);
    }

    // ② 書籍詳細
    public function show($id)
    {
        $book = Book::with(['genres', 'reviews.user'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->find($id);

        if (!$book) {
            return response()->json([
                'message' => '指定された書籍が見つかりません。'
            ], 404);
        }

        return response()->json($book, 200);
    }

    // ③ 書籍新規登録
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'isbn'         => 'required|string|size:13|unique:books,isbn',
            'published_at' => 'required|date',
            'description'  => 'nullable|string',
            'image_url'    => 'nullable|url',
            'genres'       => 'required|array',
            'genres.*'     => 'exists:genres,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラーが発生しました。',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $book = Book::create([
            'user_id'        => auth()->id() ?? 1,
            'title'          => $validated['title'],
            'author'         => $validated['author'],
            'isbn'           => $validated['isbn'],
            'published_date' => $validated['published_at'],
            'description'    => $validated['description'] ?? null,
            'image_url'      => $validated['image_url'] ?? null,
        ]);

        if (!empty($validated['genres'])) {
            $book->genres()->sync($validated['genres']);
        }

        return response()->json([
            'message' => '書籍を正常に登録しました。',
            'data'    => $book->load('genres')
        ], 201);
    }

    // ④ 書籍更新
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => '指定された書籍が見つかりません。'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'isbn'         => 'required|string|size:13|unique:books,isbn,' . $book->id,
            'published_at' => 'required|date',
            'description'  => 'nullable|string',
            'image_url'    => 'nullable|url',
            'genres'       => 'required|array',
            'genres.*'     => 'exists:genres,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラーが発生しました。',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $book->update([
            'title'          => $validated['title'],
            'author'         => $validated['author'],
            'isbn'           => $validated['isbn'],
            'published_date' => $validated['published_at'],
            'description'    => $validated['description'] ?? null,
            'image_url'      => $validated['image_url'] ?? null,
        ]);

        if (isset($validated['genres'])) {
            $book->genres()->sync($validated['genres']);
        }

        return response()->json([
            'message' => '書籍情報を更新しました。',
            'data'    => $book->load('genres')
        ], 200);
    }

    // ⑤ 書籍削除
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => '指定された書籍が見つかりません。'
            ], 404);
        }

        $book->delete();

        return response()->json([
            'message' => '書籍を削除しました。'
        ], 200);
    }
}