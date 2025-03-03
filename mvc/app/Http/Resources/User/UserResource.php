<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** 
 * @property array{user:User} $resource
 */
final class UserResource extends JsonResource
{
    public function __construct(User $user)
    {
        parent::__construct(['user' => $user]);
    }

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
