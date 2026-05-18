<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 👑 1. 마스터 관리자 계정 자동 생성
        User::create([
            'name' => '어드민',
            'email' => 'admin@board.com', // 👈 로그인할 관리자 이메일
            'password' => Hash::make('admin1234'), // 👈 관리자 비밀번호
            'is_admin' => true, // 👈 관리자 권한 부여!
            'email_verified_at' => now(), // 👈 시더로 만들 때는 이메일 인증도 프리패스로 완료 처리!
        ]);

    }
}