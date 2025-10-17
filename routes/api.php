<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserGroupController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::post('logout' , [AuthController::class , 'logout'])->name('auth.logout');

    //users
    Route::prefix('user')->as('users.')->controller(UserController::class)->group(function () {
        Route::get('show', [UserController::class, 'user'])->name('show')->middleware(['can:show-user']);
        Route::post('store', [UserController::class, 'store'])->name('store')->middleware(['can:store-user']);
    });

    //user gorups
    Route::prefix('user-group')->as('user-group')->controller(UserGroupController::class)->group(function(){
        Route::post('' , 'index')->name('index')->middleware(['can:index-user-groups']);
        Route::post('store' , 'store')->name('store')->middleware(['can:store-user-group']);
        Route::post('{userGroup}/update' , 'update')->name('update')->middleware(['can:update-user-group']);
        Route::post('delete' , 'delete')->name('delete')->middleware(['can:delete-user-group']);
        Route::post('show' , 'show')->name('show')->middleware(['can:show-user-group']);
    });

});
