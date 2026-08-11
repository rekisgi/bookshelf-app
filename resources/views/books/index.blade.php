<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>本の一覧 - Bookshelf</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">📚 本の一覧</h1>
            <a href="{{ route('books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + 新しい本を登録
            </a>
        </div>

        {{-- 登録成功メッセージの表示 --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

       {{-- 本のカード一覧 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($books as $book)
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    {{-- ★ここを <a> タグで囲んで青色・アンダーライン付きのリンクにする --}}
                    <h2 class="text-xl font-bold text-blue-600 hover:underline mb-1">
                        <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                    </h2>
                    <p class="text-sm text-gray-600 mb-4">著者: {{ $book->author }}</p>
                    <p class="text-gray-700 mb-4">{{ Str::limit($book->description, 100) }}</p>
                </div>
            @empty
                <p class="text-gray-500 col-span-2">まだ登録された本がありません。上のボタンから追加してみよう！</p>
            @endforelse
        </div>
    </div>
</body>
</html>