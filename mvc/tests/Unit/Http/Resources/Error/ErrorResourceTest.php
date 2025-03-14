<?php

declare(strict_types=1);

use App\Http\Resources\Error\ErrorResource;

describe('ErrorResourceTest', function () {

    it('success to initialize', function () {
        $message = 'error message';
        $result = new ErrorResource($message);

        expect($result->resource)->toBe(['message' => $message]);
    });
});
