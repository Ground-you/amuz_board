<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth; // ⚠️ 맨 위에 이 줄이 없다면 꼭 추가해주세요!

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            // 👈 이 auth 부분을 아래와 같이 완벽하게 매칭시켜 줍니다!
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null,
            ],
            // 혹시 기존에 플래시 메시지 등이 있다면 아래에 유지됩니다.
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
        ]);
    }
}