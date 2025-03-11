<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Passport\Client;

final class OAuthClient extends Client
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
