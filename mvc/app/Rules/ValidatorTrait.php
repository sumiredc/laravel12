<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;

trait ValidatorTrait
{
    protected Validator $validator;

    public function setValidator(Validator $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    protected function getCustomAttribute(string $attribute): string
    {
        return $this->validator->customAttributes[$attribute] ?? $attribute;
    }

    /**
     * @param Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    protected function setErrorMessages(Closure $fail, MessageBag $messageBag): void
    {
        foreach ($messageBag->all() as $message) {
            $fail($message);
        }
    }
}
