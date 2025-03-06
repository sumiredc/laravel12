<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Exceptions\ForbiddenException;
use App\Exceptions\UnprocessableContentException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Gate;

trait AuthorizeTrait
{
    public function can(string $ability, array $argments)
    {
        $response = Gate::inspect($ability, $argments);

        if ($response->denied()) {
            throw new ForbiddenException(
                message: $response->message(),
                code: intval($response->code())
            );
        }

        return true;
    }

    /**
     * @throws HttpResponseException,\Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->isJson()) {
            throw new UnprocessableContentException($validator->errors()->toArray());
        }

        parent::failedValidation($validator);

    }
}
