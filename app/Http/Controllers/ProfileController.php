<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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

    public function destroy(Request $request)
    {
        // 1. 비밀번호 확인
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // 2. 로그아웃 처리
        Auth::logout();

        // 3. 데이터 삭제
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', '계정이 삭제되었습니다.');
    }
}