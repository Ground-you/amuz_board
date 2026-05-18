<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'content',
        'parent_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // 부모 댓글 가져오기
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // 자식 댓글(대댓글)들 가져오기
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    public function likes()
    {
        return $this->morphMany(\App\Models\Like::class, 'likeable');
    }
}
