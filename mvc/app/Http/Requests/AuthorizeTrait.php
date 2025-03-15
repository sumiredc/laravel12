<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Exceptions\ForbiddenException;
use Illuminate\Support\Facades\Gate;

use function intval;

trait AuthorizeTrait
{
    public function can(string $ability, array $argments)
    {
        $response = Gate::inspect($ability, $argments);

        if ($response->denied()) {
            throw new ForbiddenException(
                message: $response->message(),
                code: intval($response->code())
            );
        }

        return true;
    }
}
