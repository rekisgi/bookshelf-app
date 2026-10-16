<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ランキング') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-8">
                <h3 class="text-base font-bold text-gray-800 mb-6">評価ランキング TOP10</h3>

                @if($books->isEmpty())
                    <p class="text-gray-500 py-4 text-center">まだ評価された書籍がありません。</p>
                @else
                    <div class="space-y-3">
                        @foreach($books as $index => $book)
                            @php
                                $rank = $index + 1;
                                $avgRating = number_format($book->reviews_avg_rating ?? 0, 1);
                                $avgRatingFull = number_format($book->reviews_avg_rating ?? 0, 2);

                                // 1〜3位と4位以降で順位バッジと背景色を切り替え
                                if ($rank === 1) {
                                    $badgeBg = 'bg-yellow-400 text-white';
                                    $rowBg = 'bg-amber-50/60';
                                } elseif ($rank === 2) {
                                    $badgeBg = 'bg-slate-300 text-white';
                                    $rowBg = 'bg-amber-50/60';
                                } elseif ($rank === 3) {
                                    $badgeBg = 'bg-amber-600 text-white';
                                    $rowBg = 'bg-amber-50/60';
                                } else {
                                    $badgeBg = 'bg-gray-100 text-gray-600 border border-gray-200';
                                    $rowBg = 'bg-white hover:bg-gray-50';
                                }
                            @endphp

                            <div class="flex items-center justify-between p-4 rounded-lg transition-colors {{ $rowBg }}">
                                <div class="flex items-center space-x-4">
                                    <!-- 順位バッジ（丸型） -->
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0 {{ $badgeBg }}">
                                        {{ $rank }}
                                    </div>

                                    <!-- 書籍画像（サムネイル） -->
                                    <div class="w-12 h-16 bg-gray-100 rounded overflow-hidden shrink-0 flex items-center justify-center border border-gray-200">
                                        @if($book->image_url)
                                            <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs text-gray-400">No Image</span>
                                        @endif
                                    </div>

                                    <!-- 書籍情報 -->
                                    <div>
                                        <a href="{{ route('books.show', $book) }}" class="text-base font-bold text-blue-600 hover:underline block">
                                            {{ $book->title }}
                                        </a>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ $book->author }}
                                        </div>
                                        <div class="flex items-center space-x-2 text-xs text-gray-400 mt-1">
                                            <span class="text-amber-400">★ ★ ★ ★ ★</span>
                                            <span class="text-amber-500 font-semibold">{{ $avgRatingFull }}</span>
                                            @if(isset($book->reviews_count))
                                                <span>（{{ $book->reviews_count }}件のレビュー）</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- 評価スコア（右側） -->
                                <div class="text-right shrink-0">
                                    <div class="text-xl font-bold text-amber-500">
                                        {{ $avgRating }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        平均評価
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>