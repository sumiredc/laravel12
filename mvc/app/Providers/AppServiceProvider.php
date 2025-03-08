<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\OAuthAccessToken;
use App\Models\OAuthAuthCode;
use App\Models\OAuthClient;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Passport::useTokenModel(OAuthAccessToken::class);
        Passport::useAuthCodeModel(OAuthAuthCode::class);
        Passport::useClientModel(OAuthClient::class);
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Passport::ignoreRoutes();
    }
}
