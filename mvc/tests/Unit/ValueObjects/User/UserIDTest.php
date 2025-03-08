<?php

declare(strict_types=1);

use App\ValueObjects\User\UserID;
use Symfony\Component\Uid\Ulid;

test('UserID::make() generates a valid ULID', function () {
    $userID = UserID::make();

    expect(Ulid::isValid($userID->value))->toBeTrue();
});

test('UserID:parse() correctly initializes from a given ULID', function () {
    $ulid = '01JNVFE1KD7K8RC2R3QX9VXD73';
    $userID = UserID::parse($ulid);

    expect($ulid)->toBe($userID->value);
});

test('UserID::__toString() correctly initializes from a given ULID', function () {
    $ulid = '01JNVFQGASP2ZAG48GAK5ZQYMR';
    $userID = UserID::parse($ulid);

    expect($ulid)->toBe((string) $userID);
});
