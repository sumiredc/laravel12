<?php

declare(strict_types=1);

use App\Domain\ValueObjects\UserID;
use App\UseCases\User\UserUpdateInput;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $userID = UserID::make();
        $name = 'sample name';
        $email = 'sample@xxx.xx';

        $result = new UserUpdateInput($userID, $name, $email);

        \expect((string) $result->userID)->toBe((string) $userID);
        \expect($result->name)->toBe($name);
        \expect($result->email)->toBe($email);
    });
});
