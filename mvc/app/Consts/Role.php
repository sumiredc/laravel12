<?php

declare(strict_types=1);

namespace App\Consts;

use App\Models\User;

enum Role: string
{
    case Admin = '01JNTYGRT15MQJWT7DTY4C3YK2';
    case User = '01JNTYHJXCYEYXP67VANSCT9M5';

    public static function isAdmin(User $user): bool
    {
        return $user->role_id->value === Role::Admin->value;
    }

    public static function isUser(User $user): bool
    {
        return $user->role_id->value === Role::User->value;
    }
}
