<?php

declare(strict_types=1);

use App\Exceptions\AppError;

describe('should initialize AppError with expected values', function () {
    it('default argments', function () {
        $appError = new class extends AppError
        {
            protected $code = 502;

            protected $message = 'default error message';
        };

        expect($appError->getCode())->toBe(502);
        expect($appError->getMessage())->toBe('default error message');
    });

    it('custom argments', function () {
        $code = 404;
        $message = 'custom error message';
        $typeError = new TypeError;
        $appError = new class($message, $code, $typeError) extends AppError {};

        expect($appError->getCode())->toBe($code);
        expect($appError->getMessage())->toBe($message);
        expect($appError->getPrevious())->toBe($typeError);
    });
});
