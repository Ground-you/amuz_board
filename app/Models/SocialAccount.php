<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    // 'user_id'를 추가하여 대량 할당(Mass Assignment)이 가능하게 합니다.
    protected $fillable = ['user_id', 'provider', 'provider_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}