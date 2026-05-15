<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    // 회원가입 페이지
    public function showRegister() { 
        return Inertia::render('Auth/Register'); 
    }

    // 회원가입 처리
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        Auth::login($user);
        return redirect('/posts');
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
}