<?php

declare(strict_types=1);

use App\Http\Controllers\User\UserDeleteController;
use App\Http\Requests\User\UserDeleteRequest;
use App\Models\User;
use App\UseCases\User\UserDeleteUseCase;

describe('UserDeleteController', function () {
    beforeEach(function () {
        $this->request = new UserDeleteRequest;
    });

    it('response 204', function () {
        $user = new User;

        $mock = Mockery::mock(new class
        {
            public function __invoke() {}
        });
        $mock->shouldReceive('__invoke')->once();
        app()->bind(UserDeleteUseCase::class, fn () => $mock);

        $controller = new UserDeleteController;
        $result = $controller($this->request, $user);

        expect($result->getStatusCode())->toBe(204);
    });
});
