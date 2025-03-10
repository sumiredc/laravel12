<?php

declare(strict_types=1);

use App\Http\Requests\User\UserUpdateRequestInterface;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\UseCases\User\UserUpdateUseCase;
use App\ValueObjects\User\UserID;
use Illuminate\Support\Facades\DB;

describe('UserUpdateUseCase', function () {
    beforeEach(function () {
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->request = Mockery::mock(UserUpdateRequestInterface::class);
        DB::shouldReceive('transaction')->andReturnUsing(fn ($callback) => $callback());
    });

    it('success to update user', function () {
        $userID = UserID::make();
        $user = new User(['id' => $userID]);

        $this->userRepository->shouldReceive('update')->once()->andReturn($user);
        $this->request->shouldReceive('name')->andReturn('test name');
        $this->request->shouldReceive('email')->andReturn('test@xxx.xxx');

        $useCase = new UserUpdateUseCase($this->userRepository);
        $result = $useCase($this->request, $user);

        expect($result->resource['user']->id)->toBe($userID);
    });
});
