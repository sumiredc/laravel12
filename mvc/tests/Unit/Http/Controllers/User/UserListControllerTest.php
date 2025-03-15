<?php

declare(strict_types=1);

use App\Http\Controllers\User\UserListController;
use App\Http\Requests\User\UserListRequest;
use App\Http\Resources\User\UserListResource;
use App\Models\User;
use App\UseCases\User\UserListUseCase;

\beforeEach(function () {
    $this->request = new UserListRequest;
});

\describe('__invoke', function () {

    \it('returns a response 200', function () {
        $resource = new UserListResource(\collect(new User));

        $mock = Mockery::mock(new class
        {
            public function __invoke() {}
        });
        $mock->shouldReceive('__invoke')->once()->andReturn($resource);
        \app()->bind(UserListUseCase::class, fn () => $mock);

        $controller = new UserListController;
        $result = $controller($this->request);

        \expect($result->getStatusCode())->toBe(200);
    });
});
