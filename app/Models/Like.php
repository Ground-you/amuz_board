<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    // 대량 할당 허용 (user_id 등을 저장하기 위함)
    protected $fillable = ['user_id', 'likeable_id', 'likeable_type'];

    // 어떤 모델(Post 또는 Comment)에 좋아요가 눌렸는지 정의 (다형성 관계)
    public function likeable()
    {
        return $this->morphTo();
    }

    // 좋아요를 누른 유저 정보
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}