<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // 유효성 검사
        $request->validate([
            'content' => 'required|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // 댓글 저장
        // 현재 로그인이 구현 안 되어 있으니 일단 DB에 있는 1번 유저로 저장
        $post->comments()->create([
            'user_id' => auth()->id(), 
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        // 3. 작업이 끝나면 다시 이전 페이지로 돌아가기
        return back();
    }
    public function update(Request $request, Comment $comment)
    {
        // 작성자 본인 확인 로직이 나중에 필요하지만 추후 추가예정
        $request->validate(['content' => 'required']);
        
        $comment->update(['content' => $request->content]);

        return back(); // 상세 페이지 유지
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back();
    }
}