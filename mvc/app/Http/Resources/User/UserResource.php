<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array{user:User} $resource
 */
final class UserResource extends JsonResource
{
    public function __construct(User $user)
    {
        parent::__construct(['user' => $user]);
    }
}
