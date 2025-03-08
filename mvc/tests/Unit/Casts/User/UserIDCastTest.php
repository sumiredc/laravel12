<?php

declare(strict_types=1);

use App\Casts\User\UserIDCast;
use App\ValueObjects\User\UserID;
use Illuminate\Database\Eloquent\Model;

describe('UserIDCast::get()', function () {
    $cast = new UserIDCast;
    $model = new class extends Model {};

    it('convert string to UserID', function () use ($cast, $model) {
        $ulid = '01JNVKWDF41STA7P2CEB974YRX';
        $result = $cast->get($model, 'key', $ulid, []);

        expect($result->value)->toBe($ulid);
    });

    it('failed to convert of InvalidArgumentException', function () use ($cast, $model) {
        $cast->get($model, 'key', 'fbb43faf-268f-4d6f-b8d3-e0efe76d96da', []);
    })
        ->throws(InvalidArgumentException::class);

    it('failed to convert of TypeError', function () use ($cast, $model) {
        $cast->get($model, 'key', 99999, []);
    })
        ->throws(TypeError::class);
});

describe('UserIDCast::set()', function () {
    $cast = new UserIDCast;
    $model = new class extends Model {};

    it('convert UserID to string', function () use ($cast, $model) {
        $ulid = '01JNVKWDF41STA7P2CEB974YRX';
        $result = $cast->set($model, 'key', UserID::parse($ulid), []);

        expect($result)->toBe($ulid);
    });

    it('failed to convert of InvalidArgumentException', function () use ($cast, $model) {
        $cast->set($model, 'key', 'fbb43faf-268f-4d6f-b8d3-e0efe76d96da', []);
    })
        ->throws(InvalidArgumentException::class);
});

describe('UserIDCast::serialize()', function () {
    $cast = new UserIDCast;
    $model = new class extends Model {};

    it('serialize UserID to string', function () use ($cast, $model) {
        $ulid = '01JNVKWDF41STA7P2CEB974YRX';
        $result = $cast->serialize($model, 'key', $ulid, []);

        expect($result)->toBe($ulid);
    });
});
