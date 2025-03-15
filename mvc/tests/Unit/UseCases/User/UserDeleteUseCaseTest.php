<?php

declare(strict_types=1);

use App\Http\Requests\User\UserDeleteRequestInterface;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\UseCases\User\UserDeleteUseCase;

\beforeEach(function () {
    $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
    $this->request = Mockery::mock(UserDeleteRequestInterface::class);
});

\describe('__invoke', function () {

    \it('deletes the user successfully', function () {
        $user = new User;

        $this->userRepository->shouldReceive('delete')->once();

        $useCase = new UserDeleteUseCase($this->userRepository);
        $useCase($this->request, $user);
    });
});
