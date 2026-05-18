<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like; // 👈 이 줄이 있는지 확인!

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'image_path', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 좋아요 다대다 모피즘 관계 설정
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}