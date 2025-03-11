<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Repositories\TokenRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infra\Repositories\TokenRepository;
use App\Infra\Repositories\UserRepository;
use App\Models\OAuthAccessToken;
use App\Models\OAuthAuthCode;
use App\Models\OAuthClient;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(TokenRepositoryInterface::class, TokenRepository::class);
    }

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
