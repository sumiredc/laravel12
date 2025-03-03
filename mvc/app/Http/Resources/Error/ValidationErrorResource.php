<?php

declare(strict_types=1);

namespace App\Http\Resources\Error;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array{status:int,message:string,errors:array}
 */
final class ValidationErrorResource extends JsonResource
{
    private const MESSAGE = 'Invalid input detected. Please review and correct your entries.';

    public function __construct(array $errors)
    {
        parent::__construct([
            'message' => self::MESSAGE,
            'errors' => $errors,
        ]);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
