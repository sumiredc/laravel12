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

\beforeEach(function () {
    $this->mapper = new ExceptionMapper;
});

\describe('mapForAuthorization', function () {

    \it('returns UnauthorizedException', function () {
        $ex = new AuthenticationException;
        $result = $this->mapper->mapForAuthorization($ex);

        \expect($result::class)->toBe(UnauthorizedException::class);
    });

    \it('does not convert other exceptions', function () {
        $result = $this->mapper->mapForAuthorization(new Exception);

        \expect($result::class)->toBe(Exception::class);
    });
});

\describe('mapForNotFound', function () {

    \it('converts to NotFoundException', function ($class) {
        Log::partialMock()->shouldReceive('warning')->once();

        $request = Request::create('http://example.com');
        $ex = new $class;

        $result = $this->mapper->mapForNotFound($ex, $request);

        \expect($result::class)->toBe(NotFoundException::class);
    })
        ->with([
            NotFoundHttpException::class,
            ModelNotFoundException::class,
        ]);

    \it('does not convert other exceptions', function () {
        Log::partialMock()->shouldReceive('warning')->never();

        $request = Request::create('http://example.com');

        $result = $this->mapper->mapForNotFound(new Exception, $request);

        \expect($result::class)->toBe(Exception::class);
    });
});

\describe('hiddenPrivateException', function () {

    \it('returns the original exception', function () {
        $ex = new Exception;

        $result = $this->mapper->hiddenPrivateException($ex);

        \expect($result)->toBe($ex);
    });

    \it('converts private exceptions to ShouldntReportException', function () {
        $ex = new QueryException('connection', 'DUMMY SELECT SQL', [], new Exception);

        $result = $this->mapper->hiddenPrivateException($ex);

        \expect($result::class)->toBe(ShouldntReportException::class);
        \expect($result->getCode())->toBe(500);
    });

});

\describe('exceptionToJsonResource', function () {

    \it('returns UnprocessableContentResource for validation exceptions', function () {
        $validator = Validator::make([], ['v' => 'required'], ['v.required' => 'v is required.']);
        $ex = new ValidationException($validator);

        $result = $this->mapper->exceptionToJsonResource($ex);

        \expect($result::class)->toBe(UnprocessableContentResource::class);
    });

    \it('returns ErrorResource for general exceptions', function () {
        $message = 'error message';

        $result = $this->mapper->exceptionToJsonResource(new Exception($message));

        \expect($result->resource)->toBe(['message' => $message]);
    });
});

\describe('getStatusCode', function () {

    \it('returns value from getCode method', function () {
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

    \it('returns value from getStatus method', function () {
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

    \it('returns value from getStatusCode method', function () {
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

    \it('returns value from status property', function () {
        $ex = new class
        {
            public $status = '499';
        };

        $result = $this->mapper->getStatusCode($ex);

        \expect($result)->toBe(499);
    });

    \it('returns 500 for invalid status codes', function ($ex) {
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

    \it('returns 500 when no status code methods or properties exist', function () {
        Log::shouldReceive('warning')->once();

        $result = $this->mapper->getStatusCode(new class {});

        \expect($result)->toBe(500);
    });
});
