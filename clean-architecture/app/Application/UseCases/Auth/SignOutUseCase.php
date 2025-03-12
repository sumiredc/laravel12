<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Domain\Shared\Result;
use App\Exceptions\InternalServerErrorException;
use App\Infra\Repositories\TokenRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

final class SignOutUseCase
{
    public function __construct(private readonly TokenRepository $tokenRepository) {}

    /** @return Result<SignOutOutput,InternalServerErrorException> */
    public function __invoke(SignOutInput $input): Result
    {
        try {
            DB::transaction(function () use ($input) {
                $result = $this->tokenRepository->revokeUserAuthorizationToken($input->user);
                if ($result->isErr()) {
                    throw $result->getError();
                }
            });

            $output = new SignOutOutput;

            return Result::ok($output);
        } catch (Throwable $th) {
            return Result::err($th);
        }
    }
}
