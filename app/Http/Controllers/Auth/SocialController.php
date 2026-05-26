<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    // 1. 소셜 인증 요청
    public function redirectToProvider($provider = 'github')
    {
        return Socialite::driver($provider)->redirect();
    }

    // 2. 콜백 처리
public function handleProviderCallback($provider = 'github')
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', '소셜 인증에 실패했습니다.');
        }

        // 1. 이미 로그인된 상태라면 -> 연동 처리
        if (Auth::check()) {
            $user = Auth::user();
            
            $existingAccount = SocialAccount::where('provider', $provider)
                                            ->where('provider_id', $socialUser->getId())
                                            ->first();
            
            if ($existingAccount) {
                return redirect('/profile')->with('error', '이미 다른 계정에 연동된 깃허브 계정입니다.');
            }

            $user->socialAccounts()->updateOrCreate(
                ['provider' => $provider],
                ['provider_id' => $socialUser->getId()]
            );

            return redirect('/profile')->with('success', '성공적으로 연동되었습니다.');
        }

        // 2. 로그인이 안 된 상태라면 -> 기존 연동 계정 확인
        $account = SocialAccount::where('provider', $provider)
                                ->where('provider_id', $socialUser->getId())
                                ->first();

        if ($account) {
            $user = $account->user;
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
            Auth::login($user);
            return redirect('/posts');
        }

        // 3. 연동 기록도 없고 로그인도 안 되어 있다면 -> 신규 가입 유도
        session([
            'social_user' => [
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]
        ]);

        return redirect('/register')->with('message', '아직 연동된 계정이 없습니다. 회원가입 후 연동해주세요.');
    }
    
    // 3. 연동 해제
    public function disconnect($provider)
    {
        $user = auth()->user();
        $account = $user->socialAccounts()->where('provider', $provider)->first();

        if ($account) {
            // 마지막 로그인 수단이 사라지는 것 방지
            if ($user->socialAccounts()->count() <= 1 && !$user->password) {
                return back()->with('error', '마지막 소셜 계정을 해제하려면 먼저 비밀번호를 설정해주세요.');
            }

            $account->delete();
            return back()->with('success', ucfirst($provider) . ' 연동이 해제되었습니다.');
        }

        return back()->with('error', '연동 정보를 찾을 수 없습니다.');
    }
}