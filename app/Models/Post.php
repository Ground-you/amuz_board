<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // DB에 저장 허용할 컬럼들 추가
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // 현재 로그인한 사용자가 좋아요를 눌렀는지 확인하는 함수
    public function isLiked()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
    
}