<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

final class UserPolicy
{
    use HandlesAuthorization;

    public function list(User $authUser): Response
    {
        return $this->allow();
    }

    public function create(User $authUser): Response
    {
        return $this->allow();
    }

    public function get(User $authUser, User $user): Response
    {
        return $this->allow();
    }

    public function update(User $authUser, user $user): Response
    {
        return $this->allow();
    }

    public function delete(User $authUser, user $user): Response
    {
        return $this->allow();
    }
}
