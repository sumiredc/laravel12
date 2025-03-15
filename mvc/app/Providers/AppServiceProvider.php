<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\OAuthAccessToken;
use App\Models\OAuthAuthCode;
use App\Models\OAuthClient;
use App\Repositories\TokenRepository;
use App\Repositories\TokenRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

use function now;

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
