<?php

declare(strict_types=1);

namespace App\Http\Resources\Error;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array{status:int,message:string} $resource
 */
final class InternalServerErrorResource extends JsonResource
{
    private const MESSAGE = 'An error occurred while processing your request. Please contact support if the issue persists.';

    public function __construct()
    {
        parent::__construct([
            'message' => self::MESSAGE,
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
