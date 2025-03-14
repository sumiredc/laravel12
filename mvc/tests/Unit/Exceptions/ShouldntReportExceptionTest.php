<?php

declare(strict_types=1);

use App\Exceptions\ShouldntReportException;
use Illuminate\Contracts\Debug\ShouldntReport;

\describe('__construct', function () {
    \it('implements the ShouldntReport interface', function () {
        \expect(ShouldntReportException::class)->toImplement(ShouldntReport::class);
    });

    \it('initializes with a message, code, and previous exception', function () {
        $message = 'error message';
        $code = 400;
        $typeError = new TypeError;
        $ex = new ShouldntReportException($message, $code, $typeError);

        \expect($ex->getMessage())->toBe($message);
        \expect($ex->getCode())->toBe($code);
        \expect($ex->getPrevious())->toBe($typeError);
    });

});
