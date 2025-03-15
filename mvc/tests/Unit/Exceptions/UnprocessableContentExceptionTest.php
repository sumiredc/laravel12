<?php

declare(strict_types=1);

use App\Exceptions\UnprocessableContentException;
use Illuminate\Contracts\Debug\ShouldntReport;

\describe('__construct', function () {
    \it('implements the ShouldntReport interface', function () {
        \expect(UnprocessableContentException::class)->toImplement(ShouldntReport::class);
        \expect(UnprocessableContentException::class);
    });

    \it('initializes with a message, code, and previous exception', function () {
        $message = 'error message';
        $code = 400;
        $typeError = new TypeError;
        $ex = new UnprocessableContentException([], $message, $code, $typeError);

        \expect($ex->getMessage())->toBe($message);
        \expect($ex->getCode())->toBe($code);
        \expect($ex->getPrevious())->toBe($typeError);
    });
});
