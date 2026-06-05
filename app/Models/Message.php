<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // 대량으로 데이터를 저장할 수 있도록 허용할 칼럼들
    protected $fillable = ['user_id', 'message'];

    // [관계 설정] 이 메시지는 "한 명의 유저"에게 속해 있습니다.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}