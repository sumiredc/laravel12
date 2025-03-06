<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UserCreateUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function __invoke(
        UserCreateRequest $request
    ): JsonResource {
        DB::beginTransaction();

        try {
            $user = $this->userRepository
                ->create(
                    $request->name(),
                    $request->email(),
                );

            DB::commit();

            return new UserResource($user);
        } catch (Throwable $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
