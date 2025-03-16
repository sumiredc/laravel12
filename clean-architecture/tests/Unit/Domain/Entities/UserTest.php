<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\UserID;

\describe('__construct', function () {
    \it('initializes User with the specified values', function () {
        $userID = UserID::make();
        $roleID = RoleID::User;
        $name = 'sample name';
        $email = 'sample@xxx.xx';

        $result = new User($userID, $roleID, $name, $email);

        \expect((string) $result->userID)->toBe((string) $userID);
        \expect($result->roleID)->toBe($roleID);
        \expect($result->name)->toBe($name);
        \expect($result->email)->toBe($email);
    });
});

\describe('recontruct', function () {
    \it('reinitializes User with the specified values', function () {
        $userID = UserID::make();
        $roleID = RoleID::User;
        $user = new User($userID, $roleID, 'sample name', 'sample@xxx.xx');

        $name = 'recontract name';
        $email = 'recontract@xxx.xx';
        $result = $user->recontruct($name, $email);

        \expect((string) $result->userID)->toBe((string) $userID);
        \expect($result->roleID)->toBe($roleID);
        \expect($result->name)->toBe($name);
        \expect($result->email)->toBe($email);
    });
});
