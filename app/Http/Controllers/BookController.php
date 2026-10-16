<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    // ① 本の一覧表示（検索・絞り込み・ソート対応）
    public function index(Request $request)
    {
        // クエリビルダの準備 ＆ リレーション（genres）のEagerロード
        $query = Book::with('genres');

        // 1. キーワード検索（タイトルまたは著者名に部分一致）
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('author', 'like', "%{$keyword}%");
            });
        }

        // 2. ジャンル絞り込み（genre および genre_id パラメータの両方に対応）
        $genreId = $request->input('genre') ?? $request->input('genre_id');
        if (!empty($genreId)) {
            $query->whereHas('genres', function($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }

        // 3. 並び順（ソート）の判定
        $sort = $request->input('sort');
        if ($sort === 'title') {
            // タイトル順（五十音・アルファベット昇順）
            $query->orderBy('title', 'asc');
        } elseif ($sort === 'rating') {
            // レビュー平均評価順（評価が高い順）
            $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
        } elseif ($sort === 'reviews_count') {
            // レビュー件数順
            $query->withCount('reviews')->orderByDesc('reviews_count');
        } else {
            // デフォルト（新着順）
            $query->latest();
        }

        // 検索結果を取得（ページネーション適用＆クエリ文字列保持）
        $books = $query->paginate(10)->withQueryString();

        // 検索用セレクトボックスで使うために全ジャンルを取得
        $genres = Genre::all();

        return view('books.index', compact('books', 'genres'));
    }

    // ② 本の新規登録画面の表示（★復活させました）
    public function create()
    {
        $genres = Genre::all();
        return view('books.create', compact('genres'));
    }

    // ③ 本の保存処理（FormRequestを適用）
    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();

        $book = Book::create([
            'user_id'        => auth()->id() ?? 1,
            'title'          => $validated['title'],
            'author'         => $validated['author'],
            'isbn'           => $validated['isbn'],
            // published_at と published_date の両方から安全に値を取得
            'published_date' => $validated['published_at'] ?? $validated['published_date'] ?? null,
            'description'    => $validated['description'] ?? null,
            'image_url'      => $validated['image_url'] ?? null,
        ]);

        if (!empty($validated['genres'])) {
            $book->genres()->sync($validated['genres']);
        }

        return redirect()->route('books.index')->with('success', '本を登録しました！');
    }

    // ④ 本の詳細表示
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    // ⑤ 本の編集画面表示
    public function edit(Book $book)
    {
        $genres = Genre::all(); 
        return view('books.edit', compact('book', 'genres')); 
    }

    // ⑥ 本の更新処理（FormRequestを適用）
    public function update(UpdateBookRequest $request, Book $book)
    {
        // バリデーション済みの安全なデータを取得
        $validated = $request->validated();

        $book->update([
            'title'          => $validated['title'],
            'author'         => $validated['author'],
            'isbn'           => $validated['isbn'],
            'published_date' => $validated['published_at'] ?? $validated['published_date'] ?? null,
            'description'    => $validated['description'] ?? null,
            'image_url'      => $validated['image_url'] ?? null,
        ]);

        // ジャンルの紐付けを更新
        $book->genres()->sync($validated['genres'] ?? []);

        return redirect()->route('books.show', $book)->with('success', '本情報を更新しました！');
    }

    // ⑦ 本の削除処理
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', '本を削除しました！');
    }

    // ⑧ ランキング表示
    public function ranking()
    {
        // レビューの平均評価と件数を取得して、評価の高い順に上位10件を取得
        $books = Book::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();

        return view('books.ranking', compact('books'));
    }

   // ⑨ ISBN検索処理（Google Books API連携＋OpenBDフォールバック）
    public function searchByIsbn($isbn)
    {
        // ハイフンなどを除外して数字のみにする
        $cleanIsbn = preg_replace('/[^0-9X]/i', '', $isbn);

        // 1. まず Google Books API へリクエスト
        $response = Http::withoutVerifying() // SSL証明書エラーを回避
            ->get("https://www.googleapis.com/books/v1/volumes?q=isbn:{$cleanIsbn}");

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['items'][0]['volumeInfo'])) {
                $info = $data['items'][0]['volumeInfo'];

                $publishedDate = $info['publishedDate'] ?? '';
                if (strlen($publishedDate) === 4) {
                    $publishedDate .= '-01-01';
                }

                return response()->json([
                    'success'        => true,
                    'title'          => $info['title'] ?? '',
                    'author'         => isset($info['authors']) ? implode(', ', $info['authors']) : '',
                    'published_date' => $publishedDate,
                    'description'    => $info['description'] ?? '',
                    'image_url'      => $info['imageLinks']['thumbnail'] ?? '',
                ]);
            }
        }

        // 2. Google Books API で見つからない場合、日本の書籍に強い OpenBD API を試す
        $openBdResponse = Http::withoutVerifying()
            ->get("https://api.openbd.jp/v1/get?isbn={$cleanIsbn}");

        if ($openBdResponse->successful()) {
            $openBdData = $openBdResponse->json();

            if (!empty($openBdData[0]['summary'])) {
                $summary = $openBdData[0]['summary'];
                
                // 出版日（YYYYMMDD を YYYY-MM-DD に変換）
                $pubDate = $summary['pubdate'] ?? '';
                if (strlen($pubDate) === 8) {
                    $pubDate = substr($pubDate, 0, 4) . '-' . substr($pubDate, 4, 2) . '-' . substr($pubDate, 6, 2);
                }

                return response()->json([
                    'success'        => true,
                    'title'          => $summary['title'] ?? '',
                    'author'         => $summary['author'] ?? '',
                    'published_date' => $pubDate,
                    'description'    => $openBdData[0]['onix']['CollateralDetail']['TextContent'][0]['Text'] ?? '',
                    'image_url'      => $summary['cover'] ?? '',
                ]);
            }
        }

        // どちらでも見つからなかった場合
        return response()->json([
            'success' => false,
            'message' => '該当する書籍情報が見つかりませんでした。'
        ], 404);
    }
}