<?php

declare(strict_types=1);

use App\UseCases\User\UserDeleteOutput;

\describe('__construct', function () {
    \it('initializes this', function () {
        $result = new UserDeleteOutput;

        \expect($result::class)->toBe(UserDeleteOutput::class);
    });
});
