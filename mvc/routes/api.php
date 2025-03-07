<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\User\UserCreateController;
use App\Http\Controllers\User\UserDeleteController;
use App\Http\Controllers\User\UserGetController;
use App\Http\Controllers\User\UserListController;
use App\Http\Controllers\User\UserUpdateController;
use Illuminate\Support\Facades\Route;

Route::name('sign-in')->post('/sign-in', SignInController::class);

Route::name('user.')->prefix('/user')->middleware(['auth:api'])->group(static function () {
    Route::name('list')->get('', UserListController::class);
    Route::name('create')->post('', UserCreateController::class);
    Route::name('get')->get('/{user}', UserGetController::class)->where('user', '[0-9]+');
    Route::name('update')->put('/{user}', UserUpdateController::class)->where('user', '[0-9]+');
    Route::name('delete')->delete('/{user}', UserDeleteController::class)->where('user', '[0-9]+');
});
