<?php

declare(strict_types=1);

use App\Http\Resources\Error\ErrorResource;

\describe('__construct', function () {
    \it('successfully initializes with a message', function () {
        $message = 'error message';
        $result = new ErrorResource($message);

        \expect($result->toArray(\request()))->toBe(['message' => $message]);
    });
});
