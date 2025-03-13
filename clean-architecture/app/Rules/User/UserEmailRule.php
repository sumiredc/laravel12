<?php

declare(strict_types=1);

namespace App\Rules\User;

use App\Rules\ValidatorTrait;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Validator;

final class UserEmailRule implements ValidationRule, ValidatorAwareRule
{
    use ValidatorTrait;

    /**
     * @param Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rules = [
            'email:rfc,strict,spoof,filter',
            'max:100',
            'unique:users,email',
        ];

        $validator = Validator::make(
            data: [$attribute => $value],
            rules: [$attribute => $rules],
            attributes: [$attribute => $this->getCustomAttribute($attribute)]
        );

        if ($validator->passes()) {
            return;
        }

        $this->setErrorMessages($fail, $validator->messages());
    }
}
