<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        // 현재 가방(Request)에 들어있는 로그인 유저 정보를 Inertia 화면으로 배달합니다.
        return Inertia::render('Profile/ProfileEdit', [
            'user' => $request->user(),
        ]);
    }
}