<?php

declare(strict_types=1);

use App\Casts\Role\RoleIDCast;
use App\Consts\Role;
use App\ValueObjects\Role\RoleID;
use Illuminate\Database\Eloquent\Model;

\beforeEach(function () {
    $this->cast = new RoleIDCast;
    $this->model = new class extends Model {};
});

\describe('get', function () {

    \it('converts a string to RoleID', function () {
        $result = $this->cast->get($this->model, 'key', Role::User->value, []);

        \expect($result->value)->toBe(Role::User->value);
    });

    \it('throws ValueError when conversion fails', function () {
        $this->cast->get($this->model, 'key', '01JNVKWDF41STA7P2CEB974YRX', []);
    })
        ->throws(ValueError::class);

    \it('throws TypeError when conversion fails', function () {
        $this->cast->get($this->model, 'key', 99999, []);
    })
        ->throws(TypeError::class);
});

\describe('set', function () {

    \it('converts RoleID to a string', function () {
        $result = $this->cast->set($this->model, 'key', RoleID::parse(Role::User), []);

        \expect($result)->toBe(Role::User->value);
    });

    \it('throws InvalidArgumentException when conversion fails', function () {
        $this->cast->set($this->model, 'key', '01JNVKWDF41STA7P2CEB974YRX', []);
    })
        ->throws(InvalidArgumentException::class);
});

\describe('serialize', function () {

    \it('serializes RoleID to a string', function () {
        $result = $this->cast->serialize($this->model, 'key', RoleID::parse(Role::Admin), []);

        \expect($result)->toBe(Role::Admin->value);
    });
});
