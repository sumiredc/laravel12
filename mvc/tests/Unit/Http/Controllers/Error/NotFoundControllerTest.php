<?php

declare(strict_types=1);

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Error\NotFoundController;
use Illuminate\Http\Request;

describe('NotFoundController', function () {
    beforeEach(function () {
        $this->request = Request::create('http://dummy.xxx');
    });

    it('throws NotFoundException', function () {
        Log::shouldReceive('warning')->once();

        $controller = new NotFoundController;
        $controller($this->request);
    })
        ->throws(NotFoundException::class);
});
