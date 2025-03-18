<?php

declare(strict_types=1);

use App\Http\Resources\ValidationErrorResource;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $errors = ['error1', 'error2', 'error3'];

        $result = new ValidationErrorResource($errors);

        \expect($result->resource['errors'])->toBe($errors);
    });
});
