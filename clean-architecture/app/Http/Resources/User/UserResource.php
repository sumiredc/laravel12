<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Domain\Entities\User;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    public function __construct(User $user)
    {
        parent::__construct(['user' => $user]);
    }
}
