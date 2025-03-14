<?php

declare(strict_types=1);

namespace App\Http\Resources\Auth;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\OAuthToken;
use Illuminate\Http\Resources\Json\JsonResource;

final class SignInUserResource extends JsonResource
{
    public function __construct(User $user, OAuthToken $token)
    {
        parent::__construct(compact('user', 'token'));
    }
}
