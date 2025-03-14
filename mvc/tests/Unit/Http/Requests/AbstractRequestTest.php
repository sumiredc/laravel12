<?php

declare(strict_types=1);

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\AbstractRequest;
use App\Models\User;
use App\ValueObjects\User\UserID;

describe('AbstractRequestTest', function () {
    beforeEach(function () {
        $this->request = new class extends AbstractRequest {};
    });

    it('success to userOrFail', function () {
        $user = new User;
        $user->id = UserID::make();
        $this->request->setUserResolver(fn () => $user);

        $result = $this->request->userOrFail();

        expect($result->id->value)->toBeClass($user->id->value);
    });

    it('throws UnauthorizedException userOrFail', function () {
        $this->request->userOrFail();
    })
        ->throws(UnauthorizedException::class);

    it('return User userOrNull', function () {
        $user = new User;
        $user->id = UserID::make();
        $this->request->setUserResolver(fn () => $user);

        $result = $this->request->userOrNull();

        expect($result->id->value)->toBeClass($user->id->value);
    });

    it('return null userOrNull', function () {
        $result = $this->request->userOrNull();

        expect($result)->toBeNull();
    });

    it('throws InternalServerErrorException userOrNull', function () {
        $this->request->setUserResolver(fn () => false);
        $this->request->userOrNull();
    })
        ->throws(InternalServerErrorException::class);
});
