<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'isbn',
        'published_date',
        'description',
        'image_url',
    ];

    // 本を登録したユーザー（1対多の逆）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 本につけられたジャンル（多対多）
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genres');
    }

    // 本につけられたレビュー（1対多）
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // お気に入り登録（1対多）
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}