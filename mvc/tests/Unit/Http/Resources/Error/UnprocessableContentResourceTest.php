<?php

declare(strict_types=1);

use App\Http\Resources\Error\UnprocessableContentResource;

describe('UnprocessableContentResourceTest', function () {
    it('success to initialize', function () {
        $message = 'error message';
        $errors = ['error'];
        $result = new UnprocessableContentResource($message, $errors);

        expect($result->resource)->toBe(['message' => $message, 'errors' => $errors]);
    });
});
