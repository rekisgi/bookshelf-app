<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍の登録 - Bookshelf</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">書籍の登録</h1>

        <form action="{{ route('books.store') }}" method="POST">
            @csrf

            {{-- タイトル --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">タイトル <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="テスト本" required class="w-full px-3 py-2 border @error('title') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 著者名 --}}
            <div class="mb-4">
                <label for="author" class="block text-sm font-medium text-gray-700 mb-1">著者名 <span class="text-red-500">*</span></label>
                <input type="text" name="author" id="author" value="{{ old('author') }}" placeholder="テスト著者" required class="w-full px-3 py-2 border @error('author') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('author')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ISBN-13 --}}
            <div class="mb-4">
                <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN-13</label>
                <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}" placeholder="1234567890123" class="w-full px-3 py-2 border @error('isbn') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                <p class="text-xs text-gray-400 mt-1">13桁のISBNコードを入力してください（任意）</p>
                @error('isbn')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 出版日 --}}
            <div class="mb-4">
                <label for="published_date" class="block text-sm font-medium text-gray-700 mb-1">出版日</label>
                <input type="date" name="published_date" id="published_date" value="{{ old('published_date') }}" class="w-full px-3 py-2 border @error('published_date') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('published_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 説明 --}}
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">説明</label>
                <textarea name="description" id="description" rows="3" placeholder="頑張りましょう🔥" class="w-full px-3 py-2 border @error('description') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 画像URL --}}
            <div class="mb-6">
                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">画像URL</label>
                <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg" class="w-full px-3 py-2 border @error('image_url') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                <p class="text-xs text-gray-400 mt-1">書籍の表紙画像のURLを入力してください（任意）</p>
                @error('image_url')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ジャンル選択 --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">ジャンル</label>
                <div class="grid grid-cols-2 gap-3 bg-gray-50 p-4 rounded-md border border-gray-200">
                    @foreach($genres as $genre)
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="genres[]" value="{{ $genre->id }}" 
                                   {{ is_array(old('genres')) && in_array($genre->id, old('genres')) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $genre->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('genres')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ボタンエリア --}}
            <div class="flex justify-end space-x-4 items-center border-t pt-4">
                <a href="{{ route('books.index') }}" class="text-sm text-gray-500 hover:underline">キャンセル</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-6 rounded-md">
                    登録
                </button>
            </div>
        </form>
    </div>
</body>
</html>