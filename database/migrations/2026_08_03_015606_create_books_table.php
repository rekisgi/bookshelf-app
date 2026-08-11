<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 登録したユーザーID
            $table->string('title'); // タイトル
            $table->string('author'); // 著者
            $table->string('isbn'); // ISBN番号
            $table->date('published_date'); // 出版年
            $table->text('description')->nullable(); // あらすじ（要件次第でnullableに）
            $table->string('image_url')->nullable(); // 書籍の画像URL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
