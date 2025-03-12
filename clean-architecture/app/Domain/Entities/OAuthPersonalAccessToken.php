<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\OAuthClientID;
use App\Domain\ValueObjects\OAuthTokenID;

final class OAuthPersonalAccessToken
{
    public function __construct(
        public readonly OAuthTokenID $tokenID,
        public readonly OAuthClientID $clientID,
    ) {}
}
