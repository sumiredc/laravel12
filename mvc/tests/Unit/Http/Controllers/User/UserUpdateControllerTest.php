<?php

declare(strict_types=1);

use App\Http\Controllers\User\UserUpdateController;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\UseCases\User\UserUpdateUseCase;

\beforeEach(function () {
    $this->request = new UserUpdateRequest;
});

\describe('invoke', function () {

    \it('returns a response 200', function () {
        $user = new User;

        $mock = Mockery::mock(new class
        {
            public function __invoke() {}
        });
        $mock->shouldReceive('__invoke')->once();
        \app()->bind(UserUpdateUseCase::class, fn () => $mock);

        $controller = new UserUpdateController;
        $result = $controller($this->request, $user);

        \expect($result->getStatusCode())->toBe(200);
    });
});
