<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // =========================================================
    // ここから下が今回新しく追加した「連携プレイ（リレーション）」だよ！
    // =========================================================

    // 1. ユーザーが登録した本たち（1対多）
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // 2. ユーザーが書いたレビューたち（1対多）
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // 3. ユーザーが登録したお気に入り（1対多）
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // 4. ユーザーが押した「いいね」（1対多）
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 5. ユーザーがお気に入り登録した本たち（多対多）
    public function favoriteBooks()
    {
        return $this->belongsToMany(Book::class, 'favorites')->withTimestamps();
    }

    // 6. ユーザーがいいねしたレビューたち（多対多）
    public function likedReviews()
    {
        return $this->belongsToMany(Review::class, 'likes')->withTimestamps();
    }

    // 7. ユーザーの読書計画（1対多）
    public function readingPlans()
    {
        return $this->hasMany(ReadingPlan::class);
    }
}