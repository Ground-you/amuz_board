<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    // 1. 소셜 인증 요청
    public function redirectToProvider($provider = 'github')
    {
        // 사용자가 연동 시 매번 계정을 선택하도록 강제하려면 'consent' 옵션을 주석 해제하세요.
        return Socialite::driver($provider)
            // ->with(['prompt' => 'consent']) 
            ->redirect();
    }

    // 2. 콜백 처리
    public function handleProviderCallback($provider = 'github')
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', '소셜 인증에 실패했습니다.');
        }

        // A. 이미 이 소셜 계정 정보가 DB에 있는지 확인
        $account = SocialAccount::where('provider', $provider)
                                ->where('provider_id', $socialUser->getId())
                                ->first();

        if ($account) {
            // 중복 방지: 이미 다른 유저가 이 소셜 계정을 가져갔는지 체크
            if (Auth::check() && $account->user_id !== Auth::id()) {
                return redirect('/login')->with('error', '해당 소셜 계정은 이미 다른 유저와 연동되어 있습니다.');
            }
            
            // 로그인 처리
            Auth::login($account->user);
            return redirect('/posts');
        }

        // B. 연동은 안 되어있지만, 이메일로 기존 유저 찾기
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // C. 완전히 신규 유저 생성 (비밀번호 null)
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'email' => $socialUser->getEmail(),
                'password' => null, 
            ]);
        }

        // D. 소셜 계정 정보 등록 (연동)
        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
        ]);

        Auth::login($user);
        return redirect('/posts');
    }

    // 3. 연동 해제
    public function disconnect($provider)
    {
        $user = auth()->user();
        $account = $user->socialAccounts()->where('provider', $provider)->first();

        if ($account) {
            // 보안 체크: 마지막 수단(소셜+비번없음) 해제 방지
            if ($user->socialAccounts()->count() <= 1 && !$user->password) {
                 return back()->with('error', '마지막 소셜 계정을 해제하려면 먼저 비밀번호를 설정해주세요.');
            }

            $account->delete();
            return back()->with('success', ucfirst($provider) . ' 연동이 해제되었습니다.');
        }

        return back()->with('error', '해당 연동 정보를 찾을 수 없습니다.');
    }
}