<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 💡 Inertia 기본 미들웨어 등록
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        // ⭐️ [여기가 핵심!] 인증/인가 실패 시 라라벨이 어디로 보낼지 리다이렉트 경로를 강제로 지정합니다.
        $middleware->redirectTo(
            guests: fn () => route('login'),                     // 로그인 안 한 유저는 /login 으로
            users: fn () => route('verification.notice')        // 이메일 인증 안 한 유저는 이메일 대기창으로!
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();