<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'user_id',
    ];

    // どのレビューへのいいねか
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    // 誰が押したいいねか
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}