<?php

declare(strict_types=1);

use App\Domain\Shared\Result;

\describe('ok', function () {
    \it('returns an Ok result with the given value', function ($value) {
        $result = Result::ok($value);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue())->toBe($value);
    })
        ->with([
            'string' => 'aaa',
            'int' => 12345,
            'float' => 5.9,
            'array' => ['value1', 'value2'],
            'object' => new stdClass,
            'null' => null,
        ]);

    \it('throws a RuntimeException when getting an error from an Ok result', function () {
        $result = Result::ok(null);

        $result->getError();
    })
        ->throws(RuntimeException::class);
});

\describe('err', function () {
    \it('returns an Err result with the given error', function () {
        $err = new TypeError;
        $result = Result::err($err);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError())->toBe($err);
    });

    \it('throws a RuntimeException when getting a value from an Err result', function () {
        $result = Result::err(new Exception);

        $result->getValue();
    })
        ->throws(RuntimeException::class);
});
