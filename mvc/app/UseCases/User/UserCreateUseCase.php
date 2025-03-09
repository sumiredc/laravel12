<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\User\UserResource;
use App\Mail\InitialPasswordMail;
use App\Repositories\UserRepositoryInterface;
use App\ValueObjects\Password;
use App\ValueObjects\User\UserID;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

final class UserCreateUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(
        UserCreateRequest $request
    ): UserResource {
        return DB::transaction(function () use ($request) {
            $password = Password::make();
            $userID = UserID::make();
            $user = $this->userRepository
                ->create(
                    $userID,
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
