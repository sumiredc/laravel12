<?php

declare(strict_types=1);

namespace App\Rules\Auth;

use App\Rules\ValidatorTrait;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

final class PasswordRule implements ValidationRule, ValidatorAwareRule
{
    use ValidatorTrait;

    /**
     * @param Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rule = Password::min(12)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised();

        $validator = Validator::make(
            data: [$attribute => $value],
            rules: [$attribute => [$rule, 'max:100']],
            attributes: [$attribute => $this->getCustomAttribute($attribute)]
        );

        if ($validator->passes()) {
            return;
        }

        $this->setErrorMessages($fail, $validator->messages());
    }
}
