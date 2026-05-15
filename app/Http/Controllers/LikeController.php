<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;    
use App\Models\Comment;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function toggle(Request $request, $type, $id)
    {
        $modelName = $type === 'post' ? Post::class : Comment::class;
        $model = $modelName::findOrFail($id);

        $like = $model->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            $model->likes()->create(['user_id' => auth()->id()]);
            $status = 'liked';
        }

        return back()->with('status', $status);
    }
}
