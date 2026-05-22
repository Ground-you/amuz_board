<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

// --- [이메일 인증 관련 필수 라우트 고정 주소] ---

// 1. 인증 안내 화면 주소 (라라벨 내장 이름 필수)
Route::get('/email/verify', [AuthController::class, 'showVerifyEmail'])
    ->middleware('auth')
    ->name('verification.notice');

// 2. 사용자가 메일함에서 링크를 클릭했을 때 검증하는 주소
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // 이메일을 인증 완료 상태로 변경
    return redirect('/posts'); // 완료 후 메인 피드로 이동
})->middleware(['auth', 'signed'])->name('verification.verify');

// 3. 인증 메일 재발송 요청 주소 (디바운싱/디토스 방지락 탑재)
Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// routes/web.php 내 이메일 인증 안내 화면
Route::get('/email/verify', [AuthController::class, 'showVerifyEmail'])
    ->middleware('auth')
    ->name('verification.notice');


// --- [인가(Authorization) 미들웨어 자물쇠 적용] ---
// 기존 라우트 중 'auth' 뒤에 'verified'를 추가해 주면 이메일 인증 안 한 사람은 진입이 자동 차단됩니다.

// 글쓰기 화면 및 저장 보호
Route::get('/posts/create', [PostController::class, 'create'])->middleware(['auth', 'verified'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->middleware(['auth', 'verified'])->name('posts.store');

// 좋아요(하트) 토글 기능 보호
Route::post('/likes/{type}/{id}', [LikeController::class, 'toggle'])->middleware(['auth', 'verified']);
