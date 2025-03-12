<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignOutController;
use Illuminate\Support\Facades\Route;

// Route::name('first-sign-in')->post('/first-sign-in', FirstSignInController::class);
Route::name('sign-in')->post('/sign-in', SignInController::class);
Route::name('sign-out')->delete('/sign-out', SignOutController::class)->middleware(['auth:api']);

// Route::name('user.')->prefix('/user')->middleware(['auth:api', 'verified'])->group(static function () {
//     Route::name('list')->get('', UserListController::class);
//     Route::name('create')->post('', UserCreateController::class);
//     Route::name('get')->get('/{user}', UserGetController::class)->whereUlid('user');
//     Route::name('update')->put('/{user}', UserUpdateController::class)->whereUlid('user');
//     Route::name('delete')->delete('/{user}', UserDeleteController::class)->whereUlid('user');
// });
