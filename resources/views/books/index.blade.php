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

        {{-- 🔍 検索・絞り込みフォーム --}}
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
            <form action="{{ route('books.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end" novalidate>
                
                {{-- キーワード入力 --}}
                <div class="w-full md:w-1/2">
                    <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">キーワード（タイトル・著者）</label>
                    <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}" 
                        placeholder="検索したいワードを入力" 
                        class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- ジャンル選択 --}}
                <div class="w-full md:w-1/3">
                    <label for="genre_id" class="block text-sm font-medium text-gray-700 mb-1">ジャンル</label>
                    <select name="genre_id" id="genre_id" 
                        class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">すべてのジャンル</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ボタン群 --}}
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition">
                        検索
                    </button>
                    
                    {{-- 検索中のみ「クリア」ボタンを表示 --}}
                    @if(request('keyword') || request('genre_id'))
                        <a href="{{ route('books.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow transition flex items-center">
                            クリア
                        </a>
                    @endif
                </div>

            </form>
        </div>

        {{-- 本のカード一覧 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($books as $book)
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <h2 class="text-xl font-bold text-blue-600 hover:underline mb-1">
                        <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                    </h2>
                    <p class="text-sm text-gray-600 mb-2">著者: {{ $book->author }}</p>
                    
                    {{-- 紐づいているジャンルバッジの表示 --}}
                    @if($book->genres->isNotEmpty())
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach($book->genres as $genre)
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded">
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <p class="text-gray-700 mb-4">{{ Str::limit($book->description, 100) }}</p>
                </div>
            @empty
                <p class="text-gray-500 col-span-2">該当する本が見つかりませんでした。</p>
            @endforelse
        </div>
    </div>
</body>
</html>