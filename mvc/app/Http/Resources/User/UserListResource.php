<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

final class UserListResource extends JsonResource
{
    public function __construct(Collection $users)
    {
        parent::__construct(['users' => $users]);
    }
}
