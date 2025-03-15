<?php

declare(strict_types=1);

use App\Http\Requests\User\UserListRequestInterface;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\UseCases\User\UserListUseCase;

\beforeEach(function () {
    $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
    $this->request = Mockery::mock(UserListRequestInterface::class);
});

\describe('__invoke', function () {

    \it('retfieves a list of users successfully', function () {
        $count = 10;
        $users = \array_fill(0, $count, new User);

        $this->userRepository->shouldReceive('list')->once()->andReturn(\collect($users));
        $this->request->shouldReceive('offset')->once()->andReturn(0);
        $this->request->shouldReceive('limit')->once()->andReturn(100);
        $this->request->shouldReceive('name')->once()->andReturn('test name');
        $this->request->shouldReceive('email')->once()->andReturn('test@xxx.xxx');

        $useCase = new UserListUseCase($this->userRepository);
        $result = $useCase($this->request);

        \expect(\count($result->resource['users']))->toBe($count);
    });
});
