<?php

declare(strict_types=1);

use App\Http\Requests\User\UserCreateRequestInterface;
use App\Mail\InitialPasswordMail;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\UseCases\User\UserCreateUseCase;
use Illuminate\Support\Facades\Mail;

\beforeEach(function () {
    $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
    $this->request = Mockery::mock(UserCreateRequestInterface::class);
    DB::shouldReceive('transaction')->andReturnUsing(fn ($callback) => $callback());
    Mail::fake();
});

\describe('__invoke', function () {

    \it('creates a new user successfully and sends an initial password email', function () {
        $name = 'test name';
        $email = 'test@xxx.xxx';
        $user = new User(['name' => $name, 'email' => $email]);
        $this->request->shouldReceive('name')->andReturn($name);
        $this->request->shouldReceive('email')->andReturn($email);
        $this->userRepository->shouldReceive('create')->andReturn($user);

        $useCase = new UserCreateUseCase($this->userRepository);
        $result = $useCase($this->request);

        \expect($result->resource['user']->name)->toBe($name);
        \expect($result->resource['user']->email)->toBe($email);
        Mail::assertSent(InitialPasswordMail::class, 1);
    });
});
