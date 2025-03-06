<?php

declare(strict_types=1);

use App\Http\Controllers\User\UserCreateController;
use App\Http\Controllers\User\UserGetController;
use App\Http\Controllers\User\UserListController;
use Illuminate\Support\Facades\Route;

Route::name('user.')->prefix('/user')->group(function () {
    Route::name('list')->get('', UserListController::class);
    Route::name('create')->post('', UserCreateController::class);
    Route::name('get')->get('/{user}', UserGetController::class)->where('user', '[0-9]+');
});
