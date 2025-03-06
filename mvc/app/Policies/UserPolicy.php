<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

final class UserPolicy
{
    use HandlesAuthorization;

    public function list(?User $user): Response
    {
        return $this->allow();
    }

    public function create(?User $user): Response
    {
        return $this->allow();
    }

    public function get(?User $auth, User $user): Response
    {
        return $this->allow();
    }
}
