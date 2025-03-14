<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\SignOutController;
use App\Http\Requests\Auth\SignOutRequest;
use App\UseCases\Auth\SignOutUseCase;

describe('SignOutController', function () {
    beforeEach(function () {
        $this->request = new SignOutRequest;
    });

    it('response 200', function () {
        $mock = Mockery::mock(new class
        {
            public function __invoke() {}
        });
        $mock->shouldReceive('__invoke')->once();
        app()->bind(SignOutUseCase::class, fn () => $mock);

        $controller = new SignOutController;
        $result = $controller($this->request);

        expect($result->getStatusCode())->toBe(204);
    });
});
