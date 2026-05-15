<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;

// 게시글 목록 페이지
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
// 게시글 작성 페이지 (화면 보여주기)
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
// 게시글 저장 (DB에 데이터 넣기)
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
// {post}는 게시글의 ID 값을 의미함
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
// 글 수정 페이지 (화면 보여주기)
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
// 게시글 업데이트 (실제 DB 수정)
Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
// 게시글 삭제
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
// 특정 게시글에 새로운 댓글 등록
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
// 댓글 업데이트
Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
//댓글 삭제
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
// 좋아요 토글 라우트
Route::post('/likes/{type}/{id}', [LikeController::class, 'toggle'])->middleware('auth');

// 로그인하지 않은 사용자만 접근 (guest 미들웨어)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 로그인한 사용자만 접근 (auth 미들웨어)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
