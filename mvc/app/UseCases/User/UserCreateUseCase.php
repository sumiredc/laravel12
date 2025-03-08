<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\User\UserResource;
use App\Mail\InitialPasswordMail;
use App\Repositories\UserRepository;
use App\ValueObjects\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

final class UserCreateUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function __invoke(
        UserCreateRequest $request
    ): UserResource {
        return DB::transaction(function () use ($request) {
            $password = Password::make();
            $user = $this->userRepository
                ->create(
                    $request->name(),
                    $request->email(),
                    $password->hashed
                );

            $template = new InitialPasswordMail($password->plain);
            Mail::to($user)->send($template);

            return new UserResource($user);
        });
    }
}
