<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Consts\RoleID;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

final class UserPolicy
{
    use HandlesAuthorization;

    public function list(User $authUser): Response
    {
        if (RoleID::Admin->value === $authUser->role_id) {
            return $this->allow();
        }

        return $this->deny();
    }

    public function create(User $authUser): Response
    {
        if (RoleID::Admin->value === $authUser->role_id) {
            return $this->allow();
        }

        return $this->deny();
    }

    public function get(User $authUser, User $user): Response
    {
        if (RoleID::Admin->value === $authUser->role_id) {
            return $this->allow();
        }

        if ($authUser->id === $user->id) {
            return $this->allow();
        }

        return $this->deny();
    }

    public function update(User $authUser, User $user): Response
    {
        if (RoleID::Admin->value === $authUser->role_id) {
            return $this->allow();
        }

        if ($authUser->id === $user->id) {
            return $this->allow();
        }

        return $this->deny();
    }

    public function delete(User $authUser, User $user): Response
    {
        if (RoleID::Admin->value === $authUser->role_id) {
            return $this->allow();
        }

        return $this->deny();
    }
}
