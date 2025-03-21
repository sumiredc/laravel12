<?php

declare(strict_types=1);

namespace App\Policies;

use App\Consts\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

final class UserPolicy
{
    use HandlesAuthorization;

    public function list(User $authUser): Response
    {
        if (Role::isAdmin($authUser)) {
            return $this->allow();
        }

        return $this->deny();
    }

    public function create(User $authUser): Response
    {
        if (Role::isAdmin($authUser)) {
            return $this->allow();
        }

        return $this->deny();
    }

    public function get(User $authUser, User $user): Response
    {
        if (Role::isAdmin($authUser)) {
            return $this->allow();
        }

        if ($authUser->id->value === $user->id->value) {
            return $this->allow();
        }

        return $this->deny();
    }

    public function update(User $authUser, user $user): Response
    {
        if (Role::isAdmin($authUser)) {
            return $this->allow();
        }

        if ($authUser->id->value === $user->id->value) {
            return $this->allow();
        }

        return $this->deny();
    }

    public function delete(User $authUser, user $user): Response
    {
        if (Role::isAdmin($authUser)) {
            return $this->allow();
        }

        return $this->deny();
    }
}
