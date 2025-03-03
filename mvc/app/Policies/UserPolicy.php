<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

final class UserPolicy
{
    use HandlesAuthorization;

    public function create(?User $user): Response
    {
        return $this->allow();
    }
}
