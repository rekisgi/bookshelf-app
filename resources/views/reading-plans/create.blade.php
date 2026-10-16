<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            新規読書計画作成
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('reading-plans.store') }}">
                    @csrf

                    <!-- 書籍選択 -->
                    <div class="mb-4">
                        <label for="book_id" class="block text-sm font-medium text-gray-700 mb-1">
                            書籍 <span class="text-red-500">*</span>
                        </label>
                        <select name="book_id" id="book_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            <option value="">選択してください</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 期日 -->
                    <div class="mb-6">
                        <label for="target_date" class="block text-sm font-medium text-gray-700 mb-1">
                            期日 <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="target_date" id="target_date" value="{{ old('target_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                        @error('target_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- デフォルト状態設定（未読） -->
                    <input type="hidden" name="status" value="unstarted">

                    <!-- ボタン領域 -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('reading-plans.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            キャンセル
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                            登録
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>