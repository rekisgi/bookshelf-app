<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
        'comment',
    ];

    // どの本に書かれたレビューか（1対多の逆）
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // 誰が書いたレビューか（1対多の逆）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // このレビューについた「いいね」（1対多）
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedByUsers()
{
    return $this->belongsToMany(User::class, 'likes');
}
}