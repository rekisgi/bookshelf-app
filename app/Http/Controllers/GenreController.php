<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    // ① ジャンル一覧（書籍数もカウントして取得）
    public function index()
    {
        $genres = Genre::withCount('books')->get();
        return view('genres.index', compact('genres'));
    }

    // ② 新規登録画面の表示
    public function create()
    {
        return view('genres.create');
    }

    // ③ 保存処理
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:genres,name',
        ], [
            'name.required' => 'ジャンル名を入力してください。',
            'name.unique'   => 'そのジャンル名は既に登録されています。',
        ]);

        Genre::create([
            'name' => $request->name,
        ]);

        return redirect()->route('genres.index')->with('success', 'ジャンルを登録しました！');
    }

    // ④ 詳細画面（特定ジャンルの本一覧など）
    public function show(Genre $genre)
    {
        $genre->load('books');
        return view('genres.show', compact('genre'));
    }

    // ⑤ 編集画面の表示
    public function edit(Genre $genre)
    {
        return view('genres.edit', compact('genre'));
    }

    // ⑥ 更新処理
    public function update(Request $request, Genre $genre)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,' . $genre->id,
        ], [
            'name.required' => 'ジャンル名を入力してください。',
            'name.unique'   => 'そのジャンル名は既に登録されています。',
        ]);

        $genre->update([
            'name' => $request->name,
        ]);

        return redirect()->route('genres.index')->with('success', 'ジャンル名を更新しました！');
    }

    // ⑦ 削除処理
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return redirect()->route('genres.index')->with('success', 'ジャンルを削除しました！');
    }
}