<?php

declare(strict_types=1);

use App\Http\Controllers\User\UserGetController;
use App\Http\Requests\User\UserGetRequest;
use App\Models\User;
use App\UseCases\User\UserGetUseCase;

\beforeEach(function () {
    $this->request = new UserGetRequest;
});

\describe('__invoke', function () {

    \it('returns a response 200', function () {
        $user = new User;

        $mock = Mockery::mock(new class
        {
            public function __invoke() {}
        });
        $mock->shouldReceive('__invoke')->once();
        \app()->bind(UserGetUseCase::class, fn () => $mock);

        $controller = new UserGetController;
        $result = $controller($this->request, $user);

        \expect($result->getStatusCode())->toBe(200);
    });
});
