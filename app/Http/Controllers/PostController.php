<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // 작성자(user) 정보를 미리 함께 가져와서(with) 성능을 최적화합니다.
        $posts = \App\Models\Post::with('user')->latest()->get();

        return view('posts.index', compact('posts'));
    }
}
