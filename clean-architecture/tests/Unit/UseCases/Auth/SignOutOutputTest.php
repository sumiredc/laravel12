<?php

declare(strict_types=1);

use App\UseCases\Auth\SignOutOutput;

\describe('__construct', function () {
    \it('initializes this', function () {
        $result = new SignOutOutput;

        \expect($result::class)->toBe(SignOutOutput::class);
    });
});
