<?php

declare(strict_types=1);

use App\UseCases\User\UserCreateInput;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $name = 'sample name';
        $email = 'sample@xxx.xx';

        $result = new UserCreateInput($name, $email);

        \expect($result->name)->toBe($name);
        \expect($result->email)->toBe($email);
    });
});
