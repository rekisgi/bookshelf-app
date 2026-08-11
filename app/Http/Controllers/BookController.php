<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Genre;

class BookController extends Controller
{
    // ① 本の一覧表示
    public function index()
    {
        // リレーション（genres）も一緒に読み込んでおくと表示が軽くなるよ
        $books = Book::with('genres')->latest()->get();
        return view('books.index', compact('books'));
    }

    // ② 本の新規登録画面の表示
    public function create()
    {
        $genres = Genre::all();
        return view('books.create', compact('genres'));
    }

    // ③ 本の保存処理（★ジャンル保存を追加）
    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

        // 本を作成
        $book = Book::create([
            'user_id'        => auth()->id() ?? 1, // ログインしていればそのID、いなければ1
            'title'          => $request->title,
            'author'         => $request->author,
            'isbn'           => $request->isbn ?? '',
            'published_date' => $request->published_date ?? now()->format('Y-m-d'),
            'description'    => $request->description,
            'image_url'      => $request->image_url ?? '',
        ]);

        // 選択されたジャンルを中間テーブルに保存（sync）
        if ($request->has('genres')) {
            $book->genres()->sync($request->genres);
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

    // ⑥ 本の更新処理（★ジャンル更新を追加）
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

        $book->update([
            'title'          => $request->title,
            'author'         => $request->author,
            'isbn'           => $request->isbn,
            'published_date' => $request->published_date,
            'description'    => $request->description,
            'image_url'      => $request->image_url,
        ]);

        // ジャンルの紐付けを更新（何も選択されていない場合は空の配列で解除）
        $book->genres()->sync($request->input('genres', []));

        return redirect()->route('books.show', $book)->with('success', '本情報を更新しました！');
    }

    // ⑦ 本の削除処理
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', '本を削除しました！');
    }
}