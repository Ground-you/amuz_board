<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // 회원가입 페이지
    public function showRegister() { 
        return Inertia::render('Auth/Register'); 
    }

    // 회원가입 처리
    public function register(Request $request)
    {
        // 1. 유효성 검사
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. 유저 생성
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. ⭐️ 회원가입 이벤트 발생 (이 코드가 실행되면 라라벨이 알아서 .env 설정을 보고 인증 메일을 쏩니다!)
        event(new Registered($user));

        // 4. 자동 로그인 처리
        Auth::login($user);

        // 5. 가입하자마자 "이메일 확인해라"는 격리 페이지(/email/verify)로 바로 던져버립니다.
        return redirect()->route('verification.notice')
        ->with('message', '회원가입이 완료되었습니다! 가입하신 이메일로 인증 링크를 발송했으니 확인해 주세요.');
    }

    // 로그인 페이지
    public function showLogin() { 
        return Inertia::render('Auth/Login'); 
    }

    // 로그인 처리
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($fields, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/posts');
        }

        return back()->withErrors(['email' => '입력하신 정보가 일치하지 않습니다.']);
    }

    // 로그아웃
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/posts');
    }

    // 1. 이메일 인증 안내 화면 렌더링
    public function showVerifyEmail()
    {
        return \Inertia\Inertia::render('Auth/VerifyEmail');
    }

    // 2. 인증 이메일 재발송
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('posts.index');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', '인증 이메일이 재발송되었습니다.');
    }
}