<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\SignInController;
use Illuminate\Support\Facades\Route;

Route::name('sign-in')->post('/sign-in', SignInController::class);
