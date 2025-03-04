<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Resources\Error\ValidationErrorResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

trait AuthorizeTrait
{
    public function can(string $ability, array $argments)
    {
        $response = Gate::inspect($ability, $argments);

        if ($response->denied()) {
            throw new AuthorizationException($response->message(), $response->code());
        }

        return true;
    }

    /**
     * @throws HttpResponseException,\Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if (!$this->isJson()) {
            parent::failedValidation($validator);

            return;
        }

        $response = new JsonResponse(
            new ValidationErrorResource($validator->errors()->toArray()),
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        );

        throw new HttpResponseException($response);
    }
}
