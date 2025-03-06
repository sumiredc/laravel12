<?php

declare(strict_types=1);

namespace App\Http\Resources\Error;

use Illuminate\Http\Resources\Json\JsonResource;

final class UnprocessableContentResource extends JsonResource
{
    public function __construct(string $message, array $errors)
    {
        parent::__construct([
            'message' => $message,
            'errors' => $errors,
        ]);
    }
}
