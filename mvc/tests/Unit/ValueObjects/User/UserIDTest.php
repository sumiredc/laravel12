<?php

declare(strict_types=1);

use App\ValueObjects\User\UserID;
use Symfony\Component\Uid\Ulid;

describe('UserID', function () {
    it('make() generates a valid ULID', function () {
        $result = UserID::make();

        expect(Ulid::isValid($result->value))->toBeTrue();
    });

    it('parse() correctly initializes from a given ULID', function () {
        $ulid = '01JNVFE1KD7K8RC2R3QX9VXD73';
        $result = UserID::parse($ulid);

        expect($result->value)->toBe($ulid);
    });

    it('__toString() correctly initializes from a given ULID', function () {
        $ulid = '01JNVFQGASP2ZAG48GAK5ZQYMR';
        $result = UserID::parse($ulid);

        expect((string) $result)->toBe($ulid);
    });
});
