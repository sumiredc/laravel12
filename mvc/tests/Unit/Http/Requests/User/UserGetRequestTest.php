<?php

declare(strict_types=1);

use App\Exceptions\ForbiddenException;
use App\Http\Requests\User\UserGetRequest;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

\describe('authorize', function () {

    \it('allows authorization when Gate permits', function () {
        Gate::partialMock()->shouldReceive('inspect')->andReturn(new Response(true));
        $request = new UserGetRequest;
        $result = $request->authorize();

        \expect($result)->toBeTrue();
    });

    \it('throws ForbiddenException when Gate denies', function () {

        Gate::partialMock()->shouldReceive('inspect')->andReturn(new Response(false));
        $request = new UserGetRequest;
        $request->authorize();

    })
        ->throws(ForbiddenException::class);

});
