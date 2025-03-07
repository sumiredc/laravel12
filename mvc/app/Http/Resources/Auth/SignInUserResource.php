<?php

declare(strict_types=1);

namespace App\Http\Resources\Auth;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

final class SignInUserResource extends JsonResource
{
    public function __construct(User $user, string $token)
    {
        parent::__construct([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
