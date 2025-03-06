<?php

declare(strict_types=1);

namespace App\Http\Resources\Error;

use Illuminate\Http\Resources\Json\JsonResource;

final class ErrorResource extends JsonResource
{
    public function __construct(string $message)
    {
        parent::__construct([
            'message' => $message,
        ]);
    }
}
