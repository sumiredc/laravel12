<?php

declare(strict_types=1);

use App\Exceptions\ForbiddenException;
use App\Http\Requests\User\UserGetRequest;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

describe('UserGetRequestTest', function () {

    it('passes authorize', function () {
        Gate::partialMock()->shouldReceive('inspect')->andReturn(new Response(true));
        $request = new UserGetRequest;
        $result = $request->authorize();

        expect($result)->toBeTrue();
    });

    it('denied authorize', function () {

        Gate::partialMock()->shouldReceive('inspect')->andReturn(new Response(false));
        $request = new UserGetRequest;
        $request->authorize();

    })
        ->throws(ForbiddenException::class);

});
