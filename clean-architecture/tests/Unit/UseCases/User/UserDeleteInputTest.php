<?php

declare(strict_types=1);

use App\Domain\ValueObjects\UserID;
use App\UseCases\User\UserDeleteInput;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $userID = UserID::make();

        $result = new UserDeleteInput($userID);

        \expect($result->userID)->toBe($userID);
    });
});
