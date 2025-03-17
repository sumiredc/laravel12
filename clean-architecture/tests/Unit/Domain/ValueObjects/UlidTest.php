<?php

declare(strict_types=1);

use App\Domain\ValueObjects\Ulid;
use Symfony\Component\Uid\Ulid as UidUlid;

\beforeAll(function () {
    final class TestUlidChild extends Ulid {}
});

\describe('make', function () {
    \it('returns a ULID with the given value', function () {

        $result = TestUlidChild::make();

        \expect(UidUlid::isValid($result->value))->toBeTrue();
    });
});

\describe('parse', function () {
    \it('returns an Ok results with the given value', function () {
        $ulid = '01JPFSVEMMZCP7V31AGQH7BW4B';

        $result = TestUlidChild::parse($ulid);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue()->value)->toBe($ulid);
    });

    \it('returns an Err result with the given error', function () {
        $uuid = '8934681e-08eb-4f86-aa06-585a37509885';

        $result = TestUlidChild::parse($uuid);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InvalidArgumentException::class);
    });
});

\describe('__toString', function () {
    \it('returns a ULID string when cast to string', function () {
        $ulid = '01JPFSVEMMZCP7V31AGQH7BW4B';

        $result = TestUlidChild::parse($ulid);

        \expect((string) $result->getValue())->toBe($ulid);
    });
});

\describe('jsonSerialize', function () {
    \it('serializes to JSON as the ULID string', function () {
        $ulid = '01JPFSVEMMZCP7V31AGQH7BW4B';

        $result = TestUlidChild::parse($ulid);

        \expect(\json_encode($result->getValue()))->toBe(\json_encode($ulid));
    });
});
