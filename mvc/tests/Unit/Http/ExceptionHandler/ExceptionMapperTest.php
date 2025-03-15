<?php

declare(strict_types=1);

use App\Exceptions\NotFoundException;
use App\Exceptions\ShouldntReportException;
use App\Exceptions\UnauthorizedException;
use App\Http\ExceptionHandler\ExceptionMapper;
use App\Http\Resources\Error\UnprocessableContentResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

\describe('mapForAuthorization', function () {
    \beforeEach(function () {
        $this->mapper = new ExceptionMapper;
    });

    \it('convert to UnauthorizedException', function () {
        $ex = new AuthenticationException;
        $result = $this->mapper->mapForAuthorization($ex);

        \expect($result::class)->toBe(UnauthorizedException::class);
    });

    \it('dont convert', function () {
        $result = $this->mapper->mapForAuthorization(new Exception);

        \expect($result::class)->toBe(Exception::class);
    });
});

\describe('mappForNotFound', function () {
    \beforeEach(function () {
        $this->mapper = new ExceptionMapper;
    });

    \it('convert to NotFoundException', function ($class) {
        Log::partialMock()->shouldReceive('warning')->once();

        $request = Request::create('http://xxx.xxx');
        $ex = new $class;

        $result = $this->mapper->mapForNotFound($ex, $request);

        \expect($result::class)->toBe(NotFoundException::class);
    })
        ->with([
            NotFoundHttpException::class,
            ModelNotFoundException::class,
        ]);

    \it('dont convert', function () {
        Log::partialMock()->shouldReceive('warning')->never();

        $request = Request::create('http://xxx.xxx');

        $result = $this->mapper->mapForNotFound(new Exception, $request);

        \expect($result::class)->toBe(Exception::class);
    });
});

\describe('hiddenPrivateException', function () {
    \beforeEach(function () {
        $this->mapper = new ExceptionMapper;
    });

    \it('return to arg Exception', function () {
        $ex = new Exception;

        $result = $this->mapper->hiddenPrivateException($ex);

        \expect($result)->toBe($ex);
    });

    \it('return to ShouldntReportException match private exceptions', function () {
        $ex = new QueryException('connection', 'DUMMY SELECT SQL', [], new Exception);

        $result = $this->mapper->hiddenPrivateException($ex);

        \expect($result::class)->toBe(ShouldntReportException::class);
        \expect($result->getCode())->toBe(500);
    });

});

\describe('exceptionToJsonResource', function () {
    \beforeEach(function () {
        $this->mapper = new ExceptionMapper;
    });

    \it('return to UnprocessableContentResource', function () {
        $validator = Validator::make([], ['v' => 'required'], ['v.required' => 'v is required.']);
        $ex = new ValidationException($validator);

        $result = $this->mapper->exceptionToJsonResource($ex);

        \expect($result::class)->toBe(UnprocessableContentResource::class);
    });

    \it('return to ErrorResource', function () {
        $message = 'error message';

        $result = $this->mapper->exceptionToJsonResource(new Exception($message));

        \expect($result->resource)->toBe(['message' => $message]);
    });
});

\describe('getStatusCode', function () {
    \beforeEach(function () {
        $this->mapper = new ExceptionMapper;
    });

    \it('return to getCode method value', function () {
        $ex = new class
        {
            public function getCode()
            {
                return 599;
            }
        };

        $result = $this->mapper->getStatusCode($ex);

        \expect($result)->toBe(599);
    });
    \it('return to getStatus method value', function () {
        $ex = new class
        {
            public function getStatus()
            {
                return '598';
            }
        };

        $result = $this->mapper->getStatusCode($ex);

        \expect($result)->toBe(598);
    });
    \it('return to getStatusCode method value', function () {
        $ex = new class
        {
            public function getStatusCode()
            {
                return 597;
            }
        };

        $result = $this->mapper->getStatusCode($ex);

        \expect($result)->toBe(597);
    });
    \it('return to status property value', function () {
        $ex = new class
        {
            public $status = '499';
        };

        $result = $this->mapper->getStatusCode($ex);

        \expect($result)->toBe(499);
    });
    \it('return to INTERNAL SERVER ERROR code, invalid status code all', function ($ex) {
        Log::shouldReceive('warning')->once();

        $result = $this->mapper->getStatusCode($ex);

        \expect($result)->toBe(500);
    })
        ->with([
            'less than 100' => new class
            {
                public $status = 99;

                public function getCode()
                {
                    return '99';
                }

                public function getStatusCode()
                {
                    return 99;
                }

                public function getStatus()
                {
                    return '99';
                }
            },
            'over 600' => new class
            {
                public $status = '600';

                public function getCode()
                {
                    return 600;
                }

                public function getStatusCode()
                {
                    return '600';
                }

                public function getStatus()
                {
                    return 600;
                }
            },
        ]);
    \it('return to INTERNAL SERVER ERROR code, dont status code methods and properties', function () {
        Log::shouldReceive('warning')->once();

        $result = $this->mapper->getStatusCode(new class {});

        \expect($result)->toBe(500);
    });
});
