<?php

declare(strict_types=1);

use App\Http\Controllers\Error\NotFoundController;
use Illuminate\Support\Facades\Route;

Route::fallback(NotFoundController::class);
