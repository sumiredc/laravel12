<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Passport\AuthCode;

final class OAuthAuthCode extends AuthCode
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
