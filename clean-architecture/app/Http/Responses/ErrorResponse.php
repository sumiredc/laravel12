<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

final class ErrorResponse extends JsonResponse
{
    public function __construct(int $status, $headers = [])
    {
        if ($status < 400 || $status >= 600) {
            throw new InvalidArgumentException;
        }

        parent::__construct([
            'message' => self::$statusTexts[$status],
        ], $status, $headers);
    }
}
