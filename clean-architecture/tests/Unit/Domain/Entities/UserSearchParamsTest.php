<?php

declare(strict_types=1);

use App\Domain\Entities\UserSearchParams;

\describe('__construct', function () {
    \it('initializes an instance with the given values', function () {
        $name = 'sample name';
        $email = 'sample@xxx.xx';
        $offset = 123;
        $limit = 34567;

        $result = new UserSearchParams($name, $email, $offset, $limit);

        \expect($result->name)->toBe($name);
        \expect($result->email)->toBe($email);
        \expect($result->offset)->toBe($offset);
        \expect($result->limit)->toBe($limit);
    });
});
