<?php

declare(strict_types=1);

namespace App\Rules\User;

use App\Rules\ValidatorTrait;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

final class UserNameRule implements ValidationRule
{
    use ValidatorTrait;

    /**
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = Validator::make(
            data: [$attribute => $value],
            rules: [$attribute =>  ['string', 'max:100']],
            attributes: [$attribute => $this->getCustomAttribute($attribute)],
        );

        if ($validator->passes()) {
            return;
        }

        $this->setErrorMessages($fail, $validator->messages());
    }
}
