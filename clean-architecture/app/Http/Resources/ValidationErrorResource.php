<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ValidationErrorResource extends JsonResource
{
    public function __construct(array $errors)
    {
        parent::__construct(compact('errors'));
    }
}
