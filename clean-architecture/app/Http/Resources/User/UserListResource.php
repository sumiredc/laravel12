<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Domain\Entities\User;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserListResource extends JsonResource
{
    /** @param array<User> $users */
    public function __construct(array $users)
    {
        parent::__construct(['users' => $users]);
    }
}
