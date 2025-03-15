<?php

declare(strict_types=1);

use App\Consts\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use App\ValueObjects\User\UserID;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Hash;

\beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $this->repository = new UserRepository;
});

\describe('list', function () {
    \it('filters users based on search criteria', function ($name, $email, $count) {
        User::factory()->userRole()->createMany([
            ['name' => 'koko-san', 'email' => 'a@xxx.xx'],
            ['name' => 'popo-san', 'email' => 'b@yyy.yy'],
            ['name' => 'toto-san', 'email' => 'c@zzz.zz'],
        ]);

        $result = $this->repository->list(0, 100, $name, $email);

        \expect($result->count())->toBe($count);
    })
        ->with([
            'filters by name' => ['koko', '', 1],
            'filters by email' => ['', 'yyy', 1],
            'filters by name and email' => ['toto-san', 'c@zzz.zz', 1],
            'filters by partial match on name' => ['san', '', 3],
            'returns no results for mismatched filters' => ['popo', 'xxx', 0],
        ]);

    \it('retrieves users with pagination', function ($offset, $limit, $count) {
        User::factory(100)->userRole()->create();

        $result = $this->repository->list($offset, $limit, '', '');

        \expect($result->count())->toBe($count);
    })
        ->with([
            'applies limit correctly' => [0, 99, 99],
            'applies offset correctly' => [48, 100, 52],
        ]);
});

\describe('create', function () {
    \it('creates a new user', function () {
        $userID = UserID::make();
        $name = 'sample user';
        $email = 'sample@xxx.xx';
        $password = Hash::make('password');

        $result = $this->repository->create($userID, $name, $email, $password);

        \expect((string) $result->id)->toBe((string) $userID);
        \expect((string) $result->role_id)->toBe(Role::User->value);
        \expect($result->name)->toBe($name);
        \expect($result->email)->toBe($email);
        \expect($result->password)->toBe($password);
    });
});

\describe('get', function () {
    \it('retrieves an existing user', function () {
        $user = User::factory()->userRole()->create();

        $result = $this->repository->get($user->id);

        \expect($result)->not->toBeNull();
        \expect((string) $result->id)->toBe((string) $user->id);
    });

    \it('returns null if user does not exist', function () {
        $userID = UserID::make();
        User::factory()->userRole()->create();

        $result = $this->repository->get($userID);

        \expect($result)->toBeNull();
    });
});

\describe('getByEmail', function () {
    \it('retrieves a verified user by email', function () {
        $email = 'koko[a]xxx.xx';
        User::factory(10)->userRole()->create();
        User::factory()->userRole()->create(['email' => $email]);

        $result = $this->repository->getByEmail($email);

        \expect($result)->not->toBeNull();
        \expect((string) $result->email)->toBe($email);
    });

    \it('returns null if no user is found', function () {
        User::factory(10)->userRole()->create();

        $result = $this->repository->getByEmail('zzz[a]xxx.gg');

        \expect($result)->toBeNull();
    });

    \it('returns null for unverified user', function () {
        $email = 'koko[a]xxx.xx';
        User::factory(10)->userRole()->create();
        User::factory()->userRole()->unverified()->create(['email' => $email]);

        $result = $this->repository->getByEmail($email);

        \expect($result)->toBeNull();
    });
});

\describe('getUnverifiedByEmail', function () {
    \it('retrieves a verified user by email', function () {
        $email = 'koko[a]xxx.xx';
        User::factory(10)->userRole()->create();
        User::factory()->userRole()->unverified()->create(['email' => $email]);

        $result = $this->repository->getUnverifiedByEmail($email);

        \expect($result)->not->toBeNull();
        \expect((string) $result->email)->toBe($email);
    });

    \it('returns null if no user is found', function () {
        User::factory(10)->userRole()->create();

        $result = $this->repository->getUnverifiedByEmail('zzz[a]xxx.gg');

        \expect($result)->toBeNull();
    });

    \it('returns null for verified user', function () {
        $email = 'koko[a]xxx.xx';
        User::factory(10)->userRole()->create();
        User::factory()->userRole()->create(['email' => $email]);

        $result = $this->repository->getUnverifiedByEmail($email);

        \expect($result)->toBeNull();
    });
});

\describe('update', function () {
    \it('updates user information', function () {
        $user = User::factory()->userRole()->create();
        $name = 'updated name';
        $email = 'updated@xxx.xx';
        $password = Hash::make('password');

        $result = $this->repository->update($user, $name, $email, $password);

        \expect($result->name)->toBe($name);
        \expect($result->email)->toBe($email);
        \expect($result->password)->toBe($password);
    });

    \it('keeps user information unchanged when no updates provided', function () {
        $user = User::factory()->userRole()->create();

        $result = $this->repository->update($user);

        \expect($result->name)->toBe($user->name);
        \expect($result->email)->toBe($user->email);
        \expect($result->password)->toBe($user->password);
    });
});

\describe('verifyEmail', function () {
    \it('makes user email as verified', function () {
        $user = User::factory()->userRole()->unverified()->create();

        \expect($user->email_verified_at)->toBeNull();

        $this->repository->verifyEmail($user);

        \expect($user->email_verified_at)->not->toBeNull();
    });
});

\describe('delete', function () {
    \it('removes user from database', function () {
        $user = User::factory()->userRole()->create();

        $this->repository->delete($user);
        $result = User::find($user->id);

        \expect($result)->toBeNull();
    });
});
