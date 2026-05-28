<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event; // 추가됨!
use SocialiteProviders\Manager\SocialiteWasCalled; // 추가됨!
use SocialiteProviders\Naver\NaverExtendSocialite;
use SocialiteProviders\Google\GoogleExtendSocialite;
use SocialiteProviders\Kakao\KakaoExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(SocialiteWasCalled::class, NaverExtendSocialite::class);
        Event::listen(SocialiteWasCalled::class, GoogleExtendSocialite::class);
        Event::listen(SocialiteWasCalled::class, KakaoExtendSocialite::class);
    }
}