<?php

declare(strict_types=1);

use App\UseCases\Auth\FirstSignInInput;

\describe('__construct', function () {
    \it('initialzes this with the specified values', function () {
        $loginID = 'login@id.xxx';
        $password = 'password';
        $newPassword = 'newPassword';

        $result = new FirstSignInInput($loginID, $password, $newPassword);

        \expect($result->loginID)->toBe($loginID);
        \expect($result->password)->toBe($password);
        \expect($result->newPassword)->toBe($newPassword);
    });
});
