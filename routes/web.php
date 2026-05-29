<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialController;

// 1. [누구나 접근 가능] 소셜 로그인 관련 라우트 (미들웨어 밖으로 완전히 분리)
Route::get('/auth/{provider}', [SocialController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialController::class, 'handleProviderCallback']);

// 2. [로그인 안 한 사람만 접근] 로그인 및 회원가입 페이지
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 3. [로그인 한 사람만 접근] 인증 필수 페이지 (verified 미들웨어)
Route::middleware(['auth', 'verified'])->group(function () {
    // 게시글 작성, 수정, 삭제 등 (인증된 사람만 가능)
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // 댓글 및 좋아요
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/likes/{type}/{id}', [LikeController::class, 'toggle']);

    // 프로필 및 연동 해제
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/auth/disconnect/{provider}', [SocialController::class, 'disconnect'])->name('auth.disconnect');
    
    // 계정 삭제 추가
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


  // 로그인 필수, 인증 여부 무관 로그아웃, 이메일 수정 등
Route::middleware(['auth'])->group(function () {
    // 이메일 수정 페이지 및 처리
    Route::patch('/profile/email', [AuthController::class, 'updateEmail'])->name('profile.email.update');
    // 로그아웃
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// 4. [인증 정보 없는 사람도 볼 수 있는 페이지]
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// 5. [이메일 인증 필수 라우트들]
Route::get('/email/verify', [AuthController::class, 'showVerifyEmail'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    // 이메일 인증 완료 후 처리
    $user = \App\Models\User::findOrFail($id);
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }
    return redirect('/posts');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//라라벨 라우트 리다이렉트
Route::get('/', function () {
    return redirect('/posts');
});