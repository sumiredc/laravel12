<?php

declare(strict_types=1);

use App\Casts\User\UserIDCast;
use App\ValueObjects\User\UserID;
use Illuminate\Database\Eloquent\Model;

\beforeEach(function () {
    $this->cast = new UserIDCast;
    $this->model = new class extends Model {};
});

\describe('get', function () {

    \it('converts a string to UserID', function () {
        $ulid = '01JNVKWDF41STA7P2CEB974YRX';
        $result = $this->cast->get($this->model, 'key', $ulid, []);

        \expect($result->value)->toBe($ulid);
    });

    \it('throws InvalidArgmentException when conversion fails', function () {
        $this->cast->get($this->model, 'key', 'fbb43faf-268f-4d6f-b8d3-e0efe76d96da', []);
    })
        ->throws(InvalidArgumentException::class);

    \it('throws TypeError when conversion fails', function () {
        $this->cast->get($this->model, 'key', 99999, []);
    })
        ->throws(TypeError::class);
});

\describe('set', function () {

    \it('converts UserID to a string', function () {
        $ulid = '01JNVKWDF41STA7P2CEB974YRX';
        $result = $this->cast->set($this->model, 'key', UserID::parse($ulid), []);

        \expect($result)->toBe($ulid);
    });

    \it('allows null', function () {
        $result = $this->cast->set($this->model, 'key', null, []);

        \expect($result)->toBeNull();
    });

    \it('throws InvalidArgumentException when conversion fails', function () {
        $this->cast->set($this->model, 'key', 'fbb43faf-268f-4d6f-b8d3-e0efe76d96da', []);
    })
        ->throws(InvalidArgumentException::class);
});

\describe('serialize', function () {

    \it('serialize UserID to a string', function () {
        $ulid = '01JNVKWDF41STA7P2CEB974YRX';
        $result = $this->cast->serialize($this->model, 'key', $ulid, []);

        \expect($result)->toBe($ulid);
    });
});
