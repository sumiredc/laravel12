<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Passport\Token;

final class OAuthAccessToken extends Token
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'string',
        ];
    }
}
