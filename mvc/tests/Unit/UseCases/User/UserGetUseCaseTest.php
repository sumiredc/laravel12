<?php

declare(strict_types=1);

use App\Http\Requests\User\UserGetRequestInterface;
use App\Models\User;
use App\UseCases\User\UserGetUseCase;
use App\ValueObjects\User\UserID;

\beforeEach(function () {
    $this->request = Mockery::mock(UserGetRequestInterface::class);
});

\describe('__invoke', function () {

    \it('retrieves the user successfully', function () {
        $userID = UserID::make();
        $user = new User(['id' => $userID]);

        $useCase = new UserGetUseCase;
        $result = $useCase($this->request, $user);

        \expect((string) $result->resource['user']->id)->toBe((string) $userID);
    });
});
