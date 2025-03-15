<?php

declare(strict_types=1);

use App\Http\ExceptionHandler\Handler;
use Illuminate\Foundation\Configuration\Exceptions;
use League\OAuth2\Server\Exception\OAuthServerException;

describe('HandlerTest', function () {
    beforeEach(function () {
        $this->mock = Mockery::mock(Exceptions::class);
    });

    it('dont report Exceptions', function () {
        $this->mock->shouldReceive('dontReport')
            ->with([OAuthServerException::class])->once();

        $handler = new Handler($this->mock);
        $handler->report();
    });

    it('createJsonResponse', function () {
        $this->mock->shouldReceive('shouldRenderJsonWhen')->once()->andReturn($this->mock);
        $this->mock->shouldReceive('render')->once()->andReturn($this->mock);

        $handler = new Handler($this->mock);
        $handler->createJsonResponse();
    });
});
