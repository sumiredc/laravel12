<?php

declare(strict_types=1);

use App\Http\Resources\Error\UnprocessableContentResource;

\describe('__construct', function () {
    \it('successfully initializes with message and errors', function () {
        $message = 'error message';
        $errors = ['error'];
        $result = new UnprocessableContentResource($message, $errors);

        \expect($result->toArray(\request()))->toBe(['message' => $message, 'errors' => $errors]);
    });
});
