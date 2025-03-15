<?php

declare(strict_types=1);

use App\Http\ExceptionHandler\ExceptionMapper;
use App\Http\ExceptionHandler\JsonResolver;
use Illuminate\Http\Request;

describe('shouldReturnJsonResponse', function () {
    beforeEach(function () {
        $this->request = Request::create('http://xxx.xxx');
        $this->resolver = new JsonResolver;
    });

    it('enable json with Request header in Content-type: application/json', function () {
        $this->request->headers->set('CONTENT_TYPE', 'application/json');

        $func = $this->resolver->shouldReturnJsonResponse();
        $result = $func($this->request, new Exception);

        expect($result)->toBeTrue();
    });

    it('enable json with Request header in Accept: application/json', function () {
        $this->request->headers->set('Accept', 'application/json');

        $func = $this->resolver->shouldReturnJsonResponse();
        $result = $func($this->request, new Exception);

        expect($result)->toBeTrue();
    });

    it('desable json', function () {
        $func = $this->resolver->shouldReturnJsonResponse();
        $result = $func($this->request, new Exception);

        expect($result)->toBeFalse();
    });
});

describe('createJsonExceptionRenderer', function () {
    beforeEach(function () {
        $this->request = Request::create('http://xxx.xxx');
        $this->resolver = new JsonResolver;
    });

    it('dont allow JsonResponse', function () {
        $func = $this->resolver->createJsonExceptionRenderer();
        $result = $func(new Exception, $this->request);

        expect($result)->toBeNull();
    });

    it('return to JsonResponse', function () {
        interface MapplerInterface
        {
            public function hiddenPrivateException();

            public function mapForAuthorization();

            public function mapForNotFound();

            public function exceptionToJsonResource();

            public function getStatusCode();
        }

        $ex = new Exception;
        $resource = 'resource';
        $status = 501;

        $mock = Mockery::mock(MapplerInterface::class);
        $mock->shouldReceive('hiddenPrivateException')->with($ex)->once()->andReturn($ex);
        $mock->shouldReceive('mapForAuthorization')->with($ex)->once()->andReturn($ex);
        $mock->shouldReceive('mapForNotFound')->with($ex, $this->request)->once()->andReturn($ex);
        $mock->shouldReceive('exceptionToJsonResource')->with($ex, $this->request)->once()->andReturn($resource);
        $mock->shouldReceive('getStatusCode')->with($ex)->once()->andReturn($status);
        app()->bind(ExceptionMapper::class, fn () => $mock);
        $this->request->headers->set('CONTENT_TYPE', 'application/json');

        $func = $this->resolver->createJsonExceptionRenderer();
        $result = $func($ex, $this->request);

        expect($result->getStatusCode())->toBe($status);
        expect($result->getData())->toBe($resource);
    });
});
