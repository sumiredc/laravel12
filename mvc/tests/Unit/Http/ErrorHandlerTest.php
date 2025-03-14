<?php

declare(strict_types=1);

use App\Http\ErrorHandler;
use Illuminate\Foundation\Configuration\Exceptions;
use League\OAuth2\Server\Exception\OAuthServerException;

describe('ErrorHandlerTest', function () {
    it('dont report Exceptions', function () {
        $handler = new ErrorHandler;
        $mock = Mockery::mock(Exceptions::class);
        $mock->shouldReceive('dontReport')->with([OAuthServerException::class])->once();
        $handler->report($mock);
    });
});
