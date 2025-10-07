<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::post('logout' , [AuthController::class , 'logout'])->name('auth.logout');
    Route::prefix('user')->as('users.')->controller(UserController::class)->group(function () {
        Route::get('show', [UserController::class, 'user'])->name('show');
        Route::post('store', [UserController::class, 'store'])->name('store')->middleware(['can:store-user']);
    });

});
