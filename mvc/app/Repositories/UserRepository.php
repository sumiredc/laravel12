<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Consts\Role;
use App\Models\User;
use App\ValueObjects\Role\RoleID;
use App\ValueObjects\User\UserID;
use Illuminate\Support\Collection;

final class UserRepository implements UserRepositoryInterface
{
    public function list(int $offset, int $limit, string $name, string $email): Collection
    {
        $query = User::query();

        if ($name) {
            $query->whereLike('name', "%$name%");
        }

        if ($email) {
            $query->whereLike('email', "%$email%");
        }

        return $query->offset($offset)->limit($limit)->get();
    }

    public function create(UserID $userID, string $name, string $email, string $hashedPassword): User
    {
        return User::create([
            'id' => $userID,
            'role_id' => RoleID::parse(Role::User),
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
        string $hashedPassword = '',
    ): User {
        $user->fill([
            'name' => $name ?: $user->name,
            'email' => $email ?: $user->email,
            'password' => $hashedPassword ?: $user->password,
        ])
            ->save();

        return $user;
    }

    public function verifyEmail(User $user): void
    {
        $user->markEmailAsVerified();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
