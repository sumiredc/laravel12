<?php

declare(strict_types=1);

namespace App\Rules\Common;

use App\Rules\ValidatorTrait;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Validator;

final class PositiveNaturalNumberRule implements ValidationRule, ValidatorAwareRule
{
    use ValidatorTrait;

    /**
     * @param Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = Validator::make(
            data: [$attribute => $value],
            rules: [$attribute => ['integer', sprintf('between:0,%d', PHP_INT_MAX)]],
            attributes: [$attribute => $this->getCustomAttribute($attribute)]
        );

        if ($validator->passes()) {
            return;
        }

        $this->setErrorMessages($fail, $validator->messages());
    }
}
