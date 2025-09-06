<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::controller(AuthController::class)->group(function (){
    Route::get('login' , 'loginView')->name('login');
    Route::post('login' , 'login')->name('submit-login');
    Route::get('register' , 'registerView')->name('register');
    Route::post('register' , 'register')->name('submit-register');
    Route::get('start-pkce' , 'startPkce');
});
