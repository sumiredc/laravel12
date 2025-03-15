<?php

declare(strict_types=1);

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Error\NotFoundController;
use Illuminate\Http\Request;

\beforeEach(function () {
    $this->request = Request::create('http://dummy.xxx');
});

\describe('NotFoundController', function () {

    \it('logs a warning and throws NotFoundException when a request is not found', function () {
        Log::shouldReceive('warning')->once();

        $controller = new NotFoundController;
        $controller($this->request);
    })
        ->throws(NotFoundException::class);
});
