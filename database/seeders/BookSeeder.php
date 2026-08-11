<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // 基本要件：山田太郎（User::first()）を登録者とする
        $user = User::first();

        // ※もし応用要件（★）も満たすなら、全ユーザーを取得してランダム割り当て
        // $users = User::all();

        // 11件の書籍データ定義
        $booksData = [
            [
                'num' => 1,
                'title' => '吾輩は猫である',
                'author' => '夏目漱石',
                'isbn' => '9784101010014',
                'published_date' => '1905-01-01',
                'description' => '夏目漱石の長編小説。',
                'genres' => ['小説'],
            ],
            [
                'num' => 2,
                'title' => '人を動かす',
                'author' => 'D・カーネギー',
                'isbn' => '9784422100524',
                'published_date' => '1936-10-01',
                'description' => '人間関係の原則を説いた名著。',
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'num' => 3,
                'title' => 'リーダブルコード',
                'author' => 'Dustin Boswell',
                'isbn' => '9784873115658',
                'published_date' => '2012-06-23',
                'description' => 'より良いコードを書くための実践的テクニック。',
                'genres' => ['技術書'],
            ],
            [
                'num' => 4,
                'title' => '7つの習慣',
                'author' => 'スティーブン・R・コヴィー',
                'isbn' => '9784863940246',
                'published_date' => '2013-08-30',
                'description' => '成功に関する人格主義の回復。',
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'num' => 5,
                'title' => '坊っちゃん',
                'author' => '夏目漱石',
                'isbn' => '9784101010021',
                'published_date' => '1906-04-01',
                'description' => '夏目漱石の中編小説。',
                'genres' => ['小説'],
            ],
            [
                'num' => 6,
                'title' => 'サピエンス全史',
                'author' => 'ユヴァル・ノア・ハラリ',
                'isbn' => '9784309226712',
                'published_date' => '2016-09-08',
                'description' => '人類の歴史を俯瞰する。',
                'genres' => ['歴史', '科学'],
            ],
            [
                'num' => 7,
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9784048930598',
                'published_date' => '2017-12-18',
                'description' => 'アジャイルソフトウェア達人の技。',
                'genres' => ['技術書'],
            ],
            [
                'num' => 8,
                'title' => '嫌われる勇気',
                'author' => '岸見一郎・古賀史健',
                'isbn' => '9784478025819',
                'published_date' => '2013-12-13',
                'description' => 'アドラー心理学の教え。',
                'genres' => ['自己啓発'],
            ],
            [
                'num' => 9,
                'title' => '火花',
                'author' => '又吉直樹',
                'isbn' => '9784163902302',
                'published_date' => '2015-03-11',
                'description' => '芥川賞受賞の青春小説。',
                'genres' => ['小説'],
            ],
            [
                'num' => 10,
                'title' => 'FACTFULNESS',
                'author' => 'ハンス・ロスリング',
                'isbn' => '9784822289607',
                'published_date' => '2019-01-11',
                'description' => 'データに基づいた世界の見方。',
                'genres' => ['ビジネス', '科学'],
            ],
            [
                'num' => 11,
                'title' => 'コンテナ物語',
                'author' => 'マルク・レビンソン',
                'isbn' => '9784822251468',
                'published_date' => '2007-01-18',
                'description' => '世界を変えた箱の歴史。',
                'genres' => ['ビジネス', '歴史'],
            ],
        ];

        foreach ($booksData as $data) {
            // ISBN重複防止のため firstOrCreate を使用
            $book = Book::firstOrCreate(
                ['isbn' => $data['isbn']], // 検索条件
                [
                    'user_id' => $user->id, // ※応用要件なら $users->random()->id
                    'title' => $data['title'],
                    'author' => $data['author'],
                    'published_date' => $data['published_date'],
                    'description' => $data['description'],
                    'image_url' => "https://placehold.co/200x300/e2e8f0/475569?text={$data['num']}",
                ]
            );

            // ジャンルIDを取得して sync で紐付け
            $genreIds = Genre::whereIn('name', $data['genres'])->pluck('id');
            $book->genres()->sync($genreIds);
        }
    }
}