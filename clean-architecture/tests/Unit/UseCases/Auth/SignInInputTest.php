<?php

declare(strict_types=1);

use App\UseCases\Auth\SignInInput;

\describe('__construct', function () {
    \it('initialzes this with the specified values', function () {
        $loginID = 'login@id.xxx';
        $password = 'password';

        $result = new SignInInput($loginID, $password);

        \expect($result->loginID)->toBe($loginID);
        \expect($result->password)->toBe($password);
    });
});
