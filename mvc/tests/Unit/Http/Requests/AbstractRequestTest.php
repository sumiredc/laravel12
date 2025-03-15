<?php

declare(strict_types=1);

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\AbstractRequest;
use App\Models\User;
use App\ValueObjects\User\UserID;

\beforeEach(function () {
    $this->request = new class extends AbstractRequest {};
});

\describe('userOfFail', function () {

    \it('returns User in userOfFail', function () {
        $user = new User;
        $user->id = UserID::make();
        $this->request->setUserResolver(fn () => $user);

        $result = $this->request->userOrFail();

        \expect($result->id->value)->toBe($user->id->value);
    });

    \it('throws UnauthorizedException in userOrFail', function () {
        $this->request->userOrFail();
    })
        ->throws(UnauthorizedException::class);

});

\describe('userOrNull', function () {

    \it('returns User in userOrNull', function () {
        $user = new User;
        $user->id = UserID::make();
        $this->request->setUserResolver(fn () => $user);

        $result = $this->request->userOrNull();

        \expect($result->id->value)->toBeClass($user->id->value);
    });

    \it('returns null in userOrNull', function () {
        $result = $this->request->userOrNull();

        \expect($result)->toBeNull();
    });

    \it('throws InternalServerErrorException in userOrNull', function () {
        $this->request->setUserResolver(fn () => false);
        $this->request->userOrNull();
    })
        ->throws(InternalServerErrorException::class);
});
