<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;

class LikeController extends Controller
{
    /**
     * 좋아요 토글 기능 (완벽 구현)
     * web.php의 /likes/{type}/{id} 주소와 매핑됩니다.
     */
    public function toggle(string $type, int $id)
    {
        // 1. 좋아요 대상이 게시글(post)인지 댓글(comment)인지 판별하여 모델을 가져옵니다.
        if ($type === 'post') {
            $likeable = Post::findOrFail($id);
        } elseif ($type === 'comment') {
            $likeable = Comment::findOrFail($id);
        } else {
            return back()->withErrors(['message' => '올바르지 않은 접근입니다.']);
        }

        // 2. 현재 로그인한 유저가 해당 게시물에 이미 좋아요를 누른 적이 있는지 확인합니다.
        $existingLike = $likeable->likes()
            ->where('user_id', auth()->id())
            ->first();

        if ($existingLike) {
            // 이미 누른 상태라면 좋아요 취소 (삭제)
            $existingLike->delete();
        } else {
            // 누른 적이 없다면 새로운 좋아요 기록 생성
            $likeable->likes()->create([
                'user_id' => auth()->id()
            ]);
        }

        // 3. Inertia 환경이므로 변경된 데이터 상태를 안고 원래 페이지로 안전하게 돌아갑니다.
        return back();
    }
}