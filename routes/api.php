<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    
    Route::get('/user', [UserController::class, 'user'])->middleware(['can:read-student']);
});


