<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        // 1. 현재 로그인한 유저를 가져옵니다.
        $user = $request->user();

        // 2. 해당 유저가 연동한 소셜 계정의 provider 이름들만 배열로 뽑아냅니다.
        // 예: ['github', 'google']
        $linkedAccounts = $user->socialAccounts()->pluck('provider')->toArray();

        return Inertia::render('Profile/ProfileEdit', [
            'user' => $user,
            'linkedAccounts' => $linkedAccounts, // 이 부분을 추가해야 합니다!
        ]);
    }
}