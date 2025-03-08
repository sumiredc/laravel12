<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

final class UserRepository
{
    public function list(int $offset, int $limit, string $name, string $email): Collection
    {
        $query = User::query();

        if ($name) {
            $query->whereName($name);
        }

        if ($email) {
            $query->whereEmail($email);
        }

        return $query->offset($offset)->limit($limit)->get();
    }

    public function create(string $name, string $email, string $hashedPassword): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ]);
    }

    public function get(int $userID): ?User
    {
        return User::find($userID);
    }

    public function getByEmail(string $email): ?User
    {
        return User::whereEmail($email)
            ->whereNotNull('email_verified_at')
            ->first();
    }

    public function getUnverifiedByEmail(string $email): ?User
    {
        return User::whereEmail($email)
            ->whereNull('email_verified_at')
            ->first();
    }

    public function update(
        User $user,
        string $name = '',
        string $email = '',
        string $hashedPassword = ''
    ): User {
        $user->fill([
            'name' => $name ?: $user->name,
            'email' => $email ?: $user->email,
            'password' => $hashedPassword ?: $hashedPassword,
        ])
            ->save();

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
