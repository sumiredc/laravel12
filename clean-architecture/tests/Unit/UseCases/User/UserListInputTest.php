<?php

declare(strict_types=1);

use App\UseCases\User\UserListInput;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $offset = 1;
        $limit = 53;
        $name = 'sample name';
        $email = 'sample@xxx.xx';

        $result = new UserListInput($offset, $limit, $name, $email);

        \expect($result->offset)->toBe($offset);
        \expect($result->limit)->toBe($limit);
        \expect($result->name)->toBe($name);
        \expect($result->email)->toBe($email);

    });
});
