<?php

declare(strict_types=1);

use App\Domain\ValueObjects\Uuid;

\beforeAll(function () {
    final class A extends Uuid {}
});

\describe('parse', function () {
    \it('returns an Ok results with the given value', function () {
        $uuid = '8934681e-08eb-4f86-aa06-585a37509885';

        $result = A::parse($uuid);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue()->value)->toBe($uuid);
    });

    \it('returns an Err result with the given error', function () {
        $ulid = '01JPFSVEMMZCP7V31AGQH7BW4B';

        $result = A::parse($ulid);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InvalidArgumentException::class);
    });
});

\describe('__toString', function () {
    \it('returns a UUID string when cast to string', function () {
        $uuid = '8934681e-08eb-4f86-aa06-585a37509885';

        $result = A::parse($uuid);

        \expect((string) $result->getValue())->toBe($uuid);
    });
});

\describe('jsonSerialize', function () {
    \it('serializes to JSON as the UUID string', function () {
        $uuid = '8934681e-08eb-4f86-aa06-585a37509885';

        $result = A::parse($uuid);

        \expect(\json_encode($result->getValue()))->toBe(\json_encode($uuid));
    });
});
