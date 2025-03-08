<?php

declare(strict_types=1);

use App\Casts\Role\RoleIDCast;
use App\Consts\Role;
use App\ValueObjects\Role\RoleID;
use Illuminate\Database\Eloquent\Model;

describe('RoleIDCast::get()', function () {
    $cast = new RoleIDCast;
    $model = new class extends Model {};

    it('convert string to RoleID', function () use ($cast, $model) {
        $result = $cast->get($model, 'key', Role::User->value, []);

        expect($result->value)->toBe(Role::User->value);
    });

    it('failed to convert of ValueError', function () use ($cast, $model) {
        $cast->get($model, 'key', '01JNVKWDF41STA7P2CEB974YRX', []);
    })
        ->throws(ValueError::class);

    it('failed to convert of TypeError', function () use ($cast, $model) {
        $cast->get($model, 'key', 99999, []);
    })
        ->throws(TypeError::class);
});

describe('RoleIDCast::set()', function () {
    $cast = new RoleIDCast;
    $model = new class extends Model {};

    it('convert RoleID to string', function () use ($cast, $model) {
        $result = $cast->set($model, 'key', RoleID::parse(Role::User), []);

        expect($result)->toBe(Role::User->value);
    });

    it('failed to convert of InvalidArgumentException', function () use ($cast, $model) {
        $cast->set($model, 'key', '01JNVKWDF41STA7P2CEB974YRX', []);
    })
        ->throws(InvalidArgumentException::class);
});

describe('RoleIDCast::serialize()', function () {
    $cast = new RoleIDCast;
    $model = new class extends Model {};

    it('serialize RoleID to string', function () use ($cast, $model) {
        $result = $cast->serialize($model, 'key', RoleID::parse(Role::Admin), []);

        expect($result)->toBe(Role::Admin->value);
    });
});
