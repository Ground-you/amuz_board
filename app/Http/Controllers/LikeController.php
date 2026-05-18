<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request, $type, $id)
    {
        // 대상이 post인지 comment인지 판별하고 올바른 모델 클래스 매칭
        $modelName = $type === 'post' ? Post::class : Comment::class;
        $model = $modelName::findOrFail($id);

        // 현재 로그인한 유저가 이미 좋아요를 눌렀는지 확인
        $like = $model->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete(); // 이미 눌렀다면 좋아요 취소
            $liked = false;
        } else {
            // 안 눌렀다면 새로운 좋아요 생성
            $model->likes()->create([
                'user_id' => Auth::id()
            ]);
            $liked = true;
        }

        // Inertia 환경에서는 처리 후 보통 이전 페이지로 리다이렉트합니다.
        return back();
    }
}