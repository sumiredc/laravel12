<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\ValueObjects\User\UserID;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function list(
        int $offset,
        int $limit,
        string $name,
        string $email
    ): Collection;

    public function create(
        UserID $userID,
        string $name,
        string $email,
        string $hashedPassword
    ): User;

    public function get(int $userID): ?User;

    public function getByEmail(string $email): ?User;

    public function getUnverifiedByEmail(string $email): ?User;

    public function update(
        User $user,
        string $name = '',
        string $email = '',
        string $hashedPassword = '',
    ): User;

    public function verifyEmail(User $user): void;

    public function delete(User $user): void;
}
