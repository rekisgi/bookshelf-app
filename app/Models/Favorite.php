<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    // 誰がお気に入りしたか
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // どの本をお気に入りしたか
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}