<?php

declare(strict_types=1);

namespace App\InterfaceAdapter\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Validator as ValidationValidator;

abstract class Validator
{
    final public function __construct() {}

    final public static function make(Request $request): ValidationValidator
    {
        $validator = new static;

        return FacadesValidator::make(
            data: $request->all(),
            messages: $validator->messages(),
            rules: $validator->rules(),
            attributes: $validator->attributes(),
        );
    }

    abstract public function rules(): array;

    abstract public function attributes(): array;

    abstract public function messages(): array;
}
