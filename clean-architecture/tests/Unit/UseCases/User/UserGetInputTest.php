<?php

declare(strict_types=1);

use App\Domain\ValueObjects\UserID;
use App\UseCases\User\UserGetInput;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $userID = UserID::make();

        $result = new UserGetInput($userID);

        \expect($result->userID)->toBe($userID);
    });
});
