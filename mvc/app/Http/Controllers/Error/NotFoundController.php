<?php

declare(strict_types=1);

namespace App\Http\Controllers\Error;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class NotFoundController extends Controller
{
    public function __invoke(Request $request): void
    {
        Log::warning('endpoint not found', ['path' => $request->uri()->value()]);

        throw new NotFoundException;
    }
}
