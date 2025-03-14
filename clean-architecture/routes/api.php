<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\FirstSignInController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignOutController;
use App\Http\Controllers\User\UserCreateController;
use App\Http\Controllers\User\UserDeleteController;
use App\Http\Controllers\User\UserGetController;
use App\Http\Controllers\User\UserListController;
use App\Http\Controllers\User\UserUpdateController;
use Illuminate\Support\Facades\Route;

Route::name('first-sign-in')->post('/first-sign-in', FirstSignInController::class);
Route::name('sign-in')->post('/sign-in', SignInController::class);
Route::name('sign-out')->delete('/sign-out', SignOutController::class)->middleware(['auth:api']);

Route::name('user.')->prefix('/user')->middleware(['auth:api', 'verified'])->group(static function () {
    Route::name('list')->get('', UserListController::class);
    Route::name('create')->post('', UserCreateController::class);
    Route::name('get')->get('/{userID}', UserGetController::class)->whereUlid('userID');
    Route::name('update')->put('/{userID}', UserUpdateController::class)->whereUlid('userID');
    Route::name('delete')->delete('/{userID}', UserDeleteController::class)->whereUlid('userID');
});
