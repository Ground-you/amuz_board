<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        // 작성자(user) 정보를 미리 함께 가져와서(with) 성능 최적화
        $posts = \App\Models\Post::with('user')->latest()->paginate(10);

        return Inertia::render('Posts/Index', ['posts' => $posts]);
    }
    public function create()
    {
        // resources/js/Pages/Posts/Create.vue 파일을 보여주기(render)
        return Inertia::render('Posts/Create');
    }

public function store(Request $request)
{
    // 1. 유효성 검사
    $validated = $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
        'image' => 'nullable|image|max:8192', // 이미지 파일이고 8MB 이하인지 확인
    ]);

    // 2. 이미지 파일이 있으면 서버에 저장하고 경로 가져오기
    if ($request->hasFile('image')) {
        // storage/app/public/posts 폴더에 저장됨
        $path = $request->file('image')->store('posts', 'public');
        $validated['image_path'] = $path; // DB에 저장할 경로 추가
    }

    // 3. DB에 저장 (작성자 ID는 임시로 1)
    \App\Models\Post::create([
        'user_id' => 1, 
        'title' => $validated['title'],
        'content' => $validated['content'],
        'image_path' => $validated['image_path'] ?? null, // 경로가 없으면 null
    ]);

    // 4. 저장이 끝나면 목록 페이지로 이동
    return redirect()->route('posts.index');
}

    public function show(\App\Models\Post $post)
    {
        // 작성자 정보(user)도 함께 불러와서 전달해줄게.
        return Inertia::render('Posts/Show', [
            'post' => $post->load('user', 'comments.user')
        ]);
    }
    //----------------------------------------------------------

    public function edit(\App\Models\Post $post)
    {
        // 수정할 글 데이터를 가지고 수정 페이지로 이동
        return Inertia::render('Posts/Edit', [
            'post' => $post
        ]);
    }

    public function update(Request $request, \App\Models\Post $post)
    {
        // 1. 유효성 검사
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:8192',
        ]);
    // 새로운 이미지가 업로드된 경우
        if ($request->hasFile('image')) {
            // 1. 기존 이미지가 있다면 서버에서 삭제 (용량 관리)
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }

            // 2. 새 이미지 저장
            $path = $request->file('image')->store('posts', 'public');
            $validated['image_path'] = $path;
        }

        // 2. 데이터 업데이트
        $post->update($validated);

        // 3. 다시 상세 페이지로 이동
        return redirect()->route('posts.show', $post->id);
    }

    public function destroy(\App\Models\Post $post)
    {
        // 게시글 삭제
        $post->delete();

        // 목록 페이지로 이동
        return redirect()->route('posts.index');
    }
    //------------------------------------------------------------

    

}
