<?php

namespace App\Http\Controllers;

use App\Enums\ReadingPlanStatus;
use App\Models\Book;
use App\Models\ReadingPlan;
use Illuminate\Http\Request;

class ReadingPlanController extends Controller
{
    /**
     * 読書計画一覧を表示
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $currentStatus = $request->input('status', '');

        $query = $user ? $user->readingPlans()->with('book') : null;

        if ($query && $currentStatus) {
            $query->where('status', $currentStatus);
        }

        $readingPlans = $query ? $query->latest()->get() : collect();

        return view('reading-plans.index', compact('readingPlans', 'currentStatus'));
    }

    /**
     * 新規作成画面を表示
     */
    public function create()
    {
        $books = Book::all();
        return view('reading-plans.create', compact('books'));
    }

    /**
     * 新規作成処理
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id'     => 'required|exists:books,id',
            'target_date' => 'required|date',
            'status'      => 'nullable|string',
        ]);

        $validated['status'] = $validated['status'] ?? ReadingPlanStatus::Unstarted->value;

        auth()->user()->readingPlans()->create($validated);

        return redirect()->route('reading-plans.index')->with('success', '読書計画を作成しました。');
    }

    /**
     * 編集画面を表示
     */
    public function edit(ReadingPlan $readingPlan)
    {
        // ログインユーザー以外のアクセスを防止
        if ($readingPlan->user_id !== auth()->id()) {
            abort(403);
        }

        $books = Book::all();
        return view('reading-plans.edit', compact('readingPlan', 'books'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, ReadingPlan $readingPlan)
    {
        if ($readingPlan->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'book_id'     => 'required|exists:books,id',
            'target_date' => 'required|date',
            'status'      => 'required|string',
        ]);

        $readingPlan->update($validated);

        return redirect()->route('reading-plans.index')->with('success', '読書計画を更新しました。');
    }

    /**
     * 読了（完了）処理
     */
    public function complete(ReadingPlan $readingPlan)
    {
        if ($readingPlan->user_id !== auth()->id()) {
            abort(403);
        }

        $readingPlan->update([
            'status'       => ReadingPlanStatus::Completed->value,
            'completed_at' => now(),
        ]);

        return redirect()->route('reading-plans.index')->with('success', '読書計画を完了にしました。');
    }

    /**
     * 削除処理
     */
    public function destroy(ReadingPlan $readingPlan)
    {
        if ($readingPlan->user_id !== auth()->id()) {
            abort(403);
        }

        $readingPlan->delete();

        return redirect()->route('reading-plans.index')->with('success', '読書計画を削除しました。');
    }
}