<?php

declare(strict_types=1);

use App\Http\Controllers\User\UserCreateController;
use Illuminate\Support\Facades\Route;

Route::name('user.')->prefix('/user')->group(function () {
    Route::name('create')->post('', UserCreateController::class);
});
